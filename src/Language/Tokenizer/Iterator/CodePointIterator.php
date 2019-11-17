<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer\Iterator;

use IntlBreakIterator;
use Iterator;
use SplFileInfo;
use SplFileObject;
use Symbiont\Language\Tokenizer\Cursor\CursorInterface;

class CodePointIterator implements Iterator, CursorInterface
{
    /** @var SplFileInfo */
    private $file;

    /** @var SplFileObject|null */
    private $rows;

    /** @var Iterator|null */
    private $row;

    /**
     * Constructor.
     *
     * @param SplFileInfo $file
     */
    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * Get the current character.
     *
     * @return string|null
     */
    public function current(): ?string
    {
        return $this->row
            ? $this->row->current()
            : null;
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next(): void
    {
        // Advance the current row.
        if ($this->row !== null) {
            $this->row->next();

            // If the row has become invalid, unset it and advance to the next
            // row.
            if (!$this->row->valid()) {
                $this->row = null;
                $this->rows->next();
            }
        }

        if ($this->row === null) {
            $this->row = $this->createRowIterator($this->rows);
        }
    }

    /**
     * Create an iterator for the current row, or null if the current row is not
     * valid.
     *
     * @param Iterator $rows
     *
     * @return Iterator|null
     */
    private function createRowIterator(Iterator $rows): ?Iterator
    {
        $row = null;

        if ($rows->valid()) {
            $buffer = IntlBreakIterator::createCharacterInstance('c');
            $buffer->setText($this->rows->current());

            /** @var Iterator $row */
            $row = $buffer->getPartsIterator();
            $row->rewind();
        }

        return $row;
    }

    /**
     * Return the key of the current element.
     *
     * @return int|null
     */
    public function key(): ?int
    {
        return $this->rows->key();
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        // Either the current row exists and is valid, or the rows iterator is
        // valid. They are not valid at the same time.
        return (
            $this->row !== null
            && $this->row->valid()
        ) || $this->rows->valid();
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->rows = null;

        if ($this->file instanceof Iterator) {
            $this->file->rewind();
            $this->rows = $this->file;
        }

        if ($this->rows === null) {
            $this->rows = $this->file->openFile('r');
        }

        $this->row = $this->createRowIterator($this->rows);
    }

    /**
     * Get the current row.
     *
     * @return int
     */
    public function getRow(): int
    {
        return $this->key();
    }

    /**
     * Get the current column.
     *
     * @return int
     */
    public function getColumn(): int
    {
        return $this->row->key();
    }
}
