<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer\Finder;

use Iterator;
use Symbiont\Language\Tokenizer\Strategy\TokenStrategyInterface;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenSequenceException;

class TokenFinder implements TokenFinderInterface
{
    /** @var TokenStrategyInterface[] */
    private iterable $strategies;

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
     * Find the next token.
     *
     * @param Iterator<mixed, string> $characters
     *
     * @return TokenInterface
     *
     * @throws UnexpectedTokenSequenceException When no strategy matches the sequence.
     */
    public function __invoke(Iterator $characters): TokenInterface
    {
        $sequence   = $characters->current();
        $strategies = $this->strategies;
        $previous   = [];

        while ($strategies !== [] && $characters->valid()) {
            foreach ($strategies as $index => $strategy) {
                $resolution = $strategy->validate($sequence);

                if ($resolution === TokenStrategyInterface::RESOLUTION_RESOLVED) {
                    $characters->next();
                    return $strategy($sequence);
                }

                if ($resolution === TokenStrategyInterface::RESOLUTION_REJECTED) {
                    unset($strategies[$index]);
                }
            }

            if (count($strategies) === 0) {
                break;
            }

            $characters->next();
            $sequence .= $characters->current();
            $previous  = $strategies;
        }

        $sequence = substr($sequence, 0, -1);
        $token    = array_reduce(
            $previous,
            function (
                ?TokenInterface $carry,
                TokenStrategyInterface $strategy
            ) use ($sequence): ?TokenInterface {
                try {
                    return $carry ?? $strategy($sequence);
                } catch (UnexpectedTokenSequenceException $exception) {
                    return $carry;
                }
            }
        );

        if ($token === null) {
            throw new UnexpectedTokenSequenceException($sequence);
        }

        return $token;
    }
}
