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

return array_reduce(
    glob(__DIR__ . '/config.d/[1-9][0-9]-*.php', GLOB_ERR) ?: [],
    function (Specification $carry, string $file): Specification {
        $configurator = null;

        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            $configurator = include $file;
        }

        if ($configurator instanceof ConfiguratorInterface) {
            $configurator($carry);
        }

        return $carry;
    },
    new Specification()
);
