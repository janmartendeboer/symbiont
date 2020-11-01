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

trait BinaryArity
{
    private static Arity $binary;

    /**
     * Get the binary arity.
     *
     * @return Arity
     */
    public static function binary(): Arity
    {
        return static::$binary ??= new Arity('binary');
    }

    /**
     * Determine if the current object instance is a binary arity.
     *
     * @return bool
     */
    public function isBinary(): bool
    {
        return static::binary()->equals($this);
    }
}
