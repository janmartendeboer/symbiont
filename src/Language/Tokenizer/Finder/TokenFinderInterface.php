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
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenSequenceException;

interface TokenFinderInterface
{
    /**
     * Find the next token.
     *
     * @param Iterator<mixed, string> $characters
     *
     * @return TokenInterface
     *
     * @throws UnexpectedTokenSequenceException When no strategy matches the sequence.
     */
    public function __invoke(Iterator $characters): TokenInterface;
}
