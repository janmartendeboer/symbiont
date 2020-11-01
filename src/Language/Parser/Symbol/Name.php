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
     */
    public function nud(
        ParseContextInterface $context
    ): NodeInterface {
        return new NamedNode($context->current());
    }
}
