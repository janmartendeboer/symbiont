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
use Symbiont\Language\Tokenizer\UnexpectedTokenSequenceException;

class EqualsAssignmentOperatorStrategy implements TokenStrategyInterface
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
        return $character === '=';
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
        switch ($sequence) {
            case '=':
            case '==':
                $resolution = static::RESOLUTION_CANDIDATE;
                break;

            case '===':
                $resolution = static::RESOLUTION_RESOLVED;
                break;

            default:
                $resolution = static::RESOLUTION_REJECTED;
        }

        return $resolution;
    }

    /**
     * Create a token for the given value.
     *
     * @param string $value
     *
     * @return TokenInterface
     *
     * @throws UnexpectedTokenSequenceException When the value is invalid.
     */
    public function __invoke(string $value): TokenInterface
    {
        switch ($value) {
            case '=':
                return new Token('T_ASSIGNMENT');

            case '===':
                return new Token('T_IS_EQUAL');

            default:
                throw new UnexpectedTokenSequenceException($value);
        }
    }
}
