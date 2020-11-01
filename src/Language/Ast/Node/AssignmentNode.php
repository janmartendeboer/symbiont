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

class AssignmentNode extends AbstractBinaryNode implements OperatorNodeInterface
{
    use OperatorTrait;
    use TokenTrait;

    /**
     * Constructor.
     *
     * @param string         $operator
     * @param TokenInterface $token
     * @param NodeInterface  $first
     * @param NodeInterface  $second
     */
    public function __construct(
        string $operator,
        TokenInterface $token,
        NodeInterface $first,
        NodeInterface $second
    ) {
        $this->operator = $operator;
        $this->token    = $token;
        $this->first    = $first;
        $this->second   = $second;
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
