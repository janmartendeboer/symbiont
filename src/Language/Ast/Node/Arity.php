<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Language\Ast\Node;

use JsonSerializable;

final class Arity implements JsonSerializable
{
    private const ARITY_UNARY     = 'unary';
    private const ARITY_BINARY    = 'binary';
    private const ARITY_TERNARY   = 'ternary';
    private const ARITY_LITERAL   = 'literal';
    private const ARITY_THIS      = 'this';
    private const ARITY_NAME      = 'name';
    private const ARITY_FUNCTION  = 'function';
    private const ARITY_STATEMENT = 'statement';

    private string $value;

    private static array $instances = [];

    /**
     * Arity constructor.
     *
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get an instance of the arity that matches the given value.
     *
     * @param string $value
     *
     * @return self
     */
    private static function getInstance(string $value): self
    {
        if (!array_key_exists($value, self::$instances)) {
            self::$instances[$value] = new self($value);
        }

        return self::$instances[$value];
    }

    /**
     * Get the unary instance.
     *
     * @return self
     */
    public static function unary(): self
    {
        return self::getInstance(self::ARITY_UNARY);
    }

    /**
     * Get the binary instance.
     *
     * @return self
     */
    public static function binary(): self
    {
        return self::getInstance(self::ARITY_BINARY);
    }

    /**
     * Get the ternary instance.
     *
     * @return self
     */
    public static function ternary(): self
    {
        return self::getInstance(self::ARITY_TERNARY);
    }

    /**
     * Get the literal instance.
     *
     * @return self
     */
    public static function literal(): self
    {
        return self::getInstance(self::ARITY_LITERAL);
    }

    /**
     * Get the this instance.
     *
     * @return self
     */
    public static function this(): self
    {
        return self::getInstance(self::ARITY_THIS);
    }

    /**
     * Get the name instance.
     *
     * @return self
     */
    public static function name(): self
    {
        return self::getInstance(self::ARITY_NAME);
    }

    /**
     * Get the function instance.
     *
     * @return self
     */
    public static function function(): self
    {
        return self::getInstance(self::ARITY_FUNCTION);
    }

    /**
     * Get the statement instance.
     *
     * @return self
     */
    public static function statement(): self
    {
        return self::getInstance(self::ARITY_STATEMENT);
    }

    /**
     * Tells whether the supplied arity matches the current arity.
     *
     * @param Arity $other
     *
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
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
