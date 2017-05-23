# A Php Library for Creating Lucene Search Queries
## Usage
A query to Lucene search engine consists of one ore more clauses for matching documents. There are three types of clauses:
1. Terms matching documents that contain a single word.
2. Phrases matching documents that contain a sequence of words.
3. Complex queries containing one or more sub-clauses of any type.

### Creating Clauses
#### Creating a Term
To create a query matching documents that contain a single word, e. g. "word", just instantiate a new ```Term``` with
the word as constructor argument:

```php
<?php
$term = new \LuceneQuery\Term('word');
```
The string representation of the query will be:

> word

#### Creating a Phrase
To create a clause matching documents that contain a sequence of words, e. g. "Lucene search", you can instantiate a new
```Phrase``` with the sequence as constructor argument:

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
```

The string representation of the query will be:

> "Lucene query"

#### Creating a Complex Query
To create a complex query containing one or more clauses of any type, instantiate a new ```Query``` and add clauses:

```php
<?php
$phrase = new \LuceneQuery\Query;
$phrase
    ->add(new \LuceneQuery\Term('word'))
    ->add(new \LuceneQuery\Phrase('Lucene query'));
```

The string representation of the query will be:

> word "Lucene query"

### Fields
For all types of clauses you can specify a field to search in by calling the method ```setField()``` or by adding a
constructor argument.

#### Setting a Field to a Term
To search for documents containing "Lucene" in the (field named) "title", use the following snippet:

```php
<?php
$term = new \LuceneQuery\Term('Lucene');
$term->setField('title');
```

As a shortcut you may also set the field by adding a second constructor argument:

```php
<?php
$term = new \LuceneQuery\Term('Lucene', 'title');
```

Both lead to the same result:

> title:Lucene

#### Setting a Field to a Phrase
You can specify a field to search in by calling the method ```setField()```...

```php
<?php
$phrase = new \LuceneQuery\Phrase('Search Engine');
$phrase->setField('title');
```

... or you can set the field by adding a second constructor argument:

```php
<?php
$phrase = new \LuceneQuery\Phrase('Search Engine', 'title');
```

The result is the same:

> title:"Search Engine"

#### Setting Fields in Complex Queries
As before you can specify a search field by calling the method ```setField()```...

```php
<?php
$query = new \LuceneQuery\Query;
$query
    ->add(new \LuceneQuery\Term('Lucene'))
    ->add(new \LuceneQuery\Term('Apache'));
$query->setField('title');
```

... or by passing a constructor argument:

```php
<?php
$query = new \LuceneQuery\Query('title');
$query
    ->add(new \LuceneQuery\Term('Lucene'))
    ->add(new \LuceneQuery\Term('Apache'));
```

In both cases the string representation of the query will be:

> title:(Lucene Apache)

Note, that the brackets are set automatically, if more than one sub-clauses were set. If you want to specify a field
just for a certain sub-clause, you may do this:

```php
<?php
$query = new \LuceneQuery\Query;
$query
    ->add(new \LuceneQuery\Term('Lucene', 'title'))
    ->add(new \LuceneQuery\Term('Apache'));
```

The result will be:

> title:Lucene Apache

### Operators
For all types of clauses you can add operators to define, if matching is required, prohibited or optional. Just call
```required()```, ```prohibited()```, or ```optional()```. Note, that a clause is optional by default, so that calling
```optional()``` is optional. But it can be used to override an operator set before.

#### Setting an Operator to a Term
To require the word "PHP" necessarily, use the following snippet...

```php
<?php
$term = new \LuceneQuery\Term('PHP');
$term->required();
```

... and get:

> +PHP

To prohibit the word "Java", do this...

```php
<?php
$term = new \LuceneQuery\Term('Java');
$term->prohibited();
```

... and you'll get that:

> -Java

#### Setting an Operator to a Phrase
You can add operators to phrases in the same manner as to terms.
Require a phrase necessarily...

```php
<?php
$phrase = new \LuceneQuery\Phrase('Lucene query');
$phrase->required();
```

... and get the string representation:

> +"Lucene query"

Prohibit the phrase...

```php
<?php
$phrase = new \LuceneQuery\Phrase('Java development');
$phrase->prohibited();
```

... and get:

> -"Java development"

#### Setting Operators in Complex Queries
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

Instead of creating sub-clauses, setting operators to them and finally adding them to a complex query, you can use
```Query::shouldHave()```, ```Query::mustHave()``` or ```Query::mustNotHave()```, what automatically sets the "optional",
"required" or "prohibited" operator to the given sub-queries.

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

### Fuzziness

You can do a fuzzy search term by calling ```Term::fuzzify()```:

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

### Proximity Search
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
