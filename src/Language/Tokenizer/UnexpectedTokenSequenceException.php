<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer;

use DomainException;
use Symbiont\Language\Tokenizer\Cursor\CursorInterface;
use Throwable;

class UnexpectedTokenSequenceException extends DomainException
{
    /** @var string */
    private $sequence;

    /** @var CursorInterface|null */
    private $start;

    /** @var CursorInterface|null */
    private $end;

    /**
     * Constructor.
     *
     * @param string               $sequence
     * @param CursorInterface|null $start
     * @param CursorInterface|null $end
     * @param int                  $code
     * @param Throwable|null       $previous
     */
    public function __construct(
        string $sequence,
        CursorInterface $start = null,
        CursorInterface $end = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->sequence = $sequence;
        $this->start    = $start;
        $this->end      = $end ?? $start;

        $message = sprintf(
            'Unexpected token sequence %s',
            json_encode($sequence)
        );

        if ($start !== null) {
            $message .= sprintf(
                ' at line %d column %d',
                $start->getRow() + 1,
                $start->getColumn() + 1
            );
        }

        parent::__construct($message . '.', $code, $previous);
    }

    /**
     * Get the sequence.
     *
     * @return string
     */
    public function getSequence(): string
    {
        return $this->sequence;
    }

    /**
     * Get the current cursor for the start of the exception.
     *
     * @return CursorInterface|null
     */
    public function getStart(): ?CursorInterface
    {
        return $this->start;
    }

    /**
     * Get the current cursor for the end of the exception.
     *
     * @return CursorInterface|null
     */
    public function getEnd(): ?CursorInterface
    {
        return $this->end;
    }
}
