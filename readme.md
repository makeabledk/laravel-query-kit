

# Laravel Query Kit

This package provides a handy way to work with query scopes on model instances in Laravel.

Makeable is web- and mobile app agency located in Aarhus, Denmark.

## Installation

You can install this package via composer:

``` bash
composer require makeabledk/laravel-querykit
```

Add the service provider to your config/app.php:

```php
'providers' => [
    ...
    Makeable\QueryKit\QueryKitServiceProvider::class,
];
```

## Usage

Whenever you have a query scope on an Eloquent Model, you apply the following trait to add QueryKit:

```php
class Model extends Eloquent {
    use \Makeable\QueryKit\QueryKit;
}
```

From then on whenever you have a Laravel scope, you use the checker functions on a single model instance:

```php
class Person extends Eloquent {
    use \Makeable\QueryKit\QueryKit;
    
    protected $fillable = ['name']; 
    
    public function scopeNameIs($query, $name)
    {
        return $query->where('name', $name);
    }
}
```

```php
$john = new Person(['name' => 'John']);

$john->checkScope('nameIs', 'John'); // true
$john->checkScope('nameIs', 'Doe'); // false
```

Pretty cool, right?

## Provided methods

### checkScope
```php
/**
 * Check if a model passes the given scope
 * 
 * @param $name
 * @param array ...$args
 * @return mixed
 */
public function checkScope($name, ...$args)
```

Example:
```php
$john = new Person(['name' => 'John']);
$john->checkScope('nameIs', 'John'); // true
```

### checkScopeFails
```php
/**
 * Check if a model fails the given scope
 * 
 * @param $name
 * @param array ...$args
 * @return mixed
 */
public function checkScopeFails($name, ...$args)
{
    return ! $this->checkScope($name, ...$args);
}
```

Example:
```php
$john = new Person(['name' => 'John']);
$john->checkScopeFails('nameIs', 'John'); // true
```

## Current limitations

As of this moment QueryKit only supports the following scope methods

- Where (including the different operators)
- WhereIn
- WhereNotNull
- WhereNull

Furthermore some argument types such as closures and query builder may not be supported. 
An exception will be thrown in that case.

## Extending QueryKit

Say that you want to add functionality for Laravel QueryBuilder's 'orWhere' method:

Create a **OrWhere** that implements **\Makeable\QueryKit\Contracts\QueryConstraint**.

```php
class OrWhere implements \Makeable\QueryKit\Contracts\QueryConstraint
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
    \Makeable\QueryKit\Builder\Builder::registerConstraint(OrWhere::class);
}
```

You can also use the above method to override the existing implementations.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

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