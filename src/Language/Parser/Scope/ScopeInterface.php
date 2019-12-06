<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Parser\Scope;

use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\Symbol\SymbolInterface;
use Symbiont\Language\Parser\SyntaxException;

interface ScopeInterface
{
    /**
     * Reserve the given node in the current scope.
     *
     * @param NodeInterface   $node
     * @param SymbolInterface $symbol
     *
     * @return void
     *
     * @throws SyntaxException When the node cannot be reserved.
     */
    public function reserve(NodeInterface $node, SymbolInterface $symbol): void;
}