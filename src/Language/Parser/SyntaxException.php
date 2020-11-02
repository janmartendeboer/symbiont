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

use LogicException;
use Symbiont\Language\Parser\Symbol\SymbolInterface;
use Symbiont\Language\Tokenizer\Context\TokenContextInterface;
use Symbiont\Language\Tokenizer\ContextAwareExceptionInterface;
use Symbiont\Language\Tokenizer\ContextAwareExceptionTrait;
use Symbiont\Language\Tokenizer\TokenInterface;
use Throwable;

class SyntaxException extends LogicException implements
    ContextAwareExceptionInterface
{
    use ContextAwareExceptionTrait;

    /**
     * Constructor.
     *
     * @param TokenInterface  $token
     * @param SymbolInterface $symbol
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct(
        TokenInterface $token,
        SymbolInterface $symbol,
        string $message,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->context = $token->getContext();

        parent::__construct(
            sprintf(
                'Invalid %s(%s) encountered. %s - %s',
                static::createSymbolName($symbol),
                json_encode(
                    $token->getValue(),
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                ),
                $message,
                static::createMessageContext($this->context)
            ),
            $code,
            $previous
        );
    }

    /**
     * Create the context information for the exception message, if provided.
     *
     * @param TokenContextInterface|null $context
     *
     * @return string
     */
    private static function createMessageContext(
        ?TokenContextInterface $context
    ): string {
        return (
            $context instanceof TokenContextInterface
                ? sprintf(
                    'In %s on line %d column %d',
                    $context->getFile()->getPathname(),
                    $context->getStart()->getLine(),
                    $context->getStart()->getColumn()
                )
                : 'In unknown context'
        );
    }

    /**
     * Create a name for the given symbol, based on the class name of the object.
     *
     * @param SymbolInterface $symbol
     *
     * @return string
     */
    private static function createSymbolName(SymbolInterface $symbol): string
    {
        return preg_replace(
            // Strip off the namespace.
            sprintf('/^%s/', addslashes(__NAMESPACE__ . '\\Symbol\\')),
            '',
            get_class($symbol)
        ) ?? '';
    }
}
