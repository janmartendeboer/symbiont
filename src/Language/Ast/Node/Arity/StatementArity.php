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

trait StatementArity
{
    private static Arity $statement;

    /**
     * Get the statement arity.
     *
     * @return Arity
     */
    public static function statement(): Arity
    {
        return static::$statement ??= new Arity('statement');
    }

    /**
     * Determine if the current object instance is a statement arity.
     *
     * @return bool
     */
    public function isStatement(): bool
    {
        return static::statement()->equals($this);
    }
}
