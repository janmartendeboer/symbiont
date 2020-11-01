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

namespace Symbiont\Language\Parser;

use OutOfRangeException;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Tokenizer\UnexpectedEndOfStreamException;

trait ExpressionParser
{
    /**
     * Create an abstract syntax tree.
     *
     * @param ParseContextInterface $context
     * @param int                   $bindingPower
     *
     * @return NodeInterface
     *
     * @throws OutOfRangeException When the left side of the expression contains
     *   no known symbol.
     * @throws UnexpectedEndOfStreamException When the tokens stop mid expression.
     */
    public function parseExpression(
        ParseContextInterface $context,
        int $bindingPower
    ): NodeInterface {
        $current = $context->getSymbol((string)$context->current());

        if ($current === null) {
            throw new UnexpectedEndOfStreamException(null);
        }

        $left    = $current->nud($context);
        $subject = $context->advance();
        $symbol  = $context->getSymbol((string)$subject);

        while (
            $symbol !== null
            && $bindingPower < $symbol->getBindingPower()
        ) {
            if ($subject === null) {
                throw new UnexpectedEndOfStreamException(null);
            }

            $context->advance();
            $left    = $symbol->led($context, $subject, $left);
            $subject = $context->current();
            $symbol  = $context->getSymbol((string)$subject);
        }

        return $left;
    }
}
