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

use Symbiont\Language\Ast\Node\NamedNode;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Tokenizer\UnexpectedEndOfStreamException;

class Name implements SymbolInterface
{
    use BindingPowerTrait;
    use NoLedTrait;
    use SequenceTrait;
    use SyntaxExceptionTrait;

    /**
     * Constructor.
     *
     * @param int $bindingPower
     */
    public function __construct(int $bindingPower = 0)
    {
        $this->bindingPower = $bindingPower;
    }

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

        return new NamedNode($token);
    }
}
