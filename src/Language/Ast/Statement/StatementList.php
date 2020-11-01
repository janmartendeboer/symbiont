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
