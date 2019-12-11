<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Parser\Symbol;

use Closure;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;

class Statement implements StatementSymbolInterface
{
    use BindingPowerTrait;
    use SequenceTrait;
    use NoLedTrait;
    use NoNudTrait;
    use SyntaxExceptionTrait;

    private Closure $std;

    /**
     * Constructor.
     *
     * @param string  $sequence
     * @param Closure $std
     */
    public function __construct(string $sequence, Closure $std)
    {
        $this->sequence = $sequence;
        $this->std      = $std;
    }

    /**
     * Invoke the symbol as a statement denotation.
     *
     * @param ParseContextInterface $context
     *
     * @return null|NodeInterface|NodeInterface[]
     */
    public function std(ParseContextInterface $context)
    {
        return $this->std->call($this, $context);
    }
}
