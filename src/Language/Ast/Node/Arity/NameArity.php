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

trait NameArity
{
    private static Arity $name;

    /**
     * Get the name arity.
     *
     * @return Arity
     */
    public static function name(): Arity
    {
        return static::$name ??= new Arity('name');
    }

    /**
     * Determine if the current object instance is a name arity.
     *
     * @return bool
     */
    public function isName(): bool
    {
        return static::name()->equals($this);
    }
}
