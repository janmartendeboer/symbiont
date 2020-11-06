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

use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Specification\Specification;

return (function (Specification $specification): callable {
    return function (
        SplFileObject $file
    ) use (
        $specification
    ): StatementListInterface {
        return $specification->parser->__invoke(
            $specification->tokenizer->__invoke($file)
        );
    };
})(require __DIR__ . '/../../lang/symbiont/v1/spec.php');
