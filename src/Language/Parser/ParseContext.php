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
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Ast\Statement\StatementList;
use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Parser\Scope\BlockScope;
use Symbiont\Language\Parser\Scope\ScopeInterface;
use Symbiont\Language\Parser\Symbol\SymbolHolderInterface;
use Symbiont\Language\Parser\Symbol\SymbolInterface;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\TokenStreamInterface;
use Symbiont\Language\Tokenizer\UnexpectedEndOfStreamException;
use Symbiont\Language\Tokenizer\UnexpectedTokenException;

class ParseContext implements ParseContextInterface
{
    private ScopeInterface $scope;

    private ParserInterface $parser;

    private TokenStreamInterface $tokens;

    private SymbolHolderInterface $symbols;

    /**
     * Constructor.
     *
     * @param ParserInterface       $parser
     * @param TokenStreamInterface  $tokens
     * @param SymbolHolderInterface $symbols
     * @param ScopeInterface|null   $scope
     */
    public function __construct(
        ParserInterface $parser,
        TokenStreamInterface $tokens,
        SymbolHolderInterface $symbols,
        ScopeInterface $scope = null
    ) {
        $this->parser  = $parser;
        $this->tokens  = $tokens;
        $this->symbols = $symbols;
        $this->scope   = $scope ?? new BlockScope();
    }

    /**
     * Get the current scope.
     *
     * @return ScopeInterface
     */
    public function getScope(): ScopeInterface
    {
        return $this->scope;
    }

    /**
     * Produce the next token, ensuring the token matches the requested token
     * identifier.
     *
     * When the requested token identifier is null, any token may be produced.
     *
     * @param string|null $token
     *
     * @return TokenInterface
     *
     * @throws UnexpectedTokenException When the produced token does not match.
     * @throws UnexpectedEndOfStreamException When the token stream has ended.
     */
    public function advance(string $token = null): TokenInterface
    {
        return $this->tokens->advance($token);
    }

    /**
     * Get the current token, if the token stream has started.
     *
     * @param string|null $token
     *
     * @return TokenInterface|null
     *
     * @throws UnexpectedTokenException When the produced token does not match.
     */
    public function current(string $token = null): ?TokenInterface
    {
        $current = $this->tokens->current();

        if ($token !== null && $current->getName() !== $token) {
            throw new UnexpectedTokenException($token, $current);
        }

        return $current;
    }

    /**
     * Parse the current expression with the given binding power.
     *
     * @param int $bindingPower
     *
     * @return NodeInterface
     */
    public function parseExpression(int $bindingPower): NodeInterface
    {
        return $this->parser->parseExpression($this, $bindingPower);
    }

    /**
     * Parse the current statement.
     *
     * @return StatementInterface
     */
    public function parseStatement(): StatementInterface
    {
        return $this->parser->parseStatement($this);
    }

    /**
     * Parse the current list of statements.
     *
     * @return StatementListInterface
     */
    public function parseStatements(): StatementListInterface
    {
        // Workaround to parse nested statements synchronously.
        // This is done so it is easier to assert the next token after parsing
        // a block.
        // It is done within the parse context, so the parser itself will yield
        // One statement at a time, for the root scope.
        return new StatementList(
            new ArrayIterator(
                iterator_to_array(
                    $this->parser->parseStatements($this)
                )
            )
        );
    }

    /**
     * Parse the current code block.
     *
     * @return StatementInterface
     */
    public function parseBlock(): StatementInterface
    {
        return $this->parser->parseBlock($this);
    }

    /**
     * Get the symbol for the given token.
     *
     * @param string $token
     *
     * @return SymbolInterface|null
     */
    public function getSymbol(string $token): ?SymbolInterface
    {
        return $this->symbols->getSymbol($token);
    }

    /**
     * Create a sub-scope relative to the current scope and make it the current
     * scope.
     *
     * @return ScopeInterface
     */
    public function newScope(): ScopeInterface
    {
        return $this->scope = $this->scope->new();
    }

    /**
     * Pop the scope and make the parent the current scope.
     *
     * @return ScopeInterface
     */
    public function popScope(): ScopeInterface
    {
        return $this->scope = $this->scope->parent();
    }
}
