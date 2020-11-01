<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer;

use Symbiont\Language\Tokenizer\Context\TokenContextInterface;

trait ContextAwareExceptionTrait
{
    /** @var TokenContextInterface|null */
    private ?TokenContextInterface $context;

    /**
     * Get the context in which the unexpected sequence was encountered.
     *
     * @return TokenContextInterface|null
     */
    public function getContext(): ?TokenContextInterface
    {
        return $this->context;
    }
}
