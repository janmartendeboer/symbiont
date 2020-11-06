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

namespace Symbiont\Language\Specification;

use Symbiont\Language\Parser\ParserInterface;
use Symbiont\Language\Parser\Symbol\SymbolTableInterface;
use Symbiont\Language\Tokenizer\TokenizerInterface;

final class Specification
{
    public SymbolTableInterface $symbols;

    public Markers $blockMarkers;
    public Markers $statementMarkers;
    public Markers $programMarkers;

    public TokenizerInterface $tokenizer;

    public ParserInterface $parser;

    public function __construct()
    {
        $this->blockMarkers     = new Markers();
        $this->statementMarkers = new Markers();
        $this->programMarkers   = new Markers();
    }
}
