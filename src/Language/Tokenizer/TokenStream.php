<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symbiont\Language\Tokenizer;

use Iterator;
use IteratorIterator;

class TokenStream extends IteratorIterator implements TokenStreamInterface
{
    /**
     * Constructor.
     *
     * @param Iterator<TokenInterface> $tokens
     */
    public function __construct(Iterator $tokens)
    {
        parent::__construct($tokens);
        $this->rewind();
    }

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
    public function advance(string $token = null): ?TokenInterface
    {
        if (!$this->valid()) {
            throw new UnexpectedEndOfStreamException($token);
        }

        $current = $this->current();

        if (
            $token !== null
            && $current !== null
            && $current->getName() !== $token
        ) {
            throw new UnexpectedTokenException($token, $current);
        }

        $this->next();

        return $this->current();
    }

    /**
     * Get the current token, if the token stream has started.
     *
     * @return TokenInterface|null
     */
    public function current(): ?TokenInterface
    {
        return parent::current();
    }
}
