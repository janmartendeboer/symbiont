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
use Symbiont\Language\Parser\Symbol\SymbolTable;
use Symbiont\Language\Parser\Symbol\SymbolTableInterface;
use Symbiont\Language\Parser\SyntaxException;

class Scope implements ScopeInterface
{
    private SymbolTableInterface $reserved;

    private ?ScopeInterface $parent;

    /**
     * Constructor.
     *
     * @param SymbolTableInterface|null $reserved
     * @param ScopeInterface|null       $parent
     */
    public function __construct(
        SymbolTableInterface $reserved = null,
        ScopeInterface $parent = null
    ) {
        $this->reserved = $reserved ?? new SymbolTable();
        $this->parent   = $parent;
    }

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
    public function reserve(NodeInterface $node, SymbolInterface $symbol): void
    {
        $name     = $node->getToken()->getValue();
        $existing = $this->reserved->getSymbol($name);

        if ($existing !== null) {
            throw $symbol->createException(
                $node->getToken(),
                sprintf(
                    'Name "%s" already reserved.',
                    $name
                ),
                $node
            );
        }

        $this->reserved->register($name, $symbol);
    }
}
