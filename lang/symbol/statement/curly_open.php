<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Parser\Symbol\Statement;

return new Statement(
    '{',
    function (ParseContextInterface $context): iterable {
        $context->advance('T_CURLY_OPEN');
        $context->newScope();
        $statements = $context->parseStatements();
        $context->popScope();
        $context->current('T_CURLY_CLOSE');

        return $statements;
    }
);
