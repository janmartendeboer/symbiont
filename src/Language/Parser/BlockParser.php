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

namespace Symbiont\Language\Parser;

use OutOfRangeException;
use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Parser\Symbol\StatementSymbolInterface;

trait BlockParser
{
    private string $startBlockToken;

    private string $endBlockToken;

    /**
     * Parse the current code block.
     *
     * @param ParseContextInterface $context
     *
     * @return StatementInterface
     *
     * @throws SyntaxException When the current symbol is not a statement.
     * @throws OutOfRangeException When the current token does not map to a symbol.
     */
    public function parseBlock(
        ParseContextInterface $context
    ): StatementInterface {
        $token  = $context->current();
        $symbol = $context->getSymbol((string)$token);

        if ($token === null || $symbol === null) {
            throw new OutOfRangeException(
                sprintf(
                    'No symbol registered for token: %s',
                    json_encode($token)
                )
            );
        }

        if (!$symbol instanceof StatementSymbolInterface) {
            throw $symbol->createException(
                $token,
                'Symbol does not denote a statement.'
            );
        }

        $context->advance($this->startBlockToken);

        return $symbol->std($context);
    }
}
