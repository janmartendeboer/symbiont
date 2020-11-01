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

use Symbiont\Language\Ast\Node\BinaryNodeInterface;
use Symbiont\Language\Ast\Node\NodeInterface;
use Symbiont\Language\Ast\Node\TernaryNodeInterface;
use Symbiont\Language\Ast\Node\UnaryNodeInterface;
use Symbiont\Language\Ast\Statement\StatementInterface;
use Symbiont\Language\Ast\Statement\StatementListInterface;

$nodeFormatter = new class
{
    public static function createPadding(int $indent): string
    {
        return str_repeat("\t", $indent);
    }

    public function __invoke(?string $parent, NodeInterface $node, int $indent): string
    {
        $padding = static::createPadding($indent);
        $name = sprintf('Node_%d', spl_object_id($node));
        $result = $padding . sprintf(
            '%1$s [label="%2$s|{%3$s|%4$s}" shape=record]',
            $name,
            $node->getToken()->getName(),
            $node->getArity(),
            $node->getToken()->getValue()
        ) . PHP_EOL;

        if ($parent != null) {
            $result .= $padding . sprintf('"%s" -> "%s"', $parent, $name) . PHP_EOL;
        }

        if ($node instanceof UnaryNodeInterface) {
            $result .= rtrim($this->formatValue($name, $node->getFirst(), $indent + 1)) . PHP_EOL;
        }

        if ($node instanceof BinaryNodeInterface) {
            $result .= rtrim($this->formatValue($name, $node->getSecond(), $indent + 1)) . PHP_EOL;
        }

        if ($node instanceof TernaryNodeInterface) {
            $result .= rtrim($this->formatValue($name, $node->getThird(), $indent + 1)) . PHP_EOL;
        }

        return rtrim($result) . PHP_EOL;
    }

    private function formatStatement(
        string $parent,
        StatementInterface $statement,
        int $indent
    ): string {
        $padding = static::createPadding($indent);
        $key = sprintf('%s_Statement_%d', $parent, spl_object_id($statement));
        $result = $padding . sprintf(
            '%1$s [label="Statement"]' . PHP_EOL,
            $key
        );

        $dependents = [];

        foreach ($statement as $node) {
            if ($node === null) {
                continue;
            }

            $dependents[] = sprintf('Node_%d', spl_object_id($node));
            $result .= rtrim($this->__invoke(null, $node, $indent + 1)) . PHP_EOL;
        }

        if (count($dependents)) {
            $result .= $padding . sprintf(
                '"%s" -> {"%s"}',
                $key,
                implode('", "', array_unique($dependents))
            ) . PHP_EOL;
        }

        $result .= $padding . sprintf('"%s" -> "%s"', $parent, $key) . PHP_EOL;

        return rtrim($result) . PHP_EOL;
    }

    private function formatStatementList(
        string $parent,
        StatementListInterface $statements,
        int $indent
    ): string {
        $padding = static::createPadding($indent);
        $key = sprintf('%s_Statements_%d', $parent, spl_object_id($statements));
        $result = $padding . sprintf(
            '%1$s [label="Statements"]',
            $key
        ) . PHP_EOL;

        $numStatements = 0;

        foreach ($statements as $statement) {
            $result .= rtrim($this->formatValue($key, $statement, $indent + 1)) . PHP_EOL;
            $numStatements++;
        }

        $result .= $padding . sprintf('"%s" -> "%s"', $parent, $key) . PHP_EOL;

        if ($numStatements === 0) {
            return PHP_EOL;
        }

        return rtrim($result) . PHP_EOL;
    }

    /**
     * Format any type of value.
     *
     * @param string $parent
     * @param mixed  $nodeValue
     * @param int    $indent
     *
     * @return string
     */
    private function formatValue(string $parent, $nodeValue, int $indent): string
    {
        if ($nodeValue instanceof NodeInterface) {
            return rtrim($this->__invoke($parent, $nodeValue, $indent)) . PHP_EOL;
        }

        if ($nodeValue instanceof StatementInterface) {
            return rtrim($this->formatStatement($parent, $nodeValue, $indent)) . PHP_EOL;
        }

        if ($nodeValue instanceof StatementListInterface) {
            return rtrim($this->formatStatementList($parent, $nodeValue, $indent)) . PHP_EOL;
        }

        $padding = self::createPadding($indent);
        $key = uniqid();

        if (is_array($nodeValue)) {
            if (count($nodeValue) === 0) {
                return PHP_EOL;
            }

            $result = '';

            foreach ($nodeValue as $index => $item) {
                $itemKey = sprintf('%s_%s', $key, (string) $index);
                $result .= $padding . sprintf(
                    '"%s" [shape=circle, label="%s"]',
                    $itemKey,
                    (string)$index
                ) . PHP_EOL;
                $result .= $this->formatValue($itemKey, $item, $indent) . PHP_EOL;
                $result .= $padding . sprintf(
                    '"%s" -> "%s"',
                    $parent,
                    $itemKey
                );
            }

            return rtrim($result) . PHP_EOL;
        }

        return (
            $padding
            . sprintf(
                '"%s" [label="%s" shape=record]',
                $key,
                preg_quote(
                    json_encode(
                        $nodeValue,
                        JSON_HEX_QUOT
                        | JSON_PRESERVE_ZERO_FRACTION
                    ) ?: '',
                    '"'
                )
            )
            . PHP_EOL
            . sprintf(
                '"%s" -> "%s"',
                $parent,
                $key
            )
            . PHP_EOL
        );
    }
};

return function (StatementListInterface $statements) use ($nodeFormatter): void {
    $result = 'digraph AST {' . PHP_EOL;

    foreach ($statements as $statement) {
        if ($statement === null) {
            continue;
        }

        $key = sprintf('Statement_%d', spl_object_id($statement));

        $result .= sprintf("\tsubgraph %s {\n\t\tlabel=\"Statement\"", $key) . PHP_EOL;

        foreach ($statement as $node) {
            if ($node === null) {
                continue;
            }

            $result .= rtrim($nodeFormatter(null, $node, 2)) . PHP_EOL;
        }

        $result .= "\t}" . PHP_EOL;
    }

    $result .= '}' . PHP_EOL;

    fwrite(STDOUT, $result);
};
