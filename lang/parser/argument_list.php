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

// Create a shared parser for all symbols that support an argument list.
use Symbiont\Language\Parser\ParseContextInterface;

return function (ParseContextInterface $context): array {
    $arguments = [];

    $context->advance('T_PAREN_OPEN');

    for (
        $name = $context->current();
        $name != null && $name->getName() !== 'T_PAREN_CLOSE';
        $name = $context->current()
    ) {
        $value = $context->parseExpression(0);

        $arguments[$name->getValue()] = $value;

        $separator = $context->current();

        if ($separator === null || $separator->getName() !== 'T_COMMA') {
            break;
        }

        $context->advance('T_COMMA');
    }

    $context->advance('T_PAREN_CLOSE');

    return $arguments;
};
