<?php

namespace Chypriote\UniqueNames;

use RuntimeException;

class Generator
{
    public const DICTIONARY_ADJECTIVES = 'adjectives';
    public const DICTIONARY_ANIMALS = 'animals';
    public const DICTIONARY_COLORS = 'colors';
    public const DICTIONARY_COUNTRIES = 'countries';
    public const DICTIONARY_NAMES = 'names';
    public const DICTIONARY_STAR_WARS = 'star-wars';
    public const DICTIONARY_LANGUAGES = 'languages';

    public const AVAILABLE_DICTIONARIES = [
        self::DICTIONARY_ADJECTIVES,
        self::DICTIONARY_ANIMALS,
        self::DICTIONARY_COLORS,
        self::DICTIONARY_COUNTRIES,
        self::DICTIONARY_NAMES,
        self::DICTIONARY_STAR_WARS,
        self::DICTIONARY_LANGUAGES,
    ];

    private array $dictionaries = ['adjectives', 'animals'];

    private array $resources = [];

    private ?string $separator = null;

    private int $length = 2;

    private ?int $seed = null;

    private bool $shuffle = false;

    public function generate(): string
    {
        $this->validateConfig();

        if ($this->shuffle) {shuffle($this->dictionaries);}
        $this->loadResources();

        return array_reduce(array_slice($this->resources, 0, $this->length), function (string $acc, array $curr) {
            $rnd = mt_rand(0, count($curr) - 1);
            $word = ucfirst($curr[$rnd]);

            return $acc !== '' ? $acc.$this->separator.$word : $word;
        }, '');
    }

    private function validateConfig(): void
    {
        $numberOfDictionaries = count($this->dictionaries);

        if (!$numberOfDictionaries) {
            throw new RuntimeException('Cannot find any dictionary. Please provide at least one, or leave the "dictionary" field empty in the config object');
        }

        if ($this->length <= 0) {
            throw new RuntimeException('Invalid length provided');
        }

        if ($this->length > $numberOfDictionaries) {
            throw new RuntimeException(
                sprintf('The length cannot be bigger than the number of dictionaries.\n Length provided: %d. Number of dictionaries provided: %d', $this->length, $numberOfDictionaries)
            );
        }

        foreach ($this->dictionaries as $dictionary) {
            if (!in_array($dictionary, self::AVAILABLE_DICTIONARIES, true)) {
                throw new RuntimeException(
                    sprintf('The dictionary %s could not be found. Available dictionaries: %s', $dictionary, json_encode(self::AVAILABLE_DICTIONARIES, JSON_THROW_ON_ERROR))
                );
            }
        }
    }

    private function loadResources(): void
    {
        foreach ($this->dictionaries as $key => $dictionary) {
            $this->resources[$key] = include __DIR__.'/dictionaries/'.$dictionary.'.php';
        }
    }

    public function setDictionaries(array $dictionaries): self
    {
        $this->dictionaries = $dictionaries;

        return $this;
    }

    public function addDictionary(string $dictionary): self
    {
        $this->dictionaries[] = $dictionary;

        return $this;
    }

    public function setSeparator(?string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function setShuffle(bool $shuffle): self
    {
        $this->shuffle = $shuffle;

        return $this;
    }

    public function setSeed($seed): self
    {
        if (is_string($seed)) {
            return $this->setSeedFromString($seed);
        }

        $this->seed = $seed;
        mt_srand($this->seed);

        return $this;
    }

    public function setSeedFromString(string $seed): self
    {
        $this->seed = crc32($seed);
        mt_srand($this->seed);

        return $this;
    }
}