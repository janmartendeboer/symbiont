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

trait LiteralArity
{
    private static Arity $literal;

    /**
     * Get the literal arity.
     *
     * @return Arity
     */
    public static function literal(): Arity
    {
        return static::$literal ??= new Arity('literal');
    }

    /**
     * Determine if the current object instance is a literal arity.
     *
     * @return bool
     */
    public function isLiteral(): bool
    {
        return static::literal()->equals($this);
    }
}
