<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Ast\Node;

use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Tokenizer\TokenInterface;

class FunctionNode extends AbstractBinaryNode
{
    use TokenTrait;

    /**
     * Constructor.
     *
     * @param TokenInterface         $token
     * @param iterable               $argumentList
     * @param StatementListInterface $body
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
