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

namespace Symbiont\Language\Tokenizer;

use LogicException;
use Throwable;

class UnexpectedEndOfStreamException extends LogicException
{
    /**
     * Constructor.
     *
     * @param string|null    $expected
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        ?string $expected,
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = 'Unexpected end of token stream.';

        if ($expected !== null) {
            $message .= sprintf(' Expected %s', $expected);
        }

        parent::__construct($message, $code, $previous);
    }
}
