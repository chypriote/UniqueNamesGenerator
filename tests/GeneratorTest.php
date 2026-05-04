<?php

namespace Chypriote\UniqueNames\Tests;

use Chypriote\UniqueNames\Generator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

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

    /** @test */
    public function generator_can_use_a_custom_separator()
    {
        $generator = new Generator();
        $generator
            ->setDictionaries(['colors', 'animals'])
            ->setSeparator('-');

        $parts = explode('-', $generator->generate());

        $this->assertCount(2, $parts);
        $this->assertNotEmpty($parts[0]);
        $this->assertNotEmpty($parts[1]);
    }

    /** @test */
    public function generator_can_generate_names_with_a_custom_length()
    {
        $generator = new Generator();
        $generator
            ->setDictionaries(['colors', 'adjectives', 'animals'])
            ->setLength(3)
            ->setSeparator('-');

        $this->assertCount(3, explode('-', $generator->generate()));
    }

    /** @test */
    public function generator_can_generate_a_single_word_name()
    {
        $generator = new Generator();
        $generator
            ->setDictionaries(['colors'])
            ->setLength(1)
            ->setSeparator('-');

        $this->assertStringNotContainsString('-', $generator->generate());
    }

    /** @test */
    public function integer_seed_makes_generation_predictable()
    {
        $firstGenerator = new Generator();
        $firstGenerator->setSeed(1234);
        $firstSequence = [$firstGenerator->generate(), $firstGenerator->generate()];

        $secondGenerator = new Generator();
        $secondGenerator->setSeed(1234);
        $secondSequence = [$secondGenerator->generate(), $secondGenerator->generate()];

        $this->assertSame($firstSequence, $secondSequence);
    }

    /** @test */
    public function string_seed_makes_generation_predictable()
    {
        $firstGenerator = new Generator();
        $firstGenerator->setSeed('unique-name');
        $firstSequence = [$firstGenerator->generate(), $firstGenerator->generate()];

        $secondGenerator = new Generator();
        $secondGenerator->setSeedFromString('unique-name');
        $secondSequence = [$secondGenerator->generate(), $secondGenerator->generate()];

        $this->assertSame($firstSequence, $secondSequence);
    }

    /** @test */
    public function generator_can_shuffle_dictionaries()
    {
        $generator = new Generator();
        $generator
            ->setDictionaries(['colors', 'adjectives', 'animals'])
            ->setLength(3)
            ->setSeed(1)
            ->setShuffle(true);

        $generator->generate();

        $this->assertSame(['colors', 'animals', 'adjectives'], $generator->getDictionaries());
    }

    /** @test */
    public function generator_uses_words_from_configured_dictionaries()
    {
        $generator = new Generator();
        $generator
            ->setDictionaries(['colors', 'animals'])
            ->setSeparator('-')
            ->setSeed(1234);

        [$color, $animal] = explode('-', $generator->generate());

        $this->assertContains(lcfirst($color), include __DIR__.'/../src/dictionaries/colors.php');
        $this->assertContains(lcfirst($animal), include __DIR__.'/../src/dictionaries/animals.php');
    }

    /** @test */
    public function available_dictionaries_have_matching_non_empty_resources()
    {
        foreach (Generator::AVAILABLE_DICTIONARIES as $dictionary) {
            $path = __DIR__.'/../src/dictionaries/'.$dictionary.'.php';

            $this->assertFileExists($path);

            $words = include $path;
            $this->assertIsArray($words);
            $this->assertNotEmpty($words);
            $this->assertContainsOnly('string', $words);
            $this->assertNotContains('', $words);
        }
    }

    /** @test */
    public function generator_requires_at_least_one_dictionary()
    {
        $generator = new Generator();
        $generator->setDictionaries([]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot find any dictionary');

        $generator->generate();
    }

    /** @test */
    public function generator_rejects_lengths_less_than_one()
    {
        $generator = new Generator();
        $generator->setLength(0);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid length provided');

        $generator->generate();
    }

    /** @test */
    public function generator_rejects_length_bigger_than_number_of_dictionaries()
    {
        $generator = new Generator();
        $generator
            ->setDictionaries(['colors'])
            ->setLength(2);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The length cannot be bigger than the number of dictionaries');

        $generator->generate();
    }

    /** @test */
    public function generator_rejects_unknown_dictionaries()
    {
        $generator = new Generator();
        $generator->setDictionaries(['colors', 'unknown']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The dictionary unknown could not be found');

        $generator->generate();
    }
}
