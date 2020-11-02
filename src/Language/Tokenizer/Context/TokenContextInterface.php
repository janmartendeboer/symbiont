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

namespace Symbiont\Language\Tokenizer\Context;

use SplFileInfo;
use Symbiont\Language\Tokenizer\Cursor\CursorInterface;

interface TokenContextInterface
{
    /**
     * Get the file from which the token originates.
     *
     * @return SplFileInfo
     */
    public function getFile(): SplFileInfo;

    /**
     * Get the start of the context.
     *
     * @return CursorInterface
     */
    public function getStart(): CursorInterface;

    /**
     * Get the end of the context.
     *
     * @return CursorInterface
     */
    public function getEnd(): CursorInterface;
}
