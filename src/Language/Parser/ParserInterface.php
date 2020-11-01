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
use Symbiont\Language\Tokenizer\TokenStreamInterface;

interface ParserInterface
{
    /**
     * Parse the given token stream into a list of statements.
     *
     * @param TokenStreamInterface $tokens
     *
     * @return StatementListInterface
     */
    public function __invoke(
        TokenStreamInterface $tokens
    ): StatementListInterface;

    /**
     * Parse the current expression with the given binding power.
     *
     * @param ParseContextInterface $context
     * @param int                   $bindingPower
     *
     * @return NodeInterface
     */
    public function parseExpression(
        ParseContextInterface $context,
        int $bindingPower
    ): NodeInterface;

    /**
     * Parse the current statement.
     *
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     */
    public function parseStatement(
        ParseContextInterface $context
    ): StatementInterface;

    /**
     * Parse the current list of statements;
     *
     * @param ParseContextInterface $context
     *
     * @return StatementListInterface
     */
    public function parseStatements(
        ParseContextInterface $context
    ): StatementListInterface;

    /**
     * Parse the current code block.
     *
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     */
    public function parseBlock(
        ParseContextInterface $context
    ): StatementInterface;
}
