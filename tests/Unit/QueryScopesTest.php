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
    }

    public function test_where_in()
    {
        $person = $this->create('John Doe', 27);
        $this->assertTrue($person->passesScope('nameIn', ['Jane Doe', 'John Doe']));
        $this->assertFalse($person->passesScope('nameIn', ['Jane Doe', 'John']));
    }

    public function test_where_null()
    {
        $person = $this->create('John Doe', null);
        $this->assertTrue($person->passesScope('noAge'));
    }

    public function test_where_not_null()
    {
        $jane = $this->create('Jane Doe', 27);
        $john = $this->create('John Doe', null);
        $this->assertTrue($jane->passesScope('hasAge'));
        $this->assertFalse($john->passesScope('hasAge'));
    }

    public function test_or_where()
    {
        $this->assertTrue($this->create('Jane Doe')->passesScope('janeOrJohn'));
        $this->assertTrue($this->create('John Doe')->passesScope('janeOrJohn'));
        $this->assertFalse($this->create('Janine Doe')->passesScope('janeOrJohn'));
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

    public function test_sub_queries()
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

//        $this->assertTrue($this->create('Janine')->passesScope(function ($query) {
//            $query->janeOrJohn()->where(function ($query) {
//                $query->age('>', 20)->orWhereNull('age');
//            });
//        }));
    }
}
