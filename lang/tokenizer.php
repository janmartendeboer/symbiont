<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symbiont\Language\Parser\Symbol\SymbolTable;
use Symbiont\Language\Tokenizer\Finder\TokenFinder;
use Symbiont\Language\Tokenizer\Optimizer\TokenOptimizer;
use Symbiont\Language\Tokenizer\StatelessTokenizer;
use Symbiont\Language\Tokenizer\Strategy\CommentStrategy;
use Symbiont\Language\Tokenizer\Strategy\NumberStrategy;
use Symbiont\Language\Tokenizer\Strategy\SymbolStrategy;
use Symbiont\Language\Tokenizer\Strategy\VariableStrategy;
use Symbiont\Language\Tokenizer\Strategy\WhitespaceStrategy;

return new TokenOptimizer(
    new StatelessTokenizer(
        new TokenFinder(
            new WhitespaceStrategy(),
            new CommentStrategy(),
            new VariableStrategy(),
            new NumberStrategy(),
            new SymbolStrategy(
                SymbolTable::getInstance(__DIR__ . '/symbol/*/*.php')
            )
        ),
        'T_END_PROGRAM'
    ),
    WhitespaceStrategy::TOKEN_NAME,
    CommentStrategy::TOKEN_NAME
);
