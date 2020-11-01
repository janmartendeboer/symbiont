<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symbiont\Language\Parser;

use ArrayIterator;
use Generator;
use Symbiont\Language\Ast\Statement\Statement;
use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Ast\Statement\StatementList;
use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Parser\Symbol\StatementSymbolInterface;

trait StatementParser
{
    /** @var array|string[] */
    private array $endStatementList;

    private ?string $endStatementToken;

    /**
     * Parse the current statement.
     *
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     */
    public function parseStatement(
        ParseContextInterface $context
    ): StatementInterface {
        $symbol = $context->getSymbol((string)$context->current());

        return (
            $symbol instanceof StatementSymbolInterface
                ? $this->parseStatementSymbol($symbol, $context)
                : $this->parseStatementExpression($context)
        );
    }

    /**
     * Parse a statement by parsing it as an expression.
     *
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     */
    private function parseStatementExpression(
        ParseContextInterface $context
    ): StatementInterface {
        $statement = new Statement(
            new ArrayIterator(
                [$context->parseExpression(0)]
            )
        );

        if ($this->endStatementToken !== null) {
            $context->advance($this->endStatementToken);
        }

        return $statement;
    }

    /**
     * Parse statement symbol using the provided context.
     *
     * @param StatementSymbolInterface $symbol
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     */
    private function parseStatementSymbol(
        StatementSymbolInterface $symbol,
        ParseContextInterface $context
    ): StatementInterface {
        $context->advance();
        $statement = $symbol->std($context);
        $statement->rewind();

        return $statement;
    }

    /**
     * Parse the current list of statements;
     *
     * @param ParseContextInterface $context
     *
     * @return StatementListInterface
     */
    public function parseStatements(
        ParseContextInterface $context
    ): StatementListInterface {
        $statementBuilder = function (ParseContextInterface $context): Generator {
            while (true) {
                $token = $context->current();

                if (
                    $token === null
                    || in_array($token->getName(), $this->endStatementList, true)
                ) {
                    break;
                }

                yield $context->parseStatement();
            }
        };

        return new StatementList($statementBuilder($context));
    }
}
