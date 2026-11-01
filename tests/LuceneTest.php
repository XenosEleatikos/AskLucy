<?php

declare(strict_types=1);

namespace AskLucy\Test;

use AskLucy\Lucene;
use PHPUnit\Framework\TestCase;

/**
 * Tests complete queries, as documented in the README.
 */
final class LuceneTest extends TestCase
{
    public function testComplexQuery(): void
    {
        $query = Lucene::query()
            ->mustHave(Lucene::term('Lucene', 'title')->boost(2))
            ->mustHave(
                Lucene::query()
                    ->add(Lucene::phrase('search engine'))
                    ->or(Lucene::wildcard('index*'))
            )
            ->mustNotHave(Lucene::range('2000', null, 'year')->exclusiveFrom())
            ->shouldHave(Lucene::term('serch')->fuzzify1())
            ->shouldHave(Lucene::regex('[mb]oat', 'body'));

        $this->assertSame(
            '+title:Lucene^2 +("search engine" OR index*) -year:{2000 TO *] serch~1 body:/[mb]oat/',
            (string) $query
        );
    }

    public function testUserInputCannotInjectSyntax(): void
    {
        $this->assertSame('title:x\)OR\(y', (string) Lucene::term('x)OR(y', 'title'));
        $this->assertSame('title:\-secret', (string) Lucene::term('-secret', 'title'));
    }
}
