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
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Parser\SyntaxException;
use Symbiont\Language\Tokenizer\TokenInterface;

// phpcs:disable Squiz.Commenting.FunctionComment.InvalidNoReturn
trait NoLedTrait
{
    /**
     * Invoke the symbol as a left denoted operator.
     *
     * @param ParseContextInterface $context
     * @param TokenInterface        $subject
     * @param NodeInterface         $left
     *
     * @return NodeInterface
     *
     * @throws SyntaxException Always.
     */
    public function led(
        ParseContextInterface $context,
        TokenInterface $subject,
        NodeInterface $left
    ): NodeInterface {
        throw $this->createException(
            $subject,
            'Cannot be used as left denoted operator.',
            $left
        );
    }

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
    abstract public function createException(
        TokenInterface $token,
        string $message,
        NodeInterface $node = null
    ): SyntaxException;
}
