<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symbiont\Language\Ast\Node;

use Symbiont\Language\Ast\Node\Arity\Arity;

abstract class AbstractUnaryNode extends AbstractNode implements
    UnaryNodeInterface
{
    /** @var mixed */
    protected $first;

    /**
     * Create the arity that matches the current node type.
     *
     * @return Arity
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function createArity(): Arity
    {
        return Arity::unary();
    }

    /**
     * Get the first sub-node.
     *
     * @return mixed
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<mixed, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'first' => $this->getFirst()
            ]
        );
    }
}
