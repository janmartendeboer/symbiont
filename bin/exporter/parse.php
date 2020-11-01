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

return function (StatementListInterface $statements): void {
    fwrite(
        STDOUT,
        json_encode(
            $statements,
            JSON_PRESERVE_ZERO_FRACTION
            | JSON_HEX_QUOT
            | JSON_NUMERIC_CHECK
            | JSON_PRETTY_PRINT
        ) ?: ''
    );
};
