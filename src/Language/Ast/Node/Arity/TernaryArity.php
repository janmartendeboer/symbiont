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

trait TernaryArity
{
    private static Arity $ternary;

    /**
     * Get the ternary arity.
     *
     * @return Arity
     */
    public static function ternary(): Arity
    {
        return static::$ternary ??= new Arity('ternary');
    }

    /**
     * Determine if the current object instance is a ternary arity.
     *
     * @return bool
     */
    public function isTernary(): bool
    {
        return static::ternary()->equals($this);
    }
}
