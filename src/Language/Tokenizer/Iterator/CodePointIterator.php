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
    private SplFileInfo $file;

    private ?SplFileObject $rows = null;

    private ?Iterator $row = null;

    private string $locale;

    /**
     * Constructor.
     *
     * @param SplFileInfo $file
     * @param string      $locale
     */
    public function __construct(SplFileInfo $file, string $locale = 'c')
    {
        $this->file   = $file;
        $this->locale = $locale;
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
            $buffer = IntlBreakIterator::createCharacterInstance($this->locale);
            $buffer->setText($this->rows->current());

            /**
             * @var Iterator $row
             * @noinspection PhpVoidFunctionResultUsedInspection
             */
            $row = $buffer->getPartsIterator();
            $row->rewind();
        }

        return $row;
    }

    /**
     * Return the key of the current element.
     *
     * @return string
     */
    public function key(): string
    {
        return sprintf(
            '%d:%d',
            $this->getLine(),
            $this->getColumn()
        );
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

        if ($this->file instanceof SplFileObject) {
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
    public function getLine(): int
    {
        return $this->rows->key() + 1;
    }

    /**
     * Get the current column.
     *
     * @return int
     */
    public function getColumn(): int
    {
        $offset = $this->row->key();

        return $offset === null ? 0 : $offset + 1;
    }
}
