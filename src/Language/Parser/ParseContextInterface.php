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
use Symbiont\Language\Tokenizer\TokenStreamInterface;

interface ParseContextInterface extends TokenStreamInterface
{
    /**
     * Get the current scope.
     *
     * @return ScopeInterface
     */
    public function getScope(): ScopeInterface;

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
}
