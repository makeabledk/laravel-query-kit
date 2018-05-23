

# Laravel Query Kit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/makeabledk/laravel-querykit.svg?style=flat-square)](https://packagist.org/packages/makeabledk/laravel-querykit)
[![Build Status](https://img.shields.io/travis/makeabledk/laravel-query-kit/master.svg?style=flat-square)](https://travis-ci.org/makeabledk/laravel-query-kit)
[![StyleCI](https://styleci.io/repos/95551114/shield?branch=master)](https://styleci.io/repos/95551114)

This package provides a handy way to query eloquent-scopes on model instances in Laravel.

Traditionally you may find yourself having a `scopeAccepted` and then additionally a `ìsAccepted` helper method on your model. 

With QueryKit, those days are long gone 🔥

--

Makeable is web- and mobile app agency located in Aarhus, Denmark.

## Installation

You can install this package via composer:

``` bash
composer require makeabledk/laravel-querykit
```

For Laravel version prior 5.5: Add the service provider to your config/app.php:

```php
'providers' => [
    ...
    Makeable\QueryKit\QueryKitServiceProvider::class,
];
```

## Usage

Whenever you have a query scope on an Eloquent Model, you can apply the following trait to add QueryKit:

```php
class Job extends Eloquent {
    use \Makeable\QueryKit\QueryKit;
    
    public function scopeHired($query)
    {
        return $query->whereIn('status', ['started', 'finished']);
    }
}
```
Out of the box Laravel offers us a convenient way to query against our database:

```php
Job::hired()->first(); // a job with either 'started' or 'finished' status
```

But with query-kit you are now also able to check if a model instance passes a given scope:

```php
$startedJob->passesScope('hired'); // true
$pendingJob->passesScope('hired'); // false
```

Pretty cool, right?

**Much more advanced functionality is supported than this simple example.**

See **Currently supported methods** further down.

## Provided methods on `QueryKit` 

### passesScope
```php
/**
 * Check if a model passes the given scope
 * 
 * @param $name
 * @param array ...$args
 * @return bool
 */
public function passesScope($name, ...$args)
```

### failsScope
```php
/**
 * Check if a model fails the given scope
 * 
 * @param $name
 * @param array ...$args
 * @return bool
 */
public function failsScope($name, ...$args)
```

## Currently supported methods

As of this moment QueryKit supports the following query methods

- OrWhere
- OrWhereNotNull
- OrWhereNull
- Where (including the different operators)
- WhereIn
- WhereNotNull
- WhereNull

QueryKit tries to support most of the argument types that Eloquent Builder supports, but there might exceptions.

Also, do note that advanced joins and relations queries won't work.

## Extending QueryKit

Say that you want to add functionality for Laravel QueryBuilder's 'whereBetween' method:

Create a **WhereBetween** that implements **\Makeable\QueryKit\Contracts\QueryConstraint**.

```php
class WhereBetween implements \Makeable\QueryKit\Contracts\QueryConstraint
{
    public function __construct(...$args)
    {
        // Accept scope arguments here
    }

    public function check($model)
    {
        // Return boolean
    }
}

```

Next register the constraint in your AppServiceProvider's register method:

```php
public function register()
{
    \Makeable\QueryKit\Builder\Builder::registerConstraint(WhereBetween::class);
}
```

You can also use the above method to override the existing implementations.

## Related packages

Make sure to checkout our [makeabledk/laravel-eloquent-status](https://github.com/makeabledk/laravel-eloquent-status) package that streamlines the way you handle model-state across your application.

## Testing

You can run the tests with:

```bash
composer test
```

## Contributing

We are happy to receive pull requests for additional functionality. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Rasmus Christoffer Nielsen](https://github.com/rasmuscnielsen)
- [All Contributors](../../contributors)

## License

Attribution-ShareAlike 4.0 International. Please see [License File](LICENSE.md) for more information.