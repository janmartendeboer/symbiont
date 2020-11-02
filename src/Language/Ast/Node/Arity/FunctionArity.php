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

trait FunctionArity
{
    private static Arity $function;

    /**
     * Get the function arity.
     *
     * @return Arity
     */
    public static function function(): Arity
    {
        return static::$function ??= new Arity('function');
    }

    /**
     * Determine if the current object instance is a function arity.
     *
     * @return bool
     */
    public function isFunction(): bool
    {
        return static::function()->equals($this);
    }
}
