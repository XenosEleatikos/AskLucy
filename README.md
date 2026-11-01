[![CI](https://github.com/XenosEleatikos/AskLucy/actions/workflows/ci.yml/badge.svg)](https://github.com/XenosEleatikos/AskLucy/actions/workflows/ci.yml)
[![Packagist Version](https://img.shields.io/packagist/v/xenos/asklucy)](https://packagist.org/packages/xenos/asklucy)
[![PHP Version](https://img.shields.io/packagist/dependency-v/xenos/asklucy/php)](https://packagist.org/packages/xenos/asklucy)
[![License](https://img.shields.io/packagist/l/xenos/asklucy)](LICENSE)

# AskLucy – A PHP Library for Creating Lucene Search Queries
This project contains an easy to use PHP library for creating Lucene search queries.

## Contents
- [Requirements](#requirements)
- [Installation](#installation)
    - [Install with Git](#install-with-git)
    - [Install with Composer](#install-with-composer)
- [Usage](#usage)
    - [Creating Clauses](#creating-clauses)
        - [Creating a Term](#creating-a-term)
        - [Creating a Phrase](#creating-a-phrase)
        - [Creating a Range](#creating-a-range)
        - [Creating a Complex Query](#creating-a-complex-query)
    - [Fields](#fields)
        - [Setting a Field to a Term](#setting-a-field-to-a-term)
        - [Setting a Field to a Phrase](#setting-a-field-to-a-phrase)
        - [Setting a Field to a Range](#setting-a-field-to-a-range)
        - [Setting Fields in Complex Queries](#setting-fields-in-complex-queries)
    - [Operators](#operators)
        - [Setting an Operator to a Term](#setting-an-operator-to-a-term)
        - [Setting an Operator to a Phrase](#setting-an-operator-to-a-phrase)
        - [Setting an Operator to a Range](#setting-an-operator-to-a-range)
        - [Setting Operators in Complex Queries](#setting-operators-in-complex-queries)
    - [Relevance Boosting](#relevance-boosting)
    - [Fuzziness](#fuzziness)
    - [Proximity Search](#proximity-search)
    - [Range Search](#range-search)
    - [Wildcard Search](#wildcard-search)
    - [Regular Expression Search](#regular-expression-search)
    - [Boolean Operators](#boolean-operators)
    - [Escaping](#escaping)
- [Upgrading from 2.x](#upgrading-from-2x)
- [Development](#development)
- [License](#license)

## Requirements
PHP 8.3 or higher. Version 2.x supports PHP 7.1 and higher.

## Installation
### Install with Git
```bash
git clone https://github.com/XenosEleatikos/AskLucy.git
```

### Install with Composer
This project is available at [Packagist](https://packagist.org): https://packagist.org/packages/xenos/asklucy

You can install it with the following command:
```bash
composer require xenos/asklucy
```

## Usage
This library contains classes providing a ``__toString()`` method. By casting their instances with the ``(string)``
operator you will get clauses ready to use for Lucene search engine. For more details about the syntax of Lucene search
queries you may [read the official docs](https://lucene.apache.org/core/10_0_0/queryparser/org/apache/lucene/queryparser/classic/package-summary.html). But the following
sections will give you all necessary information about building
such queries.

A query to Lucene search engine consists of one or more clauses for matching documents. There are six types of clauses:
1. Terms matching documents that contain a single word.
2. Phrases matching documents that contain a sequence of words.
3. Ranges matching documents that contain a value between a lower and an upper bound.
4. Wildcards matching documents that contain words following a pattern.
5. Regular expressions matching documents that contain words following a regular expression.
6. Complex queries containing one or more sub-clauses of any type.

### Creating Clauses
#### Creating a Term
To create a query matching documents that contain a single word, e. g. "word", just build a new ```Term``` as follows:

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('word');
```
The string representation of the query will be:

> word

#### Creating a Phrase
To create a clause matching documents that contain a sequence of words, e. g. "Lucene search", you can instantiate a new
```Phrase``` with the following snippet:

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::phrase('Lucene query');
```

The string representation of the query will be:

> "Lucene query"

#### Creating a Range
To create a range matching documents that contain a value between a lower and an upper bound, instantiate a new
```Range``` with the bounds:

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('alpha', 'omega');
```

The string representation of the query will be:

> [alpha TO omega]

#### Creating a Complex Query
To create a complex query containing one or more clauses of any type, instantiate a new ```Query``` and add clauses:

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::query()
    ->add(Lucene::term('word'))
    ->add(Lucene::phrase('Lucene query'));
```

The string representation of the query will be:

> word "Lucene query"

### Fields
For all types of clauses you can specify a field to search in by calling the method ```setField()``` or by adding an
additional parameter to the factory method.

#### Setting a Field to a Term
To search for documents containing "Lucene" in the (field named) "title", use the following snippet:

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('Lucene');
$term->setField('title');
```

As a shortcut you may also set the field directly by adding a second parameter to the factory:

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('Lucene', 'title');
```

Both lead to the same result:

> title:Lucene

#### Setting a Field to a Phrase
You can specify a field to search in by calling the method ```setField()```...

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::phrase('Search Engine');
$phrase->setField('title');
```

... or you can set the field by adding a second parameter to the factory:

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::phrase('Search Engine', 'title');
```

The result is the same:

> title:"Search Engine"

#### Setting a Field to a Range
Specify a field to search the value range in by calling the method ```setField()```...

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('Anna', 'Doro');
$range->setField('name');
```

... or set the field by adding a third parameter to the factory:

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('Anna', 'Doro', 'name');
```

The result is the same:

> name:[Anna TO Doro]

#### Setting Fields in Complex Queries
As before you can specify a search field by calling the method ```setField()```...

```php
<?php
use AskLucy\Lucene;

$query = Lucene::query()
    ->add(Lucene::term('Lucene'))
    ->add(Lucene::term('Apache'));
$query->setField('title');
```

... or by passing a parameter to the factory:

```php
<?php
use AskLucy\Lucene;

$query = Lucene::query('title')
    ->add(Lucene::term('Lucene'))
    ->add(Lucene::term('Apache'));
```

In both cases the string representation of the query will be:

> title:(Lucene Apache)

Note, that the brackets are set automatically, if more than one sub-clauses were set. If you want to specify a field
just for a certain sub-clause, you may do this:

```php
<?php
use AskLucy\Lucene;

$query = Lucene::query()
    ->add(Lucene::term('Lucene', 'title'))
    ->add(Lucene::term('Apache'));
```

The result will be:

> title:Lucene Apache

### Operators
Note, that ```Query::shouldHave()```, ```Query::mustHave()``` and ```Query::mustNotHave()``` set the operator of the given
clause object itself. If you use the same clause object in several queries, create a copy for each of them.

For all types of clauses you can add operators to define, if matching is required, prohibited or optional. Just call
```required()```, ```prohibited()```, or ```optional()```. Note, that a clause is optional by default, so that calling
```optional()``` is optional. But it can be used to override an operator set before.

#### Setting an Operator to a Term
To require the word "PHP" necessarily, use the following snippet...

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('PHP')
    ->required();
```

... and get:

> +PHP

To prohibit the word "Java", do this...

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('Java')
    ->prohibited();
```

... and you'll get that:

> -Java

#### Setting an Operator to a Phrase
You can add operators to phrases in the same manner as to terms.

Require a phrase necessarily...

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::phrase('Lucene query')
    ->required();
```

... and get the string representation:

> +"Lucene query"

Prohibit the phrase...

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::phrase('Java development')
    ->prohibited();
```

... and get:

> -"Java development"

#### Setting an Operator to a Range
Adding operators to ranges works in the same way as adding them to the other kinds of clauses.

Require a value of a range necessarily...

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('Anna', 'Doro')
    ->required();
```

... and get the string representation:

> +[Anna TO Doro]

Prohibit a value of the range...

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('Anna', 'Doro')
    ->prohibited();
```

... and get:

> -[Anna TO Doro]

#### Setting Operators in Complex Queries
You can add operators to complex queries right as to terms and phrases:

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::query()
    ->add(Lucene::term('Lucene'))
    ->add(Lucene::phrase('search query'))
    ->required();
```

The query will match all documents containing necessarily "Lucene" or "search query" (or both). The string representation will
be:

> +(Lucene "search query")

Instead of creating sub-clauses, setting operators to them and finally adding them to a complex query, you can use
```Query::shouldHave()```, ```Query::mustHave()``` or ```Query::mustNotHave()```, what automatically sets the "optional",
"required" or "prohibited" operator to the given sub-queries.

```php
<?php
use AskLucy\Lucene;

$query = Lucene::query()
    ->shouldHave(Lucene::term('word'))
    ->mustHave(Lucene::phrase('Lucene query'))
    ->mustNotHave(Lucene::phrase('Java development'));
```

The string representation of the query will be:

> word +"Lucene query" -"Java development"

### Relevance Boosting
You can add a "boost" to a clause of any type, to make it more relevant. Just call ```boost()``` with a boost factor
greater than zero.

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('Lucene');
$term->boost(2.5);

$phrase = Lucene::phrase('search engine')
    ->boost(2);

$query = Lucene::query()
    ->add(Lucene::term('Apache'))
    ->add($term)
    ->add($phrase);
```

The result will be:

> Apache Lucene^2.5 "search engine"^2

### Fuzziness

You can do a fuzzy search term by calling ```Term::fuzzify()```:

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('word')
    ->fuzzify();
```

The string representation of the query will be:

> word~

The fuzzy search is based on Damerau-Levenshtein Distance, that is the number of single character edits allowed to reach
the search term. By using the optional parameter, you can define that distance:

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('word')
    ->fuzzify(1);
```

The string representation of the query will be:

> word~1

The query will also match terms like "Ford", that can be reached by edit a single character of the search term. The
default value of ```fuzzify()``` is 2, so that also words like "nerd" will be matched. By using 0 as parameter, fuzzy search
is disabled, what is the same as just don't calling ```fuzzify()```. Allowed values are 0, 1 and 2.

### Proximity Search
You can specify a maximum distance to find terms, that are near each other in a document. For example, if you search for
the terms "search" and "term" within five words, create the following phrase:  

```php
<?php
use AskLucy\Lucene;

$phrase = Lucene::phrase('search term')
    ->setProximity(5);
```

The string representation of the query will be:

> "search term"~5

The proximity 0 means exact matching and, as the Lucene default value, must not be rendered. The proximity 1 would
allow interchanging words, "term search".

### Range Search
Ranges matching documents that contain a value between a lower and an upper bound. They can be inclusive or exclusive of
the bounds.

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('Alpha', 'Omega')
    ->inclusive();
```

This clause matches documents that contain values between "Alpha" and "Omega" inclusive "Alpha" and "Omega". The clause
will be rendered with square brackets.

> [Alpha TO Omega]

Note, that ranges are inclusive by default, so that you don't have to call ```Range::inclusive()```.
You can make the range exclusive of the bounds by calling ```Range::exclusive()```:

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('Alpha', 'Omega')
    ->exclusive();
```

The clause will be rendered with curly brackets:

> {Alpha TO Omega}

Both bounds can be set independently of each other:

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('Alpha', 'Omega')
    ->exclusiveFrom();  // or inclusiveFrom(), exclusiveTo(), inclusiveTo()
```

> {Alpha TO Omega]

A bound given as ```null``` is open and rendered as an asterisk, e. g. for all documents with a price of at least 100:

```php
<?php
use AskLucy\Lucene;

$range = Lucene::range('100', null, 'price');
```

> price:[100 TO *]

### Wildcard Search
Use ```Lucene::wildcard()``` to search for terms containing the wildcards ```?``` (exactly one character) and ```*```
(zero or more characters). All other special characters are escaped.

```php
<?php
use AskLucy\Lucene;

$wildcard = Lucene::wildcard('te?t*', 'title');
```

> title:te?t*

Note, that Lucene may disallow wildcards at the beginning of a term, depending on its configuration.

### Regular Expression Search
```Lucene::regex()``` renders a regular expression between slashes. Slashes in the pattern are escaped automatically.

```php
<?php
use AskLucy\Lucene;

$regex = Lucene::regex('[mb]oat', 'title');
```

> title:/[mb]oat/

The syntax of the pattern depends on your search engine.

### Boolean Operators
Besides the prefix operators ```required()``` and ```prohibited()```, the clauses of a complex query can be connected by
the boolean operators ```AND```, ```OR``` and ```NOT```. Use ```Query::and()```, ```Query::or()``` and
```Query::not()``` instead of ```Query::add()```:

```php
<?php
use AskLucy\Lucene;

$query = Lucene::query()
    ->add(Lucene::term('Lucene'))
    ->and(Lucene::query()
        ->add(Lucene::term('search'))
        ->or(Lucene::term('query'))
    )
    ->not(Lucene::term('Solr'));
```

Nested queries with more than one clause are enclosed in parentheses automatically:

> Lucene AND (search OR query) NOT Solr

Note, that Lucene's ```AND``` and ```OR``` are not boolean algebra: ```a AND b OR c``` means "```a``` and ```b``` are
required, ```c``` is optional". Use nested queries to group clauses explicitly.

For the first clause of a query, ```and()``` and ```or()``` have no effect, ```not()``` renders ```NOT``` (which matches
nothing on its own). ```NOT``` must not be followed by ```+``` or ```-```, so such a clause is put in parentheses.
Operators, field and boost of a query that contains a single decorated clause lead to parentheses as well, to keep the
query valid, e. g. ```+(-a)```.

### Escaping
Lucene's special characters ```+ - && || ! ( ) { } [ ] ^ " ~ * ? : \ /``` and the reserved words ```AND```, ```OR```,
```NOT``` and ```TO``` are escaped automatically in terms, phrases, ranges, wildcards and field names. So it is safe to
pass user input to these clauses, it can't change the structure of the query:

```php
<?php
use AskLucy\Lucene;

$term = Lucene::term('-secret', 'title');
```

> title:\-secret

Terms and wildcards must not be empty and must not contain whitespace (including the ideographic space). Use a phrase
for several words. Input must be valid UTF-8, otherwise an ```InvalidArgumentException``` is thrown.

Note the following limits:
- The pattern of a regular expression is passed through, except for slashes, which are escaped. It has the syntax of your
  search engine, so don't pass untrusted input to ```Lucene::regex()``` without validating it. A pattern must not end
  with a backslash; use ```[\\]``` to match a literal backslash.
- Bounds of ranges that contain whitespace or closing brackets (```]```, ```}```) are put in quotation marks, because
  Lucene can't read escaped brackets within a range. Such a bound must not end with a backslash, because Lucene would
  read it as an escaped quotation mark. An ```InvalidArgumentException``` is thrown in this case.
- In wildcards, ```*``` and ```?``` are always wildcards. There is no way to match them literally.
- To escape a string for your own use, call ```Lucene::escape()```. It escapes whitespace, too, so the result is always
  a single token.
- The library only escapes Lucene's syntax. Search engines built on top of Lucene may reserve further characters, for
  example ```<``` and ```>``` in Elasticsearch's ```query_string```.

## Upgrading from 2.x
Version 3.0 requires PHP 8.3 and contains the following breaking changes:

- Phrases are always rendered in quotation marks, also if they contain a single word.
- Empty terms and phrases throw an ```InvalidArgumentException``` instead of being rendered as an empty string.
- Terms and phrases are escaped. If you relied on passing Lucene syntax like ```te*t``` to ```Lucene::term()```, use
  ```Lucene::wildcard()```.
- A query with a field now encloses multiple clauses in parentheses: ```title:(a b)``` instead of ```title:a b```.
- ```Operator```, ```Fuzziness``` and the former ```RangeType``` are replaced by enums. The constants
  ```Operator::SYMBOL_*``` are removed, use the enum ```Operator``` and the new enum ```Conjunction```.
- ```InvalidArgumentException``` extends ```\InvalidArgumentException```.
- The ```Clause``` interface declares ```static``` as return type of its fluent methods. Own implementations must adapt.
- Removed: the public constants ```Query::CLAUSE_SEPARATOR``` and ```Phrase::TERM_SEPARATOR```, and the factory methods
  ```Fuzziness::distance0/1/2()```, ```Operator::optional/required/prohibited()``` and
  ```RangeType::inclusive/exclusive()```.
- ```Boost``` throws an exception for values that are not finite or not greater than zero (rendered as at least
  ```0.0000000001```).
- Regular expressions must not end with a backslash, and a query must not contain itself, directly or indirectly.
- All classes are final, the traits in ```AskLucy\Property``` were removed.
- Fluent methods return ```static```.

## Compatibility
The rendered queries were verified against the classic query parser of Apache Lucene 10.6: tens of thousands of randomly
generated queries, including adversarial input like special characters, reserved words and unicode, were parsed by Lucene
and compared with the structure that was meant. Solr and Elasticsearch use the same syntax, but may differ in details.

## Development
```bash
composer install
composer ci
```

See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License
AskLucy is released under the [MIT License](LICENSE).
