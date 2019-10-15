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

class Token implements TokenInterface
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $value;

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
     * Get the value of the token.
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
}
