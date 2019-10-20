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
use Throwable;

class UnexpectedTokenSequenceException extends DomainException
{
    /**
     * Constructor.
     *
     * @param string         $sequence
     * @param int|null       $offset
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $sequence,
        int $offset = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = sprintf('Unexpected token sequence "%s"', $sequence);

        if ($offset !== null) {
            $message .= sprintf(' at offset %d', $offset);
        }

        parent::__construct($message, $code, $previous);
    }
}
