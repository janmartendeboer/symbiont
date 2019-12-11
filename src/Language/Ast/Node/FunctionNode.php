<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Ast\Node;

use Symbiont\Language\Tokenizer\TokenInterface;

class FunctionNode extends AbstractBinaryNode
{
    use TokenTrait;

    /**
     * Constructor.
     *
     * @param TokenInterface  $token
     * @param string[]        $argumentList
     * @param NodeInterface[] $body
     */
    public function __construct(
        TokenInterface $token,
        iterable $argumentList,
        iterable $body
    ) {
        $this->token  = $token;
        $this->first  = $argumentList;
        $this->second = $body;
        $this->arity  = static::ARITY_FUNCTION;
    }
}
