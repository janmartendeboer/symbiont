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

class NumberStrategy implements TokenStrategyInterface
{
    private const SEARCH_PATTERN = <<<'REGEXP'
    /
        ^(?:
            # Hex and Binary numbers.
            (?:0
                (
                    ([bB]?[01]*)
                    | ([xX]?[0-9a-fA-F]*)
                )?
            )
    
            | (?:
                # Positive or negative numbers
                [-+]?
                (?:
                    # Integers and octal numbers.
                    (?:[0-9]*)
    
                    |
    
                    # Decimal numbers.
                    (?:[0-9]*\.[0-9]*)
                )
                (?:[eE]?[-+]?\d*)?
            )
        )$
    /xD
    REGEXP;

    // This relies on the PCRE2 implementation introduced in PHP 7.3.
    // The definitions are slightly optimized to prevent recursion within named
    // patterns.
    // This is not a performance decision, but necessity, because of the PCRE
    // JIT stack size which is hard-coded within PHP.
    private const GRAMMAR = <<<'REGEXP'
    /
        (?(DEFINE)
            (?<digit>               [0-9]                                      )
            (?<nonzero_digit>       [1-9]                                      )
            (?<binary_digit>        [01]                                       )
            (?<hexadecimal_digit>   [0-9a-fA-F]                                )
            (?<octal_digit>         [0-7]                                      )
            (?<digit_sequence>      (?&digit)+                                 )
            (?<sign>                [+-]                                       )
            (?<exponent_part>       [eE] (?&sign)? (?&decimal_literal)         )
            (?<binary_prefix>       0[bB]                                      )
            (?<hexadecimal_prefix>  0[xX]                                      )
            
            (?<decimal_literal>
                (?&nonzero_digit) (?&digit)*
            )

            (?<octal_literal>
                0 (?&octal_digit)*
            )
            
            (?<hexadecimal_literal>
                (?&hexadecimal_prefix) (?&hexadecimal_digit)+
            )
            
            (?<binary_literal>
                (?&binary_prefix) (?&binary_digit)+
            )

            (?<integer_literal>
                (?&decimal_literal)
                | (?&hexadecimal_literal)
                | (?&binary_literal)
                | (?&octal_literal)
            )
            
            (?<floating_literal>
                (?&fractional_literal)
                | (?&fractional_literal) (?&exponent_part)
                | (?&decimal_literal)    (?&exponent_part)
            )
            
            (?<fractional_literal>
                (?&decimal_literal) \. (?&digit_sequence)
                | \. (?&digit_sequence)
                | (?&decimal_literal) \.
            )
            
            (?<number_literal>
                (?&integer_literal)
                | (?&floating_literal)
            )
        )
        
        ^(?&sign)? (?&number_literal)$
    /x
    REGEXP;

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
        return preg_match(static::SEARCH_PATTERN, $sequence) === 1
            ? static::RESOLUTION_CANDIDATE
            : static::RESOLUTION_REJECTED;
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
        // Full numbers must match the grammar.
        if (!preg_match(static::GRAMMAR, $value)) {
            throw new UnexpectedTokenSequenceException($value);
        }

        return new Token('T_NUMBER', $value);
    }
}
