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

use Symbiont\Language\Specification\ConfiguratorInterface;
use Symbiont\Language\Specification\Specification;
use Symbiont\Language\Tokenizer\Finder\TokenFinder;
use Symbiont\Language\Tokenizer\StatelessTokenizer;
use Symbiont\Language\Tokenizer\Strategy\CommentStrategy;
use Symbiont\Language\Tokenizer\Strategy\NumberStrategy;
use Symbiont\Language\Tokenizer\Strategy\SymbolStrategy;
use Symbiont\Language\Tokenizer\Strategy\VariableStrategy;
use Symbiont\Language\Tokenizer\Strategy\WhitespaceStrategy;

return new class implements ConfiguratorInterface {
    public function __invoke(Specification $spec): void
    {
        $spec->tokenizer = new StatelessTokenizer(
            new TokenFinder(
                new WhitespaceStrategy(),
                new CommentStrategy(),
                new VariableStrategy(),
                new NumberStrategy(),
                new SymbolStrategy($spec->symbols)
            ),
            $spec->programMarkers->end
        );
    }
};
