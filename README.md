# A PHP library for creating Lucene search queries

## Usage
You can create three types of queries:
1. terms matching documents that contain a single word
2. phrases matching documents that contain a sequence of words
3. complex queries containing one or more sub-queries of any type

### Terms
To create a query matching documents that contain a single word, e. g. "word", just instantiate a new term with the word
as constructor argument:

#### Creating a term

```php
<?php
$term = new \LuceneQuery\Term('word');
```
The string representation of the query will be:

> word

#### Operators

You can add operators to define, if the word is required, prohibited or optional.

To require the word necessarily, just call required():

```php
<?php
$term = new \LuceneQuery\Term('word');
$term->required();
```

The string representation of the query will be:

> +word

To prohibit the word, just call prohibited():

```php
<?php
$term = new \LuceneQuery\Term('word');
$term->prohibited();
```

The string representation of the query will be:

> -word

If neither required() nor prohibited() is called, the word is optional. You can make this explicit, but you don't have
to, by calling optional():

```php
<?php
$term = new \LuceneQuery\Term('word');
$term->optional();
```

The string representation of the query will be:

> word

#### Fuzziness

You can do a fuzzy search term by calling fuzzify():

```php
<?php
$term = new \LuceneQuery\Term('word');
$term->fuzzify();
```

The string representation of the query will be:

> word~

The fuzzy search is based on Damerau-Levenshtein Distance, that is the number of single character edits allowed to reach
the search term. By using the optional parameter, you can define that distance:

```php
<?php
$term = new \LuceneQuery\Term('word');
$term->fuzzify(1);
```
The string representation of the query will be:

> word~1

The query will also match terms like "Ford", that can be reached by edit a single character of the search term. The
default value of fuzzify() is 2, so that also words like "nerd" will be matched. By using 0 as parameter, fuzzy seach
is disabled, what is the same as just don't calling fuzzify(). Allowed values are 0, 1 and 2.

### Phrases
To create a query matching documents that contain a sequence of words, e. g. "Lucene search", just instantiate a new
phrase with the sequence as constructor argument:

#### Creating a phrase

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
```
The string representation of the query will be:

> "Lucene query"

#### Operators

You can add operators to phrases in the same manner as to terms.

To require the phrase necessarily, just call required():

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
$phrase->required();
```

The string representation of the query will be:

> +"Lucene query"

To prohibit the phrase, just call prohibited():

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
$phrase->prohibited();
```

The string representation of the query will be:

> -"Lucene query"

If neither required() nor prohibited() is called, the phrase is optional. You can make this explicit, but you don't have
to, by calling optional():

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
$phrase->optional();
```

The string representation of the query will be:

> "Lucene query"