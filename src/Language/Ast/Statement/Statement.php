<?php

declare(strict_types=1);

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Ast\Statement;

use IteratorIterator;
use Symbiont\Language\Ast\Node\NodeInterface;

class Statement extends IteratorIterator implements StatementInterface
{
    /**
     * Get the current node.
     *
     * @return NodeInterface|null
     */
    public function current(): ?NodeInterface
    {
        return parent::current();
    }

    /**
     * Advance and get the next node.
     *
     * @return NodeInterface|null
     */
    public function advance(): ?NodeInterface
    {
        if ($this->valid()) {
            $this->next();
        }

        return $this->current();
    }

    /**
     * Return a list of nodes within the current statement.
     *
     * @return array<mixed, mixed>
     */
    public function jsonSerialize(): array
    {
        return iterator_to_array($this);
    }
}
