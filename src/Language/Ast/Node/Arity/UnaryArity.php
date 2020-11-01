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

trait UnaryArity
{
    private static Arity $unary;

    /**
     * Get the unary arity.
     *
     * @return Arity
     */
    public static function unary(): Arity
    {
        return static::$unary ??= new Arity('unary');
    }

    /**
     * Determine if the current object instance is a unary arity.
     *
     * @return bool
     */
    public function isUnary(): bool
    {
        return static::unary()->equals($this);
    }
}
