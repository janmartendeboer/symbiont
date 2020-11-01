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
     * @return array<mixed, mixed>
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
