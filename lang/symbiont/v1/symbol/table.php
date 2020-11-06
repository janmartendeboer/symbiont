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

use Symbiont\Language\Parser\Symbol\SymbolInterface;
use Symbiont\Language\Parser\Symbol\SymbolTable;
use Symbiont\Language\Parser\Symbol\SymbolTableInterface;
use Symbiont\Language\Specification\ConfiguratorInterface;
use Symbiont\Language\Specification\Specification;

return new class implements ConfiguratorInterface {
    public function __invoke(Specification $spec): void
    {
        $spec->symbols = array_reduce(
            glob(__DIR__ . '/*/*.php', GLOB_ERR | GLOB_NOSORT) ?: [],
            function (
                SymbolTableInterface $carry,
                string $file
            ): SymbolTableInterface {
                /** @noinspection PhpIncludeInspection */
                $symbol = require $file;

                if ($symbol instanceof SymbolInterface) {
                    $carry->register(
                        sprintf(
                            'T_%s',
                            strtoupper(basename($file, '.php'))
                        ),
                        $symbol
                    );
                }

                return $carry;
            },
            new SymbolTable()
        );

        $spec->blockMarkers->start = 'T_CURLY_OPEN';
        $spec->blockMarkers->end = 'T_CURLY_CLOSE';

        $spec->statementMarkers->end = 'T_END_STATEMENT';
        $spec->programMarkers->end = 'T_END_PROGRAM';
    }
};
