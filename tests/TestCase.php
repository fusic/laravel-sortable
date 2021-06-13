<?php

namespace Tests;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ReflectionClass;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        //$this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return ['Sortable\Providers\SortableServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.faker_locale', 'ja_JP');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function createRequest(string $routeName, array $queryParameters = []): Request
    {
        return Request::create(
            route($routeName),
            Request::METHOD_GET,
            $queryParameters
        );
    }

    protected function doMethod($obj, $methodName, array $param = [])
    {
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $param);
    }
}
