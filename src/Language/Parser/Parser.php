<?php

declare(strict_types=1);

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Parser;

use Generator;
use Symbiont\Language\Ast\Statement\StatementList;
use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Parser\Symbol\SymbolTableInterface;
use Symbiont\Language\Tokenizer\TokenStreamInterface;

class Parser implements ParserInterface
{
    use BlockParser;
    use ExpressionParser;
    use StatementParser;

    private ?string $endToken;

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
}
