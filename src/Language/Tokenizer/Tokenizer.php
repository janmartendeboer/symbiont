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

use Generator;
use IntlBreakIterator;
use Iterator;
use Symbiont\Language\Tokenizer\Strategy\TokenStrategyInterface;

class Tokenizer implements TokenizerInterface
{
    /** @var TokenStrategyInterface[] */
    private $strategies;

    /**
     * Constructor.
     *
     * @param TokenStrategyInterface ...$strategies
     */
    public function __construct(TokenStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * Tokenize the given subject into a list of tokens.
     *
     * @param string $subject
     *
     * @return Generator|TokenInterface[]
     *
     * @throws UnexpectedTokenSequenceException When a token could not be resolved.
     */
    public function __invoke(string $subject): Generator
    {
        $numTokens = 0;

        /** @var IntlBreakIterator $buffer */
        $buffer = IntlBreakIterator::createCharacterInstance('en_US');
        $buffer->setText($subject);

        /** @var Iterator $characters */
        $characters = $buffer->getPartsIterator();
        $characters->rewind();

        while ($characters->valid()) {
            $character = $characters->current();

            try {
                $strategy = $this->findStrategy($character);
            } catch (UnexpectedTokenSequenceException $exception) {
                throw new UnexpectedTokenSequenceException(
                    $character,
                    $characters->key(),
                    0,
                    $exception
                );
            }

            $characters->next();

            yield $strategy(
                $this->read($strategy, $character, $characters)
            );

            $numTokens++;
        }

        return $numTokens;
    }

    /**
     * Find a token strategy matching the given character.
     *
     * @param string $character
     *
     * @return TokenStrategyInterface
     *
     * @throws UnexpectedTokenSequenceException When no strategy supports the
     *   character.
     */
    private function findStrategy(string $character): TokenStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($character)) {
                return $strategy;
            }
        }

        throw new UnexpectedTokenSequenceException($character);
    }

    /**
     * Read characters so long the strategy accepts it.
     *
     * @param TokenStrategyInterface $strategy
     * @param string                 $value
     * @param Iterator               $characters
     *
     * @return string
     *
     * @throws UnexpectedTokenSequenceException When the strategy mis-matches.
     */
    private function read(
        TokenStrategyInterface $strategy,
        string $value,
        Iterator $characters
    ): string {
        $previousResolution = $strategy->validate($value);

        while ($characters->valid()) {
            $character  = $characters->current();
            $resolution = $strategy->validate($value . $character);

            if ($resolution === TokenStrategyInterface::RESOLUTION_REJECTED) {
                if ($previousResolution === $resolution) {
                    throw new UnexpectedTokenSequenceException(
                        $value . $character
                    );
                }

                break;
            }

            $previousResolution = $resolution;
            $value             .= $character;

            $characters->next();

            if ($resolution === TokenStrategyInterface::RESOLUTION_RESOLVED) {
                break;
            }
        }

        return $value;
    }
}
