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

use ArrayIterator;
use Closure;
use Symbiont\Language\Ast\Statement\Statement as AstStatement;
use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Parser\ParseContextInterface;
use Traversable;

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
     * @return StatementInterface
     */
    public function std(ParseContextInterface $context): StatementInterface
    {
        $statement = $this->std->call($this, $context) ?? [];

        return new AstStatement(
            $statement instanceof Traversable
                ? $statement
                : new ArrayIterator((array)$statement)
        );
    }
}
