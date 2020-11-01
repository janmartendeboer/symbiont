<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Tokenizer;

use DomainException;
use Symbiont\Language\Tokenizer\Context\TokenContextInterface;
use Throwable;

class UnexpectedTokenException extends DomainException implements
    ContextAwareExceptionInterface
{
    use ContextAwareExceptionTrait;

    /**
     * Constructor.
     *
     * @param string         $expected
     * @param TokenInterface $actual
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $expected,
        TokenInterface $actual,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->context = $actual->getContext();

        parent::__construct(
            $this->context instanceof TokenContextInterface
                ? static::createContextualMessage($expected, $actual, $this->context)
                : static::createContextLessMessage($expected, $actual),
            $code,
            $previous
        );
    }

    /**
     * Create an exception message without context to its origins.
     *
     * @param string         $expected
     * @param TokenInterface $actual
     *
     * @return string
     */
    private static function createContextLessMessage(
        string $expected,
        TokenInterface $actual
    ): string {
        return sprintf(
            'Unexpected token %s, expected %s in unknown context.',
            $actual->getName(),
            $expected
        );
    }

    /**
     * Create a contextual exception message.
     *
     * @param string                $expected
     * @param TokenInterface        $actual
     * @param TokenContextInterface $context
     *
     * @return string
     */
    private static function createContextualMessage(
        string $expected,
        TokenInterface $actual,
        TokenContextInterface $context
    ): string {
        $cursor  = $context->getStart();
        $file    = $context->getFile();

        return sprintf(
            'Unexpected token %s, expected %s in %s on line %d column %d.',
            $actual->getName(),
            $expected,
            $file->getPathname(),
            $cursor->getLine(),
            $cursor->getColumn()
        );
    }
}
