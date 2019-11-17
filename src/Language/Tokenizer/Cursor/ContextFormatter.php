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

use SplFileInfo;
use SplFileObject;

class ContextFormatter
{
    /** @var int */
    private $before;

    /** @var int */
    private $after;

    /**
     * Constructor.
     *
     * @param int $before
     * @param int $after
     */
    public function __construct(int $before = 1, int $after = 1)
    {
        $this->before = $before;
        $this->after  = $after;
    }

    /**
     * Format context for the given file, using the given cursor.
     *
     * @param SplFileInfo     $file
     * @param CursorInterface $cursor
     *
     * @return string
     */
    public function __invoke(SplFileInfo $file, CursorInterface $cursor): string
    {
        $output  = [];
        $context = [];
        $buffer  = $file instanceof SplFileObject
            ? $file
            : $file->openFile('r');

        // Treat newly opened and previously opened files the same.
        $buffer->rewind();

        foreach ($buffer as $row => $text) {
            // These lines are before the target context.
            if ($row < $cursor->getRow() - $this->before) {
                continue;
            }

            // Fill the context.
            $context[$row] = rtrim($text);

            // These lines are after the target context.
            if ($row >= $cursor->getRow() + $this->after) {
                break;
            }
        }

        $highestLine    = $cursor->getRow() + $this->after + 1;
        $lineColumnSize = strlen((string)$highestLine) + 2;

        foreach ($context as $row => $line) {
            // Prefix the line number.
            $prefix = str_pad(
                sprintf('%d: ', $row + 1),
                $lineColumnSize,
                ' '
            );

            // Draw a line pointing to the column of the cursor.
            if ($row === $cursor->getRow()) {
                $output[] = str_repeat(
                    '─',
                    $cursor->getColumn() + strlen($prefix)
                ) . '╮';
            }

            $output[] = $prefix . $line;
        }

        return implode(PHP_EOL, $output);
    }
}
