<?php

declare(strict_types=1);

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Parser\Symbol;

use Symbiont\Language\Ast\Node\AssignmentNode;
use Symbiont\Language\Ast\Node\LiteralNodeInterface;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Parser\SyntaxException;
use Symbiont\Language\Tokenizer\TokenInterface;

class Assignment implements SymbolInterface
{
    use BindingPowerTrait;
    use SequenceTrait;
    use NoNudTrait;

    /**
     * Constructor.
     *
     * @param string $sequence
     * @param int    $bindingPower
     */
    public function __construct(string $sequence, int $bindingPower = 10)
    {
        $this->sequence     = $sequence;
        $this->bindingPower = $bindingPower;
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
    public function createException(
        TokenInterface $token,
        string $message,
        NodeInterface $node = null
    ): SyntaxException {
        if ($node instanceof LiteralNodeInterface) {
            $message .= sprintf(
                ' Can not assign to literal %s',
                json_encode(
                    $node->getValue(),
                    JSON_UNESCAPED_SLASHES
                    | JSON_PRETTY_PRINT
                )
            );
        }

        return new SyntaxException($token, $this, $message);
    }

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
    ): NodeInterface {
        if (!$left->getArity()->isName()) {
            throw $this->createException(
                $subject,
                sprintf('Unexpected %s.', $left->getArity()),
                $left
            );
        }

        return new AssignmentNode(
            $this->getSequence() ?? '',
            $subject,
            $left,
            $context->parseExpression($this->getBindingPower() - 1)
        );
    }
}
