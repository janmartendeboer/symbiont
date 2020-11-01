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
use SplFileInfo;
use Symbiont\Language\Tokenizer\Context\TokenContext;
use Symbiont\Language\Tokenizer\Cursor\ImmutableCursor;
use Symbiont\Language\Tokenizer\Finder\TokenFinderInterface;
use Symbiont\Language\Tokenizer\Iterator\CodePointIterator;

class StatelessTokenizer implements TokenizerInterface
{
    private ?string $endToken;

    private TokenFinderInterface $finder;

    /**
     * Constructor.
     *
     * @param TokenFinderInterface $finder
     * @param string|null          $endToken
     */
    public function __construct(
        TokenFinderInterface $finder,
        string $endToken = null
    ) {
        $this->finder   = $finder;
        $this->endToken = $endToken;
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
            static::createTokenGenerator(
                $file,
                $this->finder,
                $this->endToken
            )
        );
    }

    /**
     * Create a token generator for the given file, characters,
     *
     * @param SplFileInfo $file
     * @param TokenFinderInterface $finder
     * @param string|null $endToken
     *
     * @return Generator<int, TokenInterface>
     */
    private static function createTokenGenerator(
        SplFileInfo $file,
        TokenFinderInterface $finder,
        ?string $endToken
    ): Generator {
        $characters = new CodePointIterator($file);
        $characters->rewind();

        while ($characters->valid()) {
            // Keep track of the current offset of code.
            $start = new ImmutableCursor($characters);

            try {
                $token = $finder->__invoke($characters);
            } catch (UnexpectedTokenSequenceException $exception) {
                throw new UnexpectedTokenSequenceException(
                    $exception->getSequence(),
                    new TokenContext(
                        $file,
                        $start,
                        new ImmutableCursor($characters)
                    ),
                    0,
                    $exception
                );
            }

            yield $token->withContext(
                new TokenContext(
                    $file,
                    $start,
                    new ImmutableCursor($characters)
                )
            );
        }

        if ($endToken !== null) {
            yield (new Token($endToken))->withContext(
                new TokenContext(
                    $file,
                    new ImmutableCursor($characters)
                )
            );
        }
    }
}
