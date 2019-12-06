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

use Symbiont\Language\Tokenizer\Context\TokenContextInterface;

class Token implements TokenInterface
{
    private string $name;

    private ?string $value;

    private ?TokenContextInterface $context = null;

    /**
     * Constructor.
     *
     * @param string      $name
     * @param string|null $value
     */
    public function __construct(string $name, string $value = null)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * Get the name of the token.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the name of the token.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Get the value of the token.
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Get the context in which the token was found.
     *
     * @return TokenContextInterface|null
     */
    public function getContext(): ?TokenContextInterface
    {
        return $this->context;
    }

    /**
     * Create a new token with the given context.
     *
     * @param TokenContextInterface $context
     *
     * @return TokenInterface
     */
    public function withContext(TokenContextInterface $context): TokenInterface
    {
        $token          = clone $this;
        $token->context = $context;

        return $token;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value
        ];
    }
}
