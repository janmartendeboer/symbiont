<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Parser\Symbol;

use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\SyntaxException;
use Symbiont\Language\Tokenizer\TokenInterface;

trait SyntaxExceptionTrait
{
    /**
     * Create an exception for the current symbol, using the given message and
     * optional node for additional context.
     *
     * @param TokenInterface     $token
     * @param string             $message
     * @param NodeInterface|null $node
     *
     * @return SyntaxException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function createException(
        TokenInterface $token,
        string $message,
        NodeInterface $node = null
    ): SyntaxException {
        return new SyntaxException(
            $token,
            $this instanceof SymbolInterface
                ? $this
                : new Symbol($token->getValue()),
            $message
        );
    }
}
