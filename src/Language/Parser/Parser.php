<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Parser;

use ArrayIterator;
use Generator;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Ast\Statement\Statement;
use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Ast\Statement\StatementList;
use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Parser\Symbol\StatementSymbolInterface;
use Symbiont\Language\Parser\Symbol\SymbolTableInterface;
use Symbiont\Language\Tokenizer\TokenStreamInterface;

class Parser implements ParserInterface
{
    private string $startBlockToken;

    private string $endBlockToken;

    private ?string $endStatementToken;

    private ?string $endToken;

    /** @var array|string[] */
    private array $endStatementList;

    private SymbolTableInterface $symbols;

    /**
     * Constructor.
     *
     * @param SymbolTableInterface $symbols
     * @param string               $startBlockToken
     * @param string               $endBlockToken
     * @param string|null          $endStatementToken
     * @param string|null          $endToken
     */
    public function __construct(
        SymbolTableInterface $symbols,
        string $startBlockToken,
        string $endBlockToken,
        string $endStatementToken = null,
        string $endToken = null
    ) {
        $this->startBlockToken   = $startBlockToken;
        $this->endStatementToken = $endStatementToken;
        $this->endToken          = $endToken;
        $this->symbols           = $symbols;
        $this->endBlockToken     = $endBlockToken;

        $this->endStatementList = [
            $endBlockToken => $endBlockToken
        ];

        if ($endToken !== null) {
            $this->endStatementList[$endToken] = $endToken;
        }
    }

    /**
     * Parse the given token stream into a list of statements.
     *
     * @param TokenStreamInterface $tokens
     *
     * @return StatementListInterface
     */
    public function __invoke(
        TokenStreamInterface $tokens
    ): StatementListInterface {
        $context          = new ParseContext($this, $tokens, $this->symbols);
        $statementBuilder = function (
            ParseContextInterface $context,
            TokenStreamInterface $tokens
        ): Generator {
            yield from $this->parseStatements($context);

            // Ensure the end of the program is reached.
            if ($this->endToken !== null) {
                $tokens->advance($this->endToken);
            }
        };

        return new StatementList($statementBuilder($context, $tokens));
    }

    /**
     * Create an abstract syntax tree.
     *
     * @param ParseContextInterface $context
     * @param int                   $bindingPower
     *
     * @return NodeInterface
     */
    public function parseExpression(
        ParseContextInterface $context,
        int $bindingPower
    ): NodeInterface {
        $current = $this->symbols->getSymbol($context->current());
        $left    = $current->nud($context);
        $subject = $context->advance();
        $symbol  = $this->symbols->getSymbol($subject);

        while (
            $symbol !== null
            && $bindingPower < $symbol->getBindingPower()
        ) {
            $context->advance();
            $left    = $symbol->led($context, $subject, $left);
            $subject = $context->current();
            $symbol  = $this->symbols->getSymbol($subject);
        }

        return $left;
    }

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
        $token  = $context->current();
        $symbol = $this->symbols->getSymbol($token);

        if ($symbol instanceof StatementSymbolInterface) {
            $context->advance();
            $statement = $symbol->std($context);
            $statement->rewind();
            $context->getScope()->reserve($statement->current(), $symbol);
        } else {
            $statement = new Statement(
                new ArrayIterator(
                    [$context->parseExpression(0)]
                )
            );

            if ($this->endStatementToken !== null) {
                $context->advance($this->endStatementToken);
            }
        }

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

    /**
     * Parse the current code block.
     *
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     *
     * @throws SyntaxException When the current symbol is not a statement.
     */
    public function parseBlock(
        ParseContextInterface $context
    ): StatementInterface {
        $symbol = $this->symbols->getSymbol($context->current());

        if (!$symbol instanceof StatementSymbolInterface) {
            throw $symbol->createException(
                $context->current(),
                'Symbol does not denote a statement.'
            );
        }

        $context->advance($this->startBlockToken);

        return $symbol->std($context);
    }
}
