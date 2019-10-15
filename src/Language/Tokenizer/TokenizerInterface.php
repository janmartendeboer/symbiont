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

interface TokenizerInterface
{
    /**
     * Tokenize the given subject into a list of tokens.
     *
     * @param string $subject
     *
     * @return Generator|TokenInterface[]
     *
     * @throws UnexpectedTokenSequenceException When a token could not be resolved.
     */
    public function __invoke(string $subject): Generator;
}
