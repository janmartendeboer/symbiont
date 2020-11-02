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

use Symbiont\Language\Ast\Node\LiteralNode;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Tokenizer\UnexpectedEndOfStreamException;

class Literal implements SymbolInterface
{
    use BindingPowerTrait;
    use NoLedTrait;
    use SequenceTrait;
    use SyntaxExceptionTrait;

    /**
     * Invoke the symbol as a null denoted operator.
     *
     * @param ParseContextInterface $context
     *
     * @return NodeInterface
     *
     * @throws UnexpectedEndOfStreamException When there is no current token.
     */
    public function nud(
        ParseContextInterface $context
    ): NodeInterface {
        $token = $context->current();

        if ($token === null) {
            throw new UnexpectedEndOfStreamException(null);
        }

        return new LiteralNode($token);
    }
}
