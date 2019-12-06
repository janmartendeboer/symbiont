<?php
/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Ast\Node;

abstract class AbstractNode implements NodeInterface
{
    protected const ARITY_UNARY     = 'unary';
    protected const ARITY_BINARY    = 'binary';
    protected const ARITY_TERNARY   = 'ternary';
    protected const ARITY_LITERAL   = 'literal';
    protected const ARITY_THIS      = 'this';
    protected const ARITY_FUNCTION  = 'function';
    protected const ARITY_STATEMENT = 'statement';

    protected string $arity;

    /**
     * Get the arity of the current node.
     *
     * - unary
     * - binary
     * - ternary
     * - literal
     * - this
     * - function
     * - statement
     *
     * @return string
     */
    public function getArity(): string
    {
        return $this->arity;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'arity' => $this->getArity(),
            'token' => $this->getToken()
        ];
    }
}
