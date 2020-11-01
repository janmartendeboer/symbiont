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

use Symbiont\Language\Parser\Symbol\SymbolTableInterface;
use Symbiont\Language\Tokenizer\Token;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenSequenceException;

class SymbolStrategy implements TokenStrategyInterface
{
    /** @var string[] */
    private ?array $sequences = null;

    private SymbolTableInterface $symbolTable;

    /**
     * Constructor.
     *
     * @param SymbolTableInterface $symbolTable
     */
    public function __construct(SymbolTableInterface $symbolTable)
    {
        $this->symbolTable = $symbolTable;
    }

    /**
     * Get the token sequences.
     *
     * @return array
     */
    private function getSequences(): array
    {
        if ($this->sequences === null) {
            $this->sequences = $this->symbolTable->getTokenSequences();
        }

        return $this->sequences;
    }

    /**
     * Get the available operators up to the length amount of bytes.
     *
     * @param int $length
     *
     * @return array
     */
    private function getOptions(int $length): array
    {
        return array_reduce(
            $this->getSequences(),
            function (array $carry, string $assignment) use ($length) {
                if (strlen($assignment) >= $length) {
                    $subject         = substr($assignment, 0, $length);
                    $carry[$subject] = $subject;
                }

                return $carry;
            },
            []
        );
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
        return in_array($sequence, $this->getOptions(strlen($sequence)), true)
            ? self::RESOLUTION_CANDIDATE
            : self::RESOLUTION_REJECTED;
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
        $sequences = $this->getSequences();
        $token     = array_search($value, $sequences, true);

        if (!is_string($token)) {
            throw new UnexpectedTokenSequenceException($value);
        }

        return new Token($token, $value);
    }
}
