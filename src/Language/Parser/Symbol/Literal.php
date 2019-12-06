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

use Symbiont\Language\Ast\Node\LiteralNode;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;

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
     */
    public function nud(
        ParseContextInterface $context
    ): NodeInterface {
        return new LiteralNode($context->current());
    }
}
