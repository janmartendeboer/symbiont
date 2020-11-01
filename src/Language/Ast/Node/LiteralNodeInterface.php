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

interface LiteralNodeInterface extends NodeInterface
{
    /**
     * Get the value of the literal node.
     *
     * @return string
     */
    public function getValue(): string;
}
