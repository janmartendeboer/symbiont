<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Parser\Symbol;

use Closure;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Ast\Node\OperatorNode;
use Symbiont\Language\Parser\ParseContextInterface;

class Prefix implements SymbolInterface
{
    use BindingPowerTrait;
    use SequenceTrait;
    use NoLedTrait;
    use SyntaxExceptionTrait;

    public const RIGHT_BINDING_POWER = 70;

    private Closure $nud;

    private int $rightBindingPower;

    /**
     * Constructor.
     *
     * @param string       $sequence
     * @param Closure|null $nud
     * @param int          $rightBindingPower
     */
    public function __construct(
        string $sequence,
        Closure $nud = null,
        int $rightBindingPower = self::RIGHT_BINDING_POWER
    ) {
        $this->sequence          = $sequence;
        $this->nud               = $nud ?? $this->createNud();
        $this->rightBindingPower = $rightBindingPower;
    }

    /**
     * @return Closure
     */
    private function createNud(): Closure
    {
        return function (ParseContextInterface $context): NodeInterface {
            $node = new OperatorNode(
                $this->getSequence(),
                $context->current(),
                $context->parseExpression($this->rightBindingPower)
            );

            $context->getScope()->reserve($node, $this);

            return $node;
        };
    }

    /**
     * Invoke the symbol as a null denoted operator.
     *
     * @param ParseContextInterface $context
     *
     * @return NodeInterface
     */
    public function nud(ParseContextInterface $context): NodeInterface
    {
        return $this->nud->call($this, $context);
    }
}
