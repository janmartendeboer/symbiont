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
    /** @var TokenFinderInterface */
    private $finder;

    /**
     * Constructor.
     *
     * @param TokenFinderInterface $finder
     */
    public function __construct(TokenFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Tokenize the given file into a list of tokens.
     *
     * @param SplFileInfo $file
     *
     * @return Generator|TokenInterface[]
     *
     * @throws UnexpectedTokenSequenceException When a token could not be resolved.
     */
    public function __invoke(SplFileInfo $file): Generator
    {
        $numTokens = 0;

        $characters = new CodePointIterator($file);
        $characters->rewind();

        while ($characters->valid()) {
            // Keep track of the current offset of code.
            $start = new ImmutableCursor($characters);

            try {
                $token = $this->finder->__invoke($characters);
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

            $numTokens++;
        }

        return $numTokens;
    }
}
