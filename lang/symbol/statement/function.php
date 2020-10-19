<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symbiont\Language\Ast\Node\FunctionNode;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Parser\ParseContextInterface;
use Symbiont\Language\Parser\Symbol\Prefix;

$argumentParser = require __DIR__ . '/../../parser/argument_list.php';

return new Prefix(
    'function',
    function (
        ParseContextInterface $context
    ) use ($argumentParser): NodeInterface {
        $token = $context->current();
        $context->advance('T_FUNCTION');

        // @todo support named functions.

        $context->newScope();
        $arguments = $argumentParser($context);

        $context->advance('T_CURLY_OPEN');

        $function = new FunctionNode(
            $token,
            $arguments,
            $context->parseStatements()
        );

        $context->popScope();
        $context->current('T_CURLY_CLOSE');

        return $function;
    }
);