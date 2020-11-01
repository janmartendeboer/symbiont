<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Ast\Node;

abstract class AbstractNode implements NodeInterface
{
    protected ?Arity $arity = null;

    /**
     * Get the arity of the current node.
     *
     * @return Arity
     */
    public function getArity(): Arity
    {
        if ($this->arity === null) {
            $this->arity = $this->createArity();
        }

        return $this->arity;
    }

    /**
     * Create the arity that matches the current node type.
     *
     * @return Arity
     */
    abstract protected function createArity(): Arity;

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'arity' => $this->getArity(),
            'token' => $this->getToken()
        ];
    }
}
