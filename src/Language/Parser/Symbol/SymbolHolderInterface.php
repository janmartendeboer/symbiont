<?php

declare(strict_types=1);

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Symbiont\Language\Parser\Symbol;

interface SymbolHolderInterface
{
    /**
     * Get the symbol for the given token.
     *
     * @param string $token
     *
     * @return SymbolInterface|null
     */
    public function getSymbol(string $token): ?SymbolInterface;
}
