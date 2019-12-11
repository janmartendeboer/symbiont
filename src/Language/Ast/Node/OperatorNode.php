<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Ast\Node;

use Symbiont\Language\Tokenizer\TokenInterface;

class OperatorNode extends AbstractUnaryNode
{
    use TokenTrait;
    use OperatorTrait;

    /**
     * Constructor.
     *
     * @param string         $operator
     * @param TokenInterface $token
     * @param NodeInterface  $first
     */
    public function __construct(
        string $operator,
        TokenInterface $token,
        NodeInterface $first
    ) {
        $this->operator = $operator;
        $this->token    = $token;
        $this->first    = $first;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'operator' => $this->getOperator()
            ]
        );
    }
}
