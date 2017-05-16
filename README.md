# A PHP library for creating Lucene search queries

## Usage
You can create three types of queries:
1. terms matching documents that contain a single word
2. phrases matching documents that contain a sequence of words
3. complex queries containing one or more sub-queries of any type

### Terms
#### Creating a term
To create a query matching documents that contain a single word, e. g. "word", just instantiate a new term with the word
as constructor argument:
```php
<?php
$term = new \LuceneQuery\Term('word');
```

The string representation of the query will be:

> word

#### Setting a field to the term
You can specify a field to search in by calling the method setField():

```php
<?php
$term = new \LuceneQuery\Term('Lucene');
$term->setField('searchEngine');
```

You can also set the field by adding a second constructor argument:

```php
<?php
$term = new \LuceneQuery\Term('Lucene', 'searchEngine');
```

Both lead to the same result:

> searchEngine:Lucene

#### Setting an operator to the term

You can add operators to define, if the word is required, prohibited or optional.

To require the word necessarily, just call required():

```php
<?php
$term = new \LuceneQuery\Term('word');
$term->required();
```

The string representation of the query will be:

> +word

To prohibit a word, e.g. "Java", just call prohibited():

```php
<?php
$term = new \LuceneQuery\Term('Java');
$term->prohibited();
```

The string representation of the query will be:

> -Java

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
#### Creating a phrase
To create a query matching documents that contain a sequence of words, e. g. "Lucene search", just instantiate a new
phrase with the sequence as constructor argument:

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
```
The string representation of the query will be:

> "Lucene query"

#### Setting an operator to the phrase

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
$phrase = new \LuceneQuery\Phrase('Java development');
$phrase->prohibited();
```

The string representation of the query will be:

> -"Java development"

If neither required() nor prohibited() is called, the phrase is optional. You can make this explicit, but you don't have
to, by calling optional():

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
$phrase->optional();
```

The string representation of the query will be:

> "Lucene query"

#### Setting a field to the phrase
You can specify a field to search in by calling the method setField():

```php
<?php
$phrase = new \LuceneQuery\Phrase('Search Engine');
$phrase->setField('title');
```

You can also set the field by adding a second constructor argument:

```php
<?php
$phrase = new \LuceneQuery\Phrase('Search Engine', 'title');
```

Both lead to the same result:

> title:"Search Engine"

#### Setting a required term proximity
You can specify a maximum distance to find terms, that are near each other in a document. For example, if you search for
the terms "search" and "term" within five words, create the following phrase:  

```php
<?php
$phrase = new \LuceneQuery\Phrase('search term');
$phrase->setProximity(5);
```

The string representation of the query will be:

> "search term"~5

The proximity 0 means exact matching and, as the Lucene default value, must not be rendered. The proximity 1 would
allow interchanging words, "term search".

### Complex queries
#### Creating a complex query
To create a complex query containing one or more sub-queries of any type, just instantiate a new query and add
sub-queries:

```php
<?php
$phrase = new \LuceneQuery\Query;
$phrase
    ->add(new \LuceneQuery\Term('word'))
    ->add(new \LuceneQuery\Phrase('Lucene query'));
```

The string representation of the query will be:

> word "Lucene query"

#### Setting sub-queries with operators
Instead of creating terms or phrases, setting operators to them and finally adding them as sub-queries to a complex
query, you can use Query::shouldHave(), Query::mustHave() or Query::mustNotHave(), what automatically sets the
"optional", "required" or "prohibited" operator to the given sub-queries.

```php
<?php
$phrase = new \LuceneQuery\Query;
$phrase
    ->shouldHave(new \LuceneQuery\Term('word'))
    ->mustHave(new \LuceneQuery\Phrase('Lucene query'))
    ->mustNotHave(new \LuceneQuery\Phrase('Java development'));
```

The string representation of the query will be:

> word +"Lucene query" -"Java development"

#### Setting an operator to the complex query
You can add operators to complex queries right as to terms and phrases:

```php
<?php
$phrase = new \LuceneQuery\Query;
$phrase
    ->add(new \LuceneQuery\Term('Lucene'))
    ->add(new \LuceneQuery\Phrase('search'))
    ->required();
```

The query will match all documents containing necessarily "Lucene" or "search" (or both). The string representation will
be:

> +(Lucene search)

#### Fields
You can specify a field to search in by adding a constructor argument:

```php
<?php
$phrase = new \LuceneQuery\Query('title');
$phrase->add(new \LuceneQuery\Term('Lucene'));
```

The string representation of the query will be:

> title:Lucene