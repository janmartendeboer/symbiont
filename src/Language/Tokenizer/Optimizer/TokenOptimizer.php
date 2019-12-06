<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer\Optimizer;

use Generator;
use Symbiont\Language\Tokenizer\TokenInterface;

class TokenOptimizer implements TokenOptimizerInterface
{
    private array $blacklist;

    /**
     * Constructor.
     *
     * @param string ...$blacklist
     */
    public function __construct(string ...$blacklist)
    {
        $this->blacklist = $blacklist;
    }

    /**
     * Optimize the given tokens, yielding only the tokens that are necessary.
     *
     * @param iterable|TokenInterface[] $tokens
     *
     * @return Generator|TokenInterface[]
     */
    public function __invoke(iterable $tokens): Generator
    {
        foreach ($tokens as $token) {
            if (!$token instanceof TokenInterface
                || in_array($token->getName(), $this->blacklist, true)
            ) {
                continue;
            }

            yield $token;
        }
    }
}
