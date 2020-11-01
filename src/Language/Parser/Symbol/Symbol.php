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

class Symbol implements SymbolInterface
{
    use BindingPowerTrait;
    use NoLedTrait;
    use NoNudTrait;
    use SequenceTrait;
    use SyntaxExceptionTrait;

    /**
     * Constructor.
     *
     * @param string $sequence
     * @param int    $bindingPower
     */
    public function __construct(string $sequence, int $bindingPower = 0)
    {
        $this->sequence     = $sequence;
        $this->bindingPower = $bindingPower;
    }
}
