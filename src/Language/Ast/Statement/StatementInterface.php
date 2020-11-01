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

namespace Symbiont\Language\Ast\Statement;

use Iterator;
use JsonSerializable;
use Symbiont\Language\Ast\Node\NodeInterface;

interface StatementInterface extends Iterator, JsonSerializable
{
    /**
     * Get the current node.
     *
     * @return NodeInterface|null
     */
    public function current(): ?NodeInterface;

    /**
     * Advance and get the next node.
     *
     * @return NodeInterface|null
     */
    public function advance(): ?NodeInterface;
}
