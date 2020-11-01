<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symbiont\Language\Parser\Parser;
use Symbiont\Language\Parser\Symbol\SymbolTable;

return new Parser(
    SymbolTable::getInstance(__DIR__ . '/symbol/*/*.php'),
    'T_CURLY_OPEN',
    'T_CURLY_CLOSE',
    'T_END_STATEMENT',
    'T_END_PROGRAM'
);
