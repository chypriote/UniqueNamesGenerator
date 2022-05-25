# Unique Names Generator

![GitHub](https://img.shields.io/github/license/chypriote/UniqueNamesGenerator)
![Packagist Downloads](https://img.shields.io/packagist/dm/chypriote/unique-names-generator)

A package to create readable, meaningful, random strings from animal names, colors and adjectives.
> More than 50,000,000 name combinations out of the box

## Why Unique name generator?

I needed a way to generate unique and rememberable names a la giphy (i.e BlueGiantHorse) and was unable to find one in the wild. Moreover, I needed a way to obtain the same result from a specific string in certain cases.


## Installation

Just require the package through composer:

``` bash
composer require chypriote/unique-names-generator
```

## Usage

Instanciate a new Generator and call the generate function in order to get a random adjective + animal combo.

``` php
$generator = new Generator();
$name = $generator->generate();
echo $name; // --> PlannedRhinoceros
```

Subsequent calls will give you different results:

``` php
$generator = new Generator();
echo $generator->generate(); // --> TypicalWolf
echo $generator->generate(); // --> QualifiedKoala
echo $generator->generate(); // --> ConfusedCarp
echo $generator->generate(); // --> DepressedCanid
```

You can configure the list of dictionaries used:

``` php
$generator = new Generator();
$generator->setDictionaries(['colors', 'animals']);
echo $generator->generate(); // --> OrangeWolverine
echo $generator->generate(); // --> MagentaMarten
echo $generator->generate(); // --> RedKingfisher
echo $generator->generate(); // --> AquaPigeon
```

Available dictionaries are:
* **adjectives**: List of 1500+ adjectives (default)
* **animals**: List of 300+ animals (default)
* **colors**: List of 50+ colors
* **countries**: List of countries
* **names**: List of ~5000 names
* **star wars**: List of Star Wars characters
* **languages**: List of 100+ languages

In order to use more than 2 dictionnaries, you need to set the `length` parameter of the generator:

``` php
$generator = new Generator();
$generator->setDictionaries(['colors', 'adjectives', 'animals'])->setLength(3);
echo $generator->generate(); // --> AzureLinguisticMongoose
echo $generator->generate(); // --> TurquoiseCanadianPuffin
echo $generator->generate(); // --> EmeraldWideFirefly
echo $generator->generate(); // --> PinkBloodyGoldfish
```

You can also get even more different results by enabling the shuffle parameter. This will shuffle the dictionaries on each call of the generator:

``` php
$generator = new Generator();
$generator
        ->setDictionaries(['colors', 'adjectives', 'animals'])
        ->setLength(3)
        ->setShuffle(true);
echo $generator->generate(); // --> CyanFiercePig
echo $generator->generate(); // --> NervousAzureUnicorn
echo $generator->generate(); // --> CoralLizardFellow
echo $generator->generate(); // --> EducationalKingfisherJade
```

You can enter a seed in order to get predictable results for repeated calls:

``` php
$generator = new Generator();
$generator->setSeed(1234);

echo $generator->generate(); // --> GreatKangaroo
echo $generator->generate(); // --> ParentalCentipede

$generator = new Generator();
$generator->setSeed(1234);
echo $generator->generate(); // --> GreatKangaroo
echo $generator->generate(); // --> ParentalCentipede
```

Seeds can be either an `integer` or a `string`.



## TODO-list

* Possibility to add custom dictionaries
* Unit tests


## FAQ

**Q: Can this package do *feature*?**

A: I created this package out of a need for a personal project. If you have some suggestions feel free to open an issue
or a PR! 
