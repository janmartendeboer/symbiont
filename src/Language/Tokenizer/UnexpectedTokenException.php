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
        ?TokenInterface $actual,
        int $code = 0,
        Throwable $previous = null
    ) {
        $context = $actual->getContext();
        $cursor  = $context->getStart();
        $file    = $context->getFile();

        $this->context = $context;

        parent::__construct(
            sprintf(
                'Unexpected token %s, expected %s in %s on line %d column %d.',
                $actual->getName(),
                $expected,
                $file->getPathname(),
                $cursor->getLine(),
                $cursor->getColumn()
            ),
            $code,
            $previous
        );
    }
}
