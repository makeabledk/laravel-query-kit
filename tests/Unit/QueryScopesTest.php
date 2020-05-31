<?php

namespace Makeable\QueryKit\Tests\Unit;

use Makeable\QueryKit\Tests\TestCase;

class QueryScopesTest extends TestCase
{
    public function test_basic_where()
    {
        $person = $this->create('John Doe', 27);
        $this->assertTrue($person->passesScope('nameIs', 'John Doe'));
        $this->assertFalse($person->passesScope('nameIs', 'John'));
    }

    public function test_check_scope_fails()
    {
        $person = $this->create('Jane', null);
        $this->assertTrue($person->failsScope('nameIs', 'John'));
    }

    public function test_where_with_operators()
    {
        $person = $this->create('John Doe', 27);
        $this->assertTrue($person->passesScope('age', '=', 27));
        $this->assertTrue($person->passesScope('age', '>', 26));
        $this->assertTrue($person->passesScope('age', '<', 28));
        $this->assertTrue($person->passesScope('age', '<=', 27));
        $this->assertTrue($person->passesScope('age', '>=', 27));
        $this->assertTrue($person->passesScope('age', '<>', 26));
        $this->assertFalse($person->passesScope('age', '<>', 27));
    }

    public function test_where_like()
    {
        $person = $this->create('John Doe');
        $this->assertTrue($person->passesScope('nameLike', 'John Doe'));
        $this->assertTrue($person->passesScope('nameLike', 'Joh%'));
        $this->assertTrue($person->passesScope('nameLike', '_ohn%'));
        $this->assertTrue($person->passesScope('nameLike', '%Doe'));
        $this->assertFalse($person->passesScope('nameLike', 'John'));
        $this->assertFalse($person->passesScope('nameLike', '_John%'));
        $this->assertFalse($person->passesScope('nameLike', '_John%'));
        $this->assertTrue($person->passesScope('nameLike', 'john doe')); // case insensitive

        $person = $this->create("John \n Doe");
        $this->assertTrue($person->passesScope('nameLike', 'John%Doe')); // % allows linebreaks
    }

    public function test_where_sub_queries()
    {
        $this->create('Jane Doe');
        $this->create('Jane Doe', 27);
        $this->create('Janine Doe', 27);
        $this->create('Janine Doe', 28);

        $this->assertTrue($this->create('John')->passesScope(function ($query) {
            $query->janeOrJohn()->where(function ($query) {
                $query->age('>', 20)->orWhereNull('age');
            });
        }));
    }

    public function test_or_where()
    {
        $this->assertTrue($this->create('Jane Doe')->passesScope('janeOrJohn'));
        $this->assertTrue($this->create('John Doe')->passesScope('janeOrJohn'));
        $this->assertFalse($this->create('Janine Doe')->passesScope('janeOrJohn'));
    }

    public function test_where_between()
    {
        $john = $this->create('John', 25);

        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereBetween('age', [20, 25]);
        }));
        $this->assertFalse($john->passesScope(function ($query) {
            $query->whereBetween('age', [19, 24]);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereBetween('age', [19, 24])->orWhereBetween('age', [25, 30]);
        }));
    }

    public function test_where_not_between()
    {
        $john = $this->create('John', 25);

        $this->assertFalse($john->passesScope(function ($query) {
            $query->whereNotBetween('age', [20, 25]);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereNotBetween('age', [19, 24]);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereNotBetween('age', [19, 24])
                ->orWhereNotBetween('age', [26, 30]);
        }));
    }

    public function test_where_in()
    {
        $person = $this->create('John Doe', 27);
        $this->assertTrue($person->passesScope('nameIn', ['Jane Doe', 'John Doe']));
        $this->assertFalse($person->passesScope('nameIn', ['Jane Doe', 'John']));
        $this->assertTrue($person->passesScope(function ($query) {
            $query->whereIn('name', ['Jane'])->orWhereIn('name', ['John Doe']);
        }));
    }

    public function test_where_null()
    {
        $person = $this->create('John Doe', null);
        $this->assertTrue($person->passesScope('noAge'));
    }

    public function test_where_not_in()
    {
        $this->assertTrue($this->create('John')->passesScope(function ($query) {
            $query->whereNotIn('name', ['Jane']);
        }));
        $this->assertTrue($this->create('John')->passesScope(function ($query) {
            $query->whereNotIn('name', ['Jane'])
                ->orWhereNotIn('name', ['John Doe']);
        }));
    }

    public function test_where_not_null()
    {
        $jane = $this->create('Jane Doe', 27);
        $john = $this->create('John Doe', null);
        $this->assertTrue($jane->passesScope('hasAge'));
        $this->assertFalse($john->passesScope('hasAge'));
    }

    public function test_or_where_null()
    {
        $this->assertFalse($this->create('John', 'John')->passesScope('missingNameOrAge'));
        $this->assertTrue($this->create(null, 'John')->passesScope('missingNameOrAge'));
        $this->assertTrue($this->create('John', null)->passesScope('missingNameOrAge'));
        $this->assertTrue($this->create(null, null)->passesScope('missingNameOrAge'));
    }

    public function test_or_where_not_null()
    {
        $this->assertTrue($this->create('John', 'John')->passesScope('hasNameOrAge'));
        $this->assertTrue($this->create('John', null)->passesScope('hasNameOrAge'));
        $this->assertTrue($this->create(null, 'John')->passesScope('hasNameOrAge'));
        $this->assertFalse($this->create(null, null)->passesScope('hasNameOrAge'));
    }

    public function test_where_date()
    {
        $john = $this->create('John');

        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereDate('created_at', now()->toDateString());
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereDate('created_at', '<', now()->addDay()->toDateString());
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query
                ->whereDate('created_at', now()->toDateString())
                ->orWhereDate('created_at', now()->subDay()->toDateString());
        }));
    }

    public function test_where_day()
    {
        $john = $this->create('John');

        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereDay('created_at', now()->day);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereDay('created_at', (string) now()->day);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query
                ->whereDay('created_at', now()->day)
                ->orWhereDay('created_at', now()->subDay()->day);
        }));
    }

    public function test_where_month()
    {
        $john = $this->create('John');

        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereMonth('created_at', now()->month);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereMonth('created_at', (string) now()->month);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query
                ->whereMonth('created_at', now()->month)
                ->orWhereMonth('created_at', now()->subMonth()->month);
        }));
    }

    public function test_where_time()
    {
        $john = $this->create('John');
        $john->created_at = '2018-01-01 00:00:00';
        $john->save();

        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereTime('created_at', '00:00:00');
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query
                ->whereTime('created_at', '00:00:01')
                ->orWhereTime('created_at', '00:00:00');
        }));
    }

    public function test_where_year()
    {
        $john = $this->create('John');

        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereYear('created_at', now()->year);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query->whereYear('created_at', (string) now()->year);
        }));
        $this->assertTrue($john->passesScope(function ($query) {
            $query
                ->whereYear('created_at', now()->year)
                ->orWhereYear('created_at', now()->subYear()->year);
        }));
    }

    public function test_where_column()
    {
        $jhon = $this->create('Jhon', 18);
        $jhon->countryMajorityAge = 18;

        $this->assertTrue($jhon->passesScope(function ($query) {
            $query->whereColumn('age', '=', 'countryMajorityAge');
        }));
        $this->assertFalse($jhon->passesScope(function ($query) {
            $query->whereColumn('age', '>', 'countryMajorityAge');
        }));
        $this->assertFalse($jhon->passesScope(function ($query) {
            $query->whereColumn('age', '<', 'countryMajorityAge');
        }));
        $this->assertTrue($jhon->passesScope(function ($query) {
            $query->whereColumn('age', '>=', 'countryMajorityAge');
        }));
        $this->assertTrue($jhon->passesScope(function ($query) {
            $query->whereColumn('age', '<=', 'countryMajorityAge');
        }));
        $this->assertFalse($jhon->passesScope(function ($query) {
            $query->whereColumn('age', '<>', 'countryMajorityAge');
        }));

        $adam = $this->create('Adam', 27);
        $adam->countryMajorityAge = 18;

        $this->assertFalse($adam->passesScope(function ($query) {
            $query->whereColumn('age', '=', 'countryMajorityAge');
        }));
        $this->assertTrue($adam->passesScope(function ($query) {
            $query->whereColumn('age', '>', 'countryMajorityAge');
        }));
        $this->assertFalse($adam->passesScope(function ($query) {
            $query->whereColumn('age', '<', 'countryMajorityAge');
        }));
        $this->assertTrue($adam->passesScope(function ($query) {
            $query->whereColumn('age', '>=', 'countryMajorityAge');
        }));
        $this->assertFalse($adam->passesScope(function ($query) {
            $query->whereColumn('age', '<=', 'countryMajorityAge');
        }));
        $this->assertTrue($adam->passesScope(function ($query) {
            $query->whereColumn('age', '<>', 'countryMajorityAge');
        }));

        $mary = $this->create('Mary', 18);
        $mary->countryMajorityAge = 21;
        $this->assertFalse($mary->passesScope(function ($query) {
            $query->whereColumn('age', '=', 'countryMajorityAge');
        }));
        $this->assertFalse($mary->passesScope(function ($query) {
            $query->whereColumn('age', '>', 'countryMajorityAge');
        }));
        $this->assertTrue($mary->passesScope(function ($query) {
            $query->whereColumn('age', '<', 'countryMajorityAge');
        }));
        $this->assertFalse($mary->passesScope(function ($query) {
            $query->whereColumn('age', '>=', 'countryMajorityAge');
        }));
        $this->assertTrue($mary->passesScope(function ($query) {
            $query->whereColumn('age', '<=', 'countryMajorityAge');
        }));
        $this->assertTrue($mary->passesScope(function ($query) {
            $query->whereColumn('age', '<>', 'countryMajorityAge');
        }));
    }
}
