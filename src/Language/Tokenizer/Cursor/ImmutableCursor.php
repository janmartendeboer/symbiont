<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer\Cursor;

class ImmutableCursor implements CursorInterface
{
    /** @var int */
    private $row;

    /** @var int */
    private $column;

    /**
     * Constructor.
     *
     * @param CursorInterface $cursor
     */
    public function __construct(CursorInterface $cursor)
    {
        $this->row    = $cursor->getRow();
        $this->column = $cursor->getColumn();
    }

    /**
     * Get the current row.
     *
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * Get the current column.
     *
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
