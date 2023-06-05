<?php

namespace Chypriote\UniqueNames\Tests;

use Chypriote\UniqueNames\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    /** @test */
    public function generator_always_gives_string_without_blanks()
    {
        $generator = new Generator();
        $name = $generator->generate();

        $this->assertIsString($name);
        $this->assertStringNotContainsString(' ', $name);
    }

    /** @test */
    public function dictionaries_can_be_set()
    {
        $generator = new Generator();
        $dictionaries = ['adjectives', 'animals', 'colors'];
        $generator->setDictionaries($dictionaries);

        $this->assertSame($dictionaries, $generator->getDictionaries());
    }

    /** @test */
    public function dictionaries_can_be_added()
    {
        $generator = new Generator();
        $generator->setDictionaries([]);

        $generator->addDictionary('colors');
        $this->assertSame(['colors'], $generator->getDictionaries());

        $generator->addDictionary('animals');
        $this->assertSame(['colors', 'animals'], $generator->getDictionaries());
    }
}
