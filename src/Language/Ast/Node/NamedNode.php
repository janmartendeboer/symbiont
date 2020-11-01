<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Ast\Node;

use Symbiont\Language\Ast\Node\Arity\Arity;
use Symbiont\Language\Tokenizer\TokenInterface;

class NamedNode extends AbstractNode implements LiteralNodeInterface
{
    use TokenTrait;

    /**
     * Constructor.
     *
     * @param TokenInterface $token
     */
    public function __construct(TokenInterface $token)
    {
        $this->token = $token;
    }

    /**
     * Create the arity that matches the current node type.
     *
     * @return Arity
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function createArity(): Arity
    {
        return Arity::name();
    }

    /**
     * Get the value of the literal node.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->getToken()->getValue();
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
                'token' => $this->getToken()->getName(),
                'value' => $this->getValue()
            ]
        );
    }
}
