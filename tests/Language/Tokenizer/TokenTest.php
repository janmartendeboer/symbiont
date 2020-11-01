<?php

/**
 * This file is part of the Symbiont package.
 *
 * (c) Jan-Marten de Boer <symbiont@janmarten.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symbiont\Test\Language\Tokenizer;

use PHPUnit\Framework\TestCase;
use Symbiont\Language\Tokenizer\Context\TokenContextInterface;
use Symbiont\Language\Tokenizer\Token;

/**
 * @coversDefaultClass \Symbiont\Language\Tokenizer\Token
 */
class TokenTest extends TestCase
{
    /**
     * @return array<int, array<int, string|null>>
     */
    public function valueProvider(): array
    {
        return [
            ['T_END_PROGRAM', null],
            ['T_END_STATEMENT', ';'],
            ['T_NUMBER', '1.12e-16']
        ];
    }

    /**
     * @dataProvider valueProvider
     *
     * @param string $name
     * @param string|null $value
     *
     * @covers ::__construct
     */
    public function testConstructor(string $name, ?string $value): void
    {
        static::assertInstanceOf(Token::class, new Token($name, $value));
    }

    /**
     * @dataProvider valueProvider
     *
     * @param string $name
     * @param string|null $value
     *
     * @covers ::jsonSerialize
     * @covers ::getName
     * @covers ::getValue
     * @covers ::__toString
     */
    public function testValues(string $name, ?string $value): void
    {
        $subject = new Token($name, $value);

        static::assertEquals(
            [
                'name' => $name,
                'value' => $value
            ],
            $subject->jsonSerialize(),
            'A JSON serialized Token must expose its name and value.'
        );

        static::assertEquals(
            $name,
            $subject->getName(),
            'Name must be unchanged from input value.'
        );

        static::assertEquals(
            $value,
            $subject->getValue(),
            'Value must be unchanged from input value.'
        );

        static::assertEquals(
            $name,
            $subject->__toString(),
            'String representation of Token must equal Token name.'
        );
    }

    /**
     * @covers ::getContext
     * @covers ::withContext
     */
    public function testContext(): void
    {
        $subject = new Token('T_TEST');

        static::assertNull(
            $subject->getContext(),
            'A Token has no context on construction.'
        );

        $context     = $this->createMock(TokenContextInterface::class);
        $withContext = $subject->withContext($context);

        static::assertNotSame(
            $subject,
            $withContext,
            'A Token with context must result in a new Token.'
        );

        static::assertSame(
            $context,
            $withContext->getContext(),
            'A Token with a context must return the same context.'
        );
    }
}
