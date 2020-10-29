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
use SplFileInfo;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\TokenizerInterface;
use Symbiont\Language\Tokenizer\TokenStream;
use Symbiont\Language\Tokenizer\TokenStreamInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenSequenceException;

class TokenOptimizer implements TokenizerInterface
{
    private array $blacklist;

    private TokenizerInterface $tokenizer;

    /**
     * Constructor.
     *
     * @param TokenizerInterface $tokenizer
     * @param string             ...$blacklist
     */
    public function __construct(
        TokenizerInterface $tokenizer,
        string ...$blacklist
    ) {
        $this->tokenizer = $tokenizer;
        $this->blacklist = $blacklist;
    }

    /**
     * Tokenize the given file into a list of tokens.
     *
     * @param SplFileInfo $file
     *
     * @return TokenStreamInterface
     *
     * @throws UnexpectedTokenSequenceException When a token could not be resolved.
     */
    public function __invoke(SplFileInfo $file): TokenStreamInterface
    {
        return new TokenStream(
            (function () use ($file): Generator {
                foreach ($this->tokenizer->__invoke($file) as $token) {
                    if (!$token instanceof TokenInterface
                        || in_array($token->getName(), $this->blacklist, true)
                    ) {
                        continue;
                    }

                    yield $token;
                }
            })()
        );
    }
}
