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

$tokenizer = require __DIR__ . '/../../lang/tokenizer.php';

return function (SplFileInfo $file) use($tokenizer): TokenStream
{
    return new TokenStream($tokenizer($file));
};
