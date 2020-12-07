<?php

namespace Makeable\QueryKit\Tests;

use Illuminate\Database\Schema\Blueprint;
use Makeable\QueryKit\QueryKitServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            QueryKitServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('age')->nullable();
            $table->boolean('wants_newsletter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @param null $name
     * @param null $age
     * @return TestModel
     */
    protected function create($name = null, $age = null)
    {
        return TestModel::create(['name' => $name, 'age' => $age]);
    }
}
