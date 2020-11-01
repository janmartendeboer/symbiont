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

namespace Symbiont\Language\Ast\Node;

use JsonSerializable;
use Symbiont\Language\Ast\Node\Arity\Arity;
use Symbiont\Language\Tokenizer\TokenInterface;

interface NodeInterface extends JsonSerializable
{
    /**
     * Get the arity of the current node.
     *
     * @return Arity
     */
    public function getArity(): Arity;

    /**
     * Get the token for the current node.
     *
     * @return TokenInterface
     */
    public function getToken(): TokenInterface;

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<mixed, mixed>
     */
    public function jsonSerialize(): array;
}
