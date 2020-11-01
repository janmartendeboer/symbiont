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

use Symbiont\Language\Ast\Statement\StatementListInterface;

$parser = require __DIR__ . '/../../lang/parser.php';
$tokenizer = require __DIR__ . '/tokenize.php';

return function (
    SplFileObject $file
) use (
    $parser,
    $tokenizer
): StatementListInterface {
    return $parser($tokenizer($file));
};
