<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Tokenizer\Strategy;

use Symbiont\Language\Tokenizer\Token;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenSequenceException;

class WhitespaceStrategy implements TokenStrategyInterface
{
    /**
     * Whether the strategy supports the given character as start of a token.
     *
     * @param string $character
     *
     * @return bool
     */
    public function supports(string $character): bool
    {
        return preg_match('/^\s$/', $character) > 0;
    }

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
            preg_match('/^\s+$/', $sequence) > 0
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
        return new Token('T_WHITESPACE', $value);
    }
}
