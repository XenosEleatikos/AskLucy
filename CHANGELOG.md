# Changelog
All notable changes to this project are documented in this file. The format is based on
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to
[Semantic Versioning](https://semver.org/).

## [3.0.0] - 2026-11-01
### Added
- Wildcard search with `Lucene::wildcard()`
- Regular expression search with `Lucene::regex()`
- Boolean operators `AND`, `OR` and `NOT` with `Query::and()`, `Query::or()` and `Query::not()`
- Open ranges (`null` as bound) and ranges with mixed inclusive and exclusive bounds
- Automatic escaping of Lucene's special characters and reserved words in terms, phrases, ranges and field names, and
  `Lucene::escape()` for escaping strings manually
- GitHub Actions workflow, Dependabot configuration, license, security policy and contribution guide

### Changed
- **BC break:** PHP 8.3 or higher is required
- **BC break:** Phrases are always rendered in quotation marks, also if they contain a single word
- **BC break:** Empty terms, phrases and range bounds throw an `InvalidArgumentException`
- **BC break:** `Operator`, `Fuzziness` and `RangeType` were replaced by the enums `Operator`, `Fuzziness` and
  `Conjunction`; the constants `Operator::SYMBOL_*` were removed
- **BC break:** All classes are final, the traits in `AskLucy\Property` were replaced by the abstract class
  `AbstractClause`
- **BC break:** `AskLucy\Exception\InvalidArgumentException` extends `\InvalidArgumentException`
- **BC break:** The methods of the `Clause` interface declare `static` as return type; the constants
  `Query::CLAUSE_SEPARATOR` and `Phrase::TERM_SEPARATOR` and the factory methods of `Fuzziness`, `Operator` and
  `RangeType` were removed
- **BC break:** Regular expressions and quoted range bounds (bounds containing whitespace or closing brackets) must not
  end with a backslash, because Lucene would read it as an escape of the closing delimiter
- Fluent methods return `static`, so the type of the clause is preserved
- Development tools updated (PHPUnit 12, PHPStan 2 at level max, Infection, PHP-CS-Fixer); GrumPHP and Travis CI were
  replaced by Composer scripts and GitHub Actions

### Fixed
- A query with a field and several clauses is enclosed in parentheses (`title:(a b)` instead of `title:a b`), as is a
  query with several clauses and a boost
- Nested queries keep their grouping when combined with boolean operators
- Whitespace other than the space character in a term is rejected
- Boost values are rendered without exponent, and non-finite or (practically) zero values are rejected
- Single clauses with an operator, field or boost that would collide with those of their query are put in parentheses
  (`+(-a)` instead of `+-a`), as are clauses following `NOT` that start with `+` or `-`
- Decorated queries without clauses render as empty string instead of e. g. `+`
- Unicode whitespace (e. g. U+3000) is recognized in terms, wildcards, ranges, phrases and field names; invalid UTF-8
  is rejected
- Bounds of ranges containing closing brackets are quoted, because Lucene can't read escaped brackets in ranges
- Wildcards escape the reserved words `AND`, `OR` and `NOT`
- A query containing itself throws an exception instead of exhausting the stack
- Typos in the documentation

## [2.0.1] - 2019-03-17
#### Added
- Travis build information

## [2.0.0] - 2018-12-02
### Fixed
- Several coding style issues

### Added
- Lucene query builder class containing factory methods for clauses to simplify library usage
- Libraries own exception class

### Changed
- Add specific fuzziness factors by specific methods to prevent invalid arguments

## [1.0.1] - 2018-04-02
### Fixed
- Several coding style issues
- phpunit.xml for using phpunit without parameters given
- Require phpunit only for development

#### Added
- Unit tests
- Mutation tests
- Changelog

## [1.0.0] - 2017-05-30
#### Added
- An easy to use PHP library for creating Lucene search queries, containing an object oriented implementation for
  - terms
  - phrases
  - range searches
  - complex queries
  - field searches
  - logical operators
  - relevance boosting
  - fuzziness
  - proximity searches
- Unit tests covering 100% code
- Complete documentation
