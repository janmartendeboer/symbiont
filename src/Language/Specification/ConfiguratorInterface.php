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

namespace Symbiont\Language\Specification;

interface ConfiguratorInterface
{
    /**
     * Configure the supplied specification.
     *
     * @param Specification $spec
     *
     * @return void
     */
    public function __invoke(Specification $spec): void;
}
