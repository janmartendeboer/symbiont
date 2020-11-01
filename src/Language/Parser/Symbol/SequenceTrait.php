<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Parser\Symbol;

trait SequenceTrait
{
    private ?string $sequence = null;

    /**
     * Get the token sequence for the current symbol.
     *
     * @return string|null
     */
    public function getSequence(): ?string
    {
        return $this->sequence;
    }
}
