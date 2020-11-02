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

namespace Symbiont\Language\Parser\Symbol;

use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Parser\SyntaxException;
use Symbiont\Language\Tokenizer\TokenInterface;

interface SymbolInterface
{
    /**
     * Get the token sequence for the current symbol.
     *
     * @return string|null
     */
    public function getSequence(): ?string;

    /**
     * Invoke the symbol as a left denoted operator.
     *
     * @param ParseContextInterface $context
     * @param TokenInterface        $subject
     * @param NodeInterface         $left
     *
     * @return NodeInterface
     */
    public function led(
        ParseContextInterface $context,
        TokenInterface $subject,
        NodeInterface $left
    ): NodeInterface;

    /**
     * Invoke the symbol as a null denoted operator.
     *
     * @param ParseContextInterface $context
     *
     * @return NodeInterface
     */
    public function nud(ParseContextInterface $context): NodeInterface;

    /**
     * Get the binding power of the current symbol.
     *
     * @return int
     */
    public function getBindingPower(): int;

    /**
     * Create an exception for the current symbol, using the given message and
     * optional node for additional context.
     *
     * @param TokenInterface     $token
     * @param string             $message
     * @param NodeInterface|null $node
     *
     * @return SyntaxException
     */
    public function createException(
        TokenInterface $token,
        string $message,
        NodeInterface $node = null
    ): SyntaxException;
}
