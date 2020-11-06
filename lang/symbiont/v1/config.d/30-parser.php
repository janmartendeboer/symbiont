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

use Symbiont\Language\Parser\Parser;
use Symbiont\Language\Specification\ConfiguratorInterface;
use Symbiont\Language\Specification\Specification;

return new class implements ConfiguratorInterface {
    public function __invoke(Specification $spec): void
    {
        $spec->parser = new Parser(
            $spec->symbols,
            $spec->blockMarkers->start,
            $spec->blockMarkers->end,
            $spec->statementMarkers->end,
            $spec->programMarkers->end
        );
    }
};
