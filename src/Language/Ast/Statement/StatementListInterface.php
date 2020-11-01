<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Ast\Statement;

use Iterator;
use JsonSerializable;

interface StatementListInterface extends Iterator, JsonSerializable
{
    /**
     * Get the current statement.
     *
     * @return StatementInterface|null
     */
    public function current(): ?StatementInterface;

    /**
     * Advance to the next statement and return it.
     *
     * @return StatementInterface|null
     */
    public function advance(): ?StatementInterface;
}
