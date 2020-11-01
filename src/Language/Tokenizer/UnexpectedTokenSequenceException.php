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

namespace Symbiont\Language\Tokenizer;

use DomainException;
use Symbiont\Language\Tokenizer\Context\TokenContextInterface;
use Throwable;

class UnexpectedTokenSequenceException extends DomainException implements
    ContextAwareExceptionInterface
{
    use ContextAwareExceptionTrait;

    /** @var string */
    private string $sequence;

    /**
     * Constructor.
     *
     * @param string                     $sequence
     * @param TokenContextInterface|null $context
     * @param int                        $code
     * @param Throwable|null             $previous
     */
    public function __construct(
        string $sequence,
        TokenContextInterface $context = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->sequence = $sequence;
        $this->context  = $context;

        $message = sprintf(
            'Unexpected token sequence %s',
            json_encode($sequence)
        );

        if ($context !== null) {
            $start = $context->getStart();

            $message .= sprintf(
                ' in file %s at line %d column %d',
                $context->getFile()->getPathname(),
                $start->getLine(),
                $start->getColumn()
            );
        }

        parent::__construct($message . '.', $code, $previous);
    }

    /**
     * Get the sequence.
     *
     * @return string
     */
    public function getSequence(): string
    {
        return $this->sequence;
    }
}
