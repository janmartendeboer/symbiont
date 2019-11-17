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
    private $cursor;

    /**
     * Constructor.
     *
     * @param string               $sequence
     * @param CursorInterface|null $cursor
     * @param int                  $code
     * @param Throwable|null       $previous
     */
    public function __construct(
        string $sequence,
        CursorInterface $cursor = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->sequence = $sequence;
        $this->cursor   = $cursor;

        $message = sprintf(
            'Unexpected token sequence %s',
            json_encode($sequence)
        );

        if ($cursor !== null) {
            $message .= sprintf(
                ' at line %d column %d',
                $cursor->getRow() + 1,
                $cursor->getColumn() + 1
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
     * Get the current cursor for the origin of the exception.
     *
     * @return CursorInterface|null
     */
    public function getCursor(): ?CursorInterface
    {
        return $this->cursor;
    }
}
