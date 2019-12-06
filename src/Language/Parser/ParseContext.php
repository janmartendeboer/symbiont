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

use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\Scope\Scope;
use Symbiont\Language\Parser\Scope\ScopeInterface;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\TokenStreamInterface;
use Symbiont\Language\Tokenizer\UnexpectedEndOfStreamException;
use Symbiont\Language\Tokenizer\UnexpectedTokenException;

class ParseContext implements ParseContextInterface
{
    private ScopeInterface $scope;

    private ParserInterface $parser;

    private TokenStreamInterface $tokens;

    /**
     * Constructor.
     *
     * @param ParserInterface      $parser
     * @param TokenStreamInterface $tokens
     * @param ScopeInterface|null  $scope
     */
    public function __construct(
        ParserInterface $parser,
        TokenStreamInterface $tokens,
        ScopeInterface $scope = null
    ) {
        $this->parser = $parser;
        $this->tokens = $tokens;
        $this->scope  = $scope ?? new Scope();
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
     * @return TokenInterface|null
     */
    public function current(): ?TokenInterface
    {
        return $this->tokens->current();
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
     * @return NodeInterface|NodeInterface[]|null
     */
    public function parseStatement()
    {
        return $this->parser->parseStatement($this);
    }

    /**
     * Parse the current list of statements.
     *
     * @return iterable|NodeInterface[]
     */
    public function parseStatements(): iterable
    {
        return $this->parser->parseStatements($this);
    }

    /**
     * Parse the current code block.
     *
     * @return NodeInterface
     */
    public function parseBlock(): NodeInterface
    {
        return $this->parser->parseBlock($this);
    }
}
