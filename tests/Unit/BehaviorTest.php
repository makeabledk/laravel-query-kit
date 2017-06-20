<?php

namespace Makeable\QueryKit\Tests\Unit;

use Makeable\QueryKit\Tests\TestCase;
use Makeable\QueryKit\Tests\TestModel;

class BehaviorTest extends TestCase
{
    public function test_statements_are_bound_to_the_object()
    {
        $class = new class extends TestModel {
            public function scopeJohnSenior($query)
            {
                // This should take effect even if we don't return it to match Laravel behavior
                $unused_variable = $query->where('age', '>=', 40);

                return $query->where('name', 'John');
            }
        };

        $johnSenior = $class::create(['name' => 'John', 'age' => 40]);
        $johnJunior = $class::create(['name' => 'John', 'age' => 10]);

        $this->assertTrue($johnSenior->checkScope('johnSenior'));
        $this->assertFalse($johnJunior->checkScope('johnSenior'));
    }
}
