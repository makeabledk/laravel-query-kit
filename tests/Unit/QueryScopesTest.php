<?php

namespace Makeable\QueryKit\Tests\Unit;

use Makeable\QueryKit\Tests\TestCase;
use Makeable\QueryKit\Tests\TestModel;

class QueryScopesTest extends TestCase
{
    protected function create($name, $age = null)
    {
        return TestModel::create(['name' => $name, 'age' => $age]);
    }

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
}
