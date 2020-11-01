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

namespace Symbiont\Language\Tokenizer;

use Symbiont\Language\Tokenizer\Context\TokenContextInterface;
use Throwable;

interface ContextAwareExceptionInterface extends Throwable
{
    /**
     * Get the context in which the exception was encountered.
     *
     * @return TokenContextInterface|null
     */
    public function getContext(): ?TokenContextInterface;
}
