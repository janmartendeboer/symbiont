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

class BlockScope implements ScopeInterface
{
    private ?ScopeInterface $parent;

    private SymbolTableInterface $keywords;

    private SymbolTableInterface $variables;

    /**
     * Constructor.
     *
     * @param SymbolTableInterface|null $keywords
     * @param ScopeInterface|null       $parent
     */
    public function __construct(
        SymbolTableInterface $keywords = null,
        ScopeInterface $parent = null
    ) {
        $this->keywords  = $keywords ?? new SymbolTable();
        $this->parent    = $parent;
        $this->variables = new SymbolTable();
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
        $existing = $this->keywords->getSymbol($name);

        if ($existing !== null) {
            throw $symbol->createException(
                $node->getToken(),
                sprintf('Name "%s" already reserved.', $name),
                $node
            );
        }

        $this->keywords->register($name, $symbol);
    }

    /**
     * Define the given node in the current scope.
     *
     * @param NodeInterface   $node
     * @param SymbolInterface $symbol
     *
     * @return void
     *
     * @throws SyntaxException When the node cannot be defined.
     */
    public function define(NodeInterface $node, SymbolInterface $symbol): void
    {
        $name     = $node->getToken()->getValue();
        $reserved = $this->keywords->getSymbol($name);

        if ($reserved !== null) {
            throw $symbol->createException(
                $node->getToken(),
                sprintf('Name "%s" is a reserved word.', $name),
                $node
            );
        }

        $defined = $this->variables->getSymbol($name);

        if ($defined !== null) {
            throw $symbol->createException(
                $node->getToken(),
                sprintf('Name "%s" is already defined.', $name),
                $node
            );
        }

        $this->variables->register($name, $symbol);
    }

    /**
     * Create a scope nested in the current scope.
     *
     * @return ScopeInterface
     */
    public function new(): ScopeInterface
    {
        return new self($this->keywords, $this);
    }

    /**
     * Get the scope parent.
     *
     * @return ScopeInterface|null
     */
    public function parent(): ?ScopeInterface
    {
        return $this->parent;
    }
}
