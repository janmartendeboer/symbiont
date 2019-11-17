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
     * Find the next token.
     *
     * @param Iterator $characters
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

        while ($strategies !== []) {
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

        if (count($previous) === 0) {
            throw new UnexpectedTokenSequenceException($sequence);
        }

        $strategy = reset($previous);

        return $strategy->__invoke(substr($sequence, 0, -1));
    }
}
