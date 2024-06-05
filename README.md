Bible Data Provider
===================

Provide texts from the bible by reference, loaded from local files.

This library works well to provide the text for verse references that were found using [stevenbuehner/bible-verse-bundle](https://github.com/stevenbuehner/BibleVerseBundle).

## Installation

Install the library with composer:

```bash
composer require devdot/bible-data-provider
```

Then provide your own data files. Because of copyrights, this package does **not** deliver actual bible texts. See further at [Building your own data](#building-your-own-data). If you need a hint on how to generate your own bible texts, you may contact me.

## Basic Usage

```php
use Devdot\Bible\Text\BibleTextProvider;
use Devdot\Bible\Text\Loaders\BiblesLoader;

// build a loader that points to the root file
//   second argument defaults to 'bibles.php'
$loader = new BiblesLoader('dir/to/data');

// build the provider with the given loader
//   additionally, you may set the default translation
$provider = new BibleTextProvider($loader, 'NIV');

// find out about the available bibles
foreach($provider->getAvailableBibles() as $bible) {
    echo $bible->abbreviation;
}

// retrieve some text
$bible = $provider->getBible('NIV');
echo $bible->books->get('JHN')->chapters->get(3)->verses->get(16)->getText();
echo $bible->books['JHN']->chapters[3]->verses[16]; // using array access
```

You may quickly load the verses for a reference (as provided by stevenbuehner/bible-verse-bundle):

```php
use StevenBuehner\BibleVerseBundle\Service\BibleVerseService;

// find the reference from a string using stevenbuehner/bible-verse-bundle
$reference = (new BibleVerseService())->stringToBibleVerse('Genesis 1:1-20')[0];

// load the text
$verses = $provider->getVersesFromReference($reference, 'NIV');

// display them
foreach($verses as $verse) {
    echo $verse->getText();
}
```

## Building your own data

As stated above, I cannot provide actual bible texts in this package for legal reasons. You may build your own bible data from public sources and then provide it to your PHP application using this package.

An example of the data format can be seen in [tests/data](tests/data).

### Root file (bibles.php)

This file is supplied to `BiblesLoader`. It is only loaded when any bible is accessed.

```php
<?php
// bibles.php

return [
    'NIV' => [ // abbreviations are used as keys to identify the bibles when loading
        'id' => 'niv', // id has no technical relevance
        'abbreviation' => 'NIV',
        'name' => 'New International Version',
        'description' => 'New International Version',
        'copyright' => '1979, 1984, 2011 by Biblica, Inc.',
        'language' => 'eng',
        'updated_at' => '2024-06-05 11:10:00',
        'books' => 'niv/books.php', // relative link from this file to the books list for NIV
    ],
    'VUL' => [
        'id' => 'vul',
        'abbreviation' => 'VUL',
        'name' => 'Biblia Sacra Vulgata ',
        'description' => 'Vulgate',
        'copyright' => 'Biblia Sacra Iuxta Vulgatam Versionem',
        'language' => 'lat',
        'updated_at' => '2024-06-05 11:10:00',
        'books' => 'vul/books.php',
    ],
];
```

See [tests/data/test-bibles.php](tests/data/test-bibles.php) for a working example.

### Books list (books.php)

This file is only loaded when a book of a given bible is accessed. Its relative location must be defined in `bibles.php`.

```php
<?php
// books.php

return [
    // map the book ID (key) to the relative book file (value)
    'GEN' => '0-gen.php',
    'EXO' => 'exodus.php',
    // ...
];
```

See [tests/data/tes/books.php](tests/data/tes/books.php) for a working example.

To find out about book IDs you may look at the [BookIdResolver Helper](src/Helper/BookIdResolver.php). You may also introduce your own book ID system.

### Book file

These files are only loaded when the corresponding book of a given bible is accessed. Its relative location must be defined in `books.php`.

```php
<?php
// book file example: 0-gen.php for NIV
return [
    'id' => 'GEN', // same as the key in books.php
    'abbreviation' => 'Gen.',
    'name' => 'Genesis',
    'name_long' => 'Genesis',
    'chapters' => [
        1 => [ // chapter number as key
            // verses as number (key) => text (value)
            1 => 'In the beginning God created the heavens and the earth.',
            2 => 'Now the earth was formless and empty, darkness was over the surface of the deep, and the Spirit of God was hovering over the waters.',
            // ...
        ],
        2 => [
            1 => 'Thus the heavens and the earth were completed in all their vast array.',
            // ...
        ],
        // ...
    ],
];
```

Your bible texts may have multiple segments. In that case, the verse-text is not a `string` but an array of strings (`array<string>`):

```php
<?php
// book file example: 74-2es.php for VUL

return [
    'id' => '2ES',
    'abbreviation' => 'IV Esr',
    'name' => 'LIBER EZRAE IIII',
    'name_long' => 'LIBER EZRAE QUARTUS',
    'chapters' => [
        // ...
        7 => [
            // ...
            35 => 'et opus subsequetur et merces ostendetur, et iustitiae vigilabunt et iniustitiae non dormibunt.',
            36 => [ // verse 36 is not a string, but an array of strings
                'a' => 'Et apparebit lacus tormenti',
                'b' => 'et contra illum erit locus requietionis, et clibanus gehennae ostendetur et contra eam iucunditatis paradisus.',
            ],
            37 => 'Et dicet tunc Altissimus ad excitatas gentes: Videte et intellegite quem negastis vel cui non servistis vel cuius diligentias sprevistis.',
            // ...
        ],
        // ...
    ],
]
```

By default, the segments are merged into a single verse string. You may access these segments individually through the arguments of `Verse->getText()`.

See [tests/data/tes/0-gen.php](tests/data/tes/0-gen.php) for a working example.

## License

Bible Text Provider is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
