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

interface CursorInterface
{
    /**
     * Get the current row.
     *
     * @return int
     */
    public function getLine(): int;

    /**
     * Get the current column.
     *
     * @return int
     */
    public function getColumn(): int;
}
