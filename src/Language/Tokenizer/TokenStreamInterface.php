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

interface TokenStreamInterface
{
    /**
     * Produce the next token, ensuring the current token matches the requested
     * token identifier.
     *
     * When the requested token identifier is null, any token may be produced.
     *
     * @param string|null $token
     *
     * @return TokenInterface|null
     *
     * @throws UnexpectedTokenException When the produced token does not match.
     * @throws UnexpectedEndOfStreamException When the token stream has ended.
     */
    public function advance(string $token = null): ?TokenInterface;

    /**
     * Get the current token, if the token stream has started.
     *
     * @return TokenInterface|null
     */
    public function current(): ?TokenInterface;
}
