<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

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
