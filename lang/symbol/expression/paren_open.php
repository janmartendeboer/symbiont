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

use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Parser\Symbol\Prefix;
use Symbiont\Language\Parser\Symbol\SymbolInterface;

return new Prefix(
    '(',
    function (ParseContextInterface $context): NodeInterface {
        /** @var SymbolInterface $this */
        $context->advance('T_PAREN_OPEN');
        $expression = $context->parseExpression($this->getBindingPower());
        $context->current('T_PAREN_CLOSE');

        return $expression;
    }
);
