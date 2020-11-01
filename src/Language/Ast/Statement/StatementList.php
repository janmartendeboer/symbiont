<?php

declare(strict_types=1);

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Ast\Statement;

use IteratorIterator;

class StatementList extends IteratorIterator implements StatementListInterface
{
    /**
     * Get the current statement.
     *
     * @return StatementInterface|null
     */
    public function current(): ?StatementInterface
    {
        return parent::current();
    }

    /**
     * Advance to the next statement and return it.
     *
     * @return StatementInterface|null
     */
    public function advance(): ?StatementInterface
    {
        if ($this->valid()) {
            $this->next();
        }

        return $this->current();
    }

    /**
     * Return a list of statements.
     *
     * @return array<mixed, mixed>
     */
    public function jsonSerialize(): array
    {
        return iterator_to_array($this);
    }
}
