<?php

declare(strict_types=1);

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Ast\Node;

interface TernaryNodeInterface extends BinaryNodeInterface
{
    /**
     * Get the third sub-node.
     *
     * @return NodeInterface
     */
    public function getThird(): NodeInterface;
}
