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

trait ThisArity
{
    private static Arity $this;

    /**
     * Get the this arity.
     *
     * @return Arity
     */
    public static function this(): Arity
    {
        return static::$this ??= new Arity('this');
    }

    /**
     * Determine if the current object instance is a this arity.
     *
     * @return bool
     */
    public function isThis(): bool
    {
        return static::this()->equals($this);
    }
}
