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

namespace Symbiont\Language\Ast\Node\Arity;

use JsonSerializable;

final class Arity implements JsonSerializable
{
    use UnaryArity;
    use BinaryArity;
    use TernaryArity;
    use LiteralArity;
    use ThisArity;
    use NameArity;
    use FunctionArity;
    use StatementArity;

    private string $value;

    /**
     * Arity constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Tells whether the supplied arity matches the current arity.
     *
     * @param object $other
     *
     * @return bool
     */
    public function equals(object $other): bool
    {
        return (
            $other instanceof self
            && $this->value === $other->value
        );
    }

    /**
     * Convert the current arity to a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Convert the arity to a JSON representation.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
