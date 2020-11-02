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

use SplFileObject;

class TokenContextFormatter implements TokenContextFormatterInterface
{
    private int $before;

    private int $after;

    /**
     * Constructor.
     *
     * @param int      $before
     * @param int|null $after  Inherits from $before when omitted.
     */
    public function __construct(int $before = 1, int $after = null)
    {
        $this->before = $before;
        $this->after  = $after ?? $before;
    }

    /**
     * Format context for the given file, using the given cursor.
     *
     * @param TokenContextInterface $context
     *
     * @return string
     */
    public function __invoke(TokenContextInterface $context): string
    {
        $output  = [];
        $start   = $context->getStart();
        $end     = $context->getEnd();
        $file    = $context->getFile();
        $context = [];
        $buffer  = $file instanceof SplFileObject
            ? $file
            : $file->openFile('r');

        // Treat newly opened and previously opened files the same.
        $buffer->rewind();

        foreach ($buffer as $row => $text) {
            if (!is_string($text)) {
                continue;
            }

            $lineNumber = $row + 1;

            // These lines are before the target context.
            if ($lineNumber < $start->getLine() - $this->before) {
                continue;
            }

            // Fill the context.
            $context[$lineNumber] = rtrim($text);

            // These lines are after the target context.
            if ($lineNumber >= $end->getLine() + $this->after) {
                break;
            }
        }

        $highestLine    = $end->getLine() + $this->after + 1;
        $lineColumnSize = strlen((string)$highestLine) + 2;

        foreach ($context as $lineNumber => $line) {
            // Prefix the line number.
            $prefix = str_pad(
                sprintf('%d: ', $lineNumber),
                $lineColumnSize,
                ' '
            );

            // Draw a line pointing to the column of the start cursor.
            if ($lineNumber === $start->getLine()) {
                $output[] = str_repeat(
                    '─',
                    $start->getColumn() + strlen($prefix) - 1
                ) . '╮';
            }

            $output[] = $prefix . $line;

            // Draw a line pointing to the column of the end cursor.
            if ($lineNumber === $end->getLine()) {
                $output[] = str_repeat(
                    '─',
                    $end->getColumn() + strlen($prefix) - 2
                ) . '┘';
            }
        }

        return implode(PHP_EOL, $output);
    }
}
