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
use Symbiont\Language\Tokenizer\Optimizer\TokenOptimizer;
use Symbiont\Language\Tokenizer\Strategy\CommentStrategy;
use Symbiont\Language\Tokenizer\Strategy\WhitespaceStrategy;

return new class implements ConfiguratorInterface {
    public function __invoke(Specification $spec): void
    {
        $spec->tokenizer = new TokenOptimizer(
            $spec->tokenizer,
            WhitespaceStrategy::TOKEN_NAME,
            CommentStrategy::TOKEN_NAME
        );
    }
};
