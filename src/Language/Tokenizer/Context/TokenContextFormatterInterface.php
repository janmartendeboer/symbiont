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

namespace Symbiont\Language\Tokenizer\Context;

interface TokenContextFormatterInterface
{
    /**
     * Format context for the given file, using the given cursor.
     *
     * @param TokenContextInterface $context
     *
     * @return string
     */
    public function __invoke(TokenContextInterface $context): string;
}
