<?php

namespace Makeable\QueryKit\Tests\Unit;

use Makeable\QueryKit\Tests\TestCase;
use Makeable\QueryKit\Tests\TestModel;

class QueryScopesTest extends TestCase
{
    protected function create($name, $age=null)
    {
        return TestModel::create(['name' => $name, 'age' => $age]);
    }

    public function test_basic_where()
    {
        $person = $this->create('John Doe', 27);
        $this->assertTrue($person->checkScope('nameIs', 'John Doe'));
        $this->assertFalse($person->checkScope('nameIs', 'John'));
    }

    public function test_check_scope_fails()
    {
        $person = $this->create('Jane', null);
        $this->assertTrue($person->checkScopeFails('nameIs', 'John'));
    }

    public function test_where_with_operators()
    {
        $person = $this->create('John Doe', 27);
        $this->assertTrue($person->checkScope('age', '=', 27));
        $this->assertTrue($person->checkScope('age', '>', 26));
        $this->assertTrue($person->checkScope('age', '<', 28));
        $this->assertTrue($person->checkScope('age', '<=', 27));
        $this->assertTrue($person->checkScope('age', '>=', 27));
        $this->assertTrue($person->checkScope('age', '<>', 26));
        $this->assertFalse($person->checkScope('age', '<>', 27));
    }

    public function test_where_like()
    {
        $person = $this->create('John Doe');
        $this->assertTrue($person->checkScope('nameLike', 'John Doe'));
        $this->assertTrue($person->checkScope('nameLike', 'Joh%'));
        $this->assertTrue($person->checkScope('nameLike', '_ohn%'));
        $this->assertTrue($person->checkScope('nameLike', '%Doe'));
        $this->assertFalse($person->checkScope('nameLike', 'John'));
        $this->assertFalse($person->checkScope('nameLike', '_John%'));
    }

    public function test_where_in()
    {
        $person = $this->create('John Doe', 27);
        $this->assertTrue($person->checkScope('nameIn', ['Jane Doe', 'John Doe']));
        $this->assertFalse($person->checkScope('nameIn', ['Jane Doe', 'John']));
    }

    public function test_where_null()
    {
        $person = $this->create('John Doe', null);
        $this->assertTrue($person->checkScope('noAge'));
    }

    public function test_where_not_null()
    {
        $jane = $this->create('Jane Doe', 27);
        $john = $this->create('John Doe', null);
        $this->assertTrue($jane->checkScope('hasAge'));
        $this->assertFalse($john->checkScope('hasAge'));
    }
}
