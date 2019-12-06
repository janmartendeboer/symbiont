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

abstract class AbstractUnaryNode extends AbstractNode implements
    UnaryNodeInterface
{
    protected string $arity = self::ARITY_UNARY;

    protected NodeInterface $first;

    /**
     * Get the first sub-node.
     *
     * @return NodeInterface
     */
    public function getFirst(): NodeInterface
    {
        return $this->first;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
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
