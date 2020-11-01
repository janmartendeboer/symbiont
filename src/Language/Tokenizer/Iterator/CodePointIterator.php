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

namespace Symbiont\Language\Tokenizer\Iterator;

use IntlBreakIterator;
use Iterator;
use SplFileInfo;
use SplFileObject;
use Symbiont\Language\Tokenizer\Cursor\CursorInterface;

/**
 * @implements Iterator<string, string>
 */
class CodePointIterator implements CursorInterface, Iterator
{
    private SplFileInfo $file;

    private ?SplFileObject $rows = null;

    /** @var Iterator<int, string>|null */
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
        return $this->row !== null && $this->row->valid()
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

                if ($this->rows !== null) {
                    $this->rows->next();
                }
            }
        }

        if ($this->row === null && $this->rows !== null) {
            $this->row = $this->createRowIterator($this->rows);
        }
    }

    /**
     * Create an iterator for the current row, or null if the current row is not
     * valid.
     *
     * @param Iterator<int, string> $rows
     *
     * @return Iterator<int, string>|null
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function createRowIterator(Iterator $rows): ?Iterator
    {
        $row = null;

        if ($rows->valid()) {
            $buffer = IntlBreakIterator::createCharacterInstance($this->locale);
            $buffer->setText((string)$rows->current());

            /**
             * @var Iterator<int, string> $row
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
        return (
            $this->row !== null
            && $this->row->valid()
            && $this->rows !== null
            && $this->rows->valid()
        );
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
        return $this->rows instanceof Iterator
            ? $this->rows->key() + 1
            : 0;
    }

    /**
     * Get the current column.
     *
     * @return int
     */
    public function getColumn(): int
    {
        $offset = $this->row instanceof Iterator
            ? $this->row->key()
            : null;

        return $offset === null ? 0 : $offset + 1;
    }
}
