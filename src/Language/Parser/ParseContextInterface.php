<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Parser;

use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Ast\Statement\StatementListInterface;
use Symbiont\Language\Parser\Symbol\SymbolHolderInterface;
use Symbiont\Language\Tokenizer\TokenPointerInterface;
use Symbiont\Language\Tokenizer\TokenInterface;
use Symbiont\Language\Tokenizer\UnexpectedTokenException;

interface ParseContextInterface extends
    TokenPointerInterface,
    SymbolHolderInterface
{
    /**
     * Parse the current expression with the given binding power.
     *
     * @param int $bindingPower
     *
     * @return NodeInterface
     */
    public function parseExpression(int $bindingPower): NodeInterface;

    /**
     * Parse the current statement.
     *
     * @return StatementInterface
     */
    public function parseStatement(): StatementInterface;

    /**
     * Parse the current list of statements.
     *
     * @return StatementListInterface
     */
    public function parseStatements(): StatementListInterface;

    /**
     * Parse the current code block.
     *
     * @return StatementInterface
     */
    public function parseBlock(): StatementInterface;

    /**
     * Get the current token, if the token stream has started.
     *
     * When a specific token is provided, the produced token must match.
     *
     * @param string|null $token
     *
     * @return TokenInterface|null
     *
     * @throws UnexpectedTokenException When the produced token does not match.
     */
    public function current(string $token = null): ?TokenInterface;
}
