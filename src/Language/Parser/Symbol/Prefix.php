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

use Closure;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Ast\Node\OperatorNode;
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Tokenizer\UnexpectedEndOfStreamException;

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
     *
     * @throws UnexpectedEndOfStreamException When there is no current token.
     */
    private function createNud(): Closure
    {
        return function (ParseContextInterface $context): NodeInterface {
            $token = $context->current();

            if ($token === null) {
                throw new UnexpectedEndOfStreamException($this->getSequence());
            }

            return new OperatorNode(
                $this->getSequence() ?? '',
                $token,
                $context->parseExpression($this->rightBindingPower)
            );
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
