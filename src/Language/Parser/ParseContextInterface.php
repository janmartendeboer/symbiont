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
use Symbiont\Language\Parser\Scope\ScopeInterface;
use Symbiont\Language\Parser\Symbol\SymbolHolderInterface;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\TokenStreamInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenException;

interface ParseContextInterface extends
    TokenStreamInterface,
    SymbolHolderInterface
{
    /**
     * Get the current scope.
     *
     * @return ScopeInterface
     */
    public function getScope(): ScopeInterface;

    /**
     * Create a sub-scope relative to the current scope and make it the current
     * scope.
     *
     * @return ScopeInterface
     */
    public function newScope(): ScopeInterface;

    /**
     * Pop the scope and make the parent the current scope.
     *
     * @return ScopeInterface
     */
    public function popScope(): ScopeInterface;

    /**
     * Parse the current expression with the given binding power.
     *
     * @param int $bindingPower
     *
     * @return NodeInterface
     */
    public function parseExpression(int $bindingPower): NodeInterface;

    /**
     * Parse the current statement.
     *
     * @return NodeInterface|NodeInterface[]|null
     */
    public function parseStatement();

    /**
     * Parse the current list of statements.
     *
     * @return iterable|NodeInterface[]
     */
    public function parseStatements(): iterable;

    /**
     * Parse the current code block.
     *
     * @return NodeInterface
     */
    public function parseBlock(): NodeInterface;

    /**
     * Get the current token, if the token stream has started.
     *
     * When a specific token is provided, the produced token must match.
     *
     * @param string|null $token
     *
     * @return TokenInterface|null
     *
     * @throws UnexpectedTokenException When the produced token does not match.
     */
    public function current(string $token = null): ?TokenInterface;
}
