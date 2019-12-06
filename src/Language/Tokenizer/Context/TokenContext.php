<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer\Context;

use SplFileInfo;
use Symbiont\Language\Tokenizer\Cursor\CursorInterface;

class TokenContext implements TokenContextInterface
{
    private SplFileInfo $file;

    private CursorInterface $start;

    private CursorInterface $end;

    /**
     * Constructor.
     *
     * @param SplFileInfo          $file
     * @param CursorInterface      $start
     * @param CursorInterface|null $end
     */
    public function __construct(
        SplFileInfo $file,
        CursorInterface $start,
        CursorInterface $end = null
    ) {
        $this->file  = $file;
        $this->start = $start;
        $this->end   = $end ?? $start;
    }

    /**
     * Get the file from which the token originates.
     *
     * @return SplFileInfo
     */
    public function getFile(): SplFileInfo
    {
        return $this->file;
    }

    /**
     * Get the start of the context.
     *
     * @return CursorInterface
     */
    public function getStart(): CursorInterface
    {
        return $this->start;
    }

    /**
     * Get the end of the context.
     *
     * @return CursorInterface
     */
    public function getEnd(): CursorInterface
    {
        return $this->end;
    }
}
