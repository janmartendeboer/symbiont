<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer\Strategy;

use Symbiont\Language\Tokenizer\Token;
use Symbiont\Language\Tokenizer\TokenInterface;

class VariableStrategy implements TokenStrategyInterface
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
        return $character === '$';
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
            preg_match('/^\$[a-z][a-zA-Z0-9]*$/', $sequence) > 0
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
     */
    public function __invoke(string $value): TokenInterface
    {
        return new Token('T_VARIABLE', $value);
    }
}
