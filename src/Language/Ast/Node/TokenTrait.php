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

namespace Symbiont\Language\Ast\Node;

use Symbiont\Language\Tokenizer\TokenInterface;

trait TokenTrait
{
    private TokenInterface $token;

    /**
     * Get the token for the current node.
     *
     * @return TokenInterface
     */
    public function getToken(): TokenInterface
    {
        return $this->token;
    }
}
