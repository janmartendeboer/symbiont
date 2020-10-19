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

abstract class AbstractBinaryNode extends AbstractUnaryNode implements
    BinaryNodeInterface
{
    /** @var mixed */
    protected $second;

    /**
     * Create the arity that matches the current node type.
     *
     * @return Arity
     */
    protected function createArity(): Arity
    {
        return Arity::binary();
    }

    /**
     * Get the second sub-node.
     *
     * @return mixed
     */
    public function getSecond()
    {
        return $this->second;
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
                'second' => $this->getSecond()
            ]
        );
    }
}
