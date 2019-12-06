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

interface OperatorNodeInterface extends NodeInterface
{
    /**
     * Get the operator token sequence.
     *
     * @return string
     */
    public function getOperator(): string;
}
