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
use Symbiont\Language\Tokenizer\TokenStreamInterface;

interface ParserInterface
{
    /**
     * Parse the given token stream into a list of statements.
     *
     * @param TokenStreamInterface $tokens
     *
     * @return iterable|NodeInterface[]
     */
    public function __invoke(TokenStreamInterface $tokens): iterable;

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
     * @return NodeInterface|NodeInterface[]|null
     */
    public function parseStatement(
        ParseContextInterface $context
    );

    /**
     * Parse the current list of statements;
     *
     * @param ParseContextInterface $context
     *
     * @return iterable|NodeInterface[]
     */
    public function parseStatements(ParseContextInterface $context): iterable;

    /**
     * Parse the current code block.
     *
     * @param ParseContextInterface $context
     *
     * @return NodeInterface
     */
    public function parseBlock(ParseContextInterface $context): NodeInterface;
}
