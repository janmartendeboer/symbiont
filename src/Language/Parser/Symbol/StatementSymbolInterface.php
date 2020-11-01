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

use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Parser\ParseContextInterface;

interface StatementSymbolInterface extends SymbolInterface
{
    /**
     * Invoke the symbol as a statement denotation.
     *
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     */
    public function std(ParseContextInterface $context): StatementInterface;
}
