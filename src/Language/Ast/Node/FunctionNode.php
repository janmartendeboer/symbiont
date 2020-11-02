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

use Symbiont\Language\Ast\Node\Arity\Arity;
use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Tokenizer\TokenInterface;

class FunctionNode extends AbstractBinaryNode
{
    use TokenTrait;

    /**
     * Constructor.
     *
     * @param TokenInterface                  $token
     * @param iterable<string, NodeInterface> $argumentList
     * @param StatementListInterface          $body
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(
        TokenInterface $token,
        iterable $argumentList,
        StatementListInterface $body
    ) {
        $this->token  = $token;
        $this->first  = $argumentList;
        $this->second = $body;
        $this->arity  = Arity::function();
    }
}
