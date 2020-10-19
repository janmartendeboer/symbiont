<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symbiont\Language\Tokenizer\TokenStream;

return function (TokenStream $tokens): void
{
    foreach ($tokens as $token) {
        echo sprintf(
            "%s\t\t%s",
            $token->getName(),
            var_export($token->getValue(), true)
        ) . PHP_EOL;
    }
};
