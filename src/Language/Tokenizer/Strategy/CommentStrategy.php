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

namespace Symbiont\Language\Tokenizer\Strategy;

use Symbiont\Language\Tokenizer\Token;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenSequenceException;

class CommentStrategy implements TokenStrategyInterface
{
    public const TOKEN_NAME = 'T_COMMENT';

    /**
     * Whether the given sequence is a valid (subset of a) value.
     *
     * Return values have the following intent:
     *   self::RESOLUTION_CANDIDATE: The sequence is valid, but may still grow larger
     *   self::RESOLUTION_RESOLVED:  The sequence is valid and completely resolved
     *   self::RESOLUTION_REJECTED:  The sequence is rejected
     *
     * @param string $sequence
     *
     * @return bool|null
     */
    public function validate(string $sequence): ?bool
    {
        return (
            preg_match('/^#[^\n\r]*$/', $sequence) === 1
                ? static::RESOLUTION_CANDIDATE
                : static::RESOLUTION_REJECTED
        );
    }

    /**
     * Create a token for the given value.
     *
     * @param string $value
     *
     * @return TokenInterface
     *
     * @throws UnexpectedTokenSequenceException When the value is not valid.
     */
    public function __invoke(string $value): TokenInterface
    {
        return new Token('T_COMMENT', $value);
    }
}
