<?php

namespace Makeable\QueryKit\Builder;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Makeable\QueryKit\Contracts\QueryConstraint;

/**
 * QueryKit Builder.
 *
 * @method Builder where(...$args)
 * @method Builder whereIn(...$args)
 * @method Builder whereNull(...$args)
 * @method Builder whereNotNull(...$args)
 */
class Builder
{
    use Macroable;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $stack = [];

    /**
     * Builder constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // _________________________________________________________________________________________________________________

    /**
     * Call a registered constraint type.
     *
     * @param $method
     * @param $parameters
     *
     * @return Builder
     */
    public function __call($method, $parameters)
    {
        // Check macros for registered Query Constraint
        if (static::hasMacro($method)) {
            return $this->pushStack(call_user_func_array(static::$macros[$method], $parameters));
        }

        try {
            // Check for a scope function
            return $this->pushStack(
                call_user_func_array([$this, 'callScopeFunction'], array_merge([$method], $parameters))->stack
            );
        } catch (\BadMethodCallException $e) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }
    }

    /**
     * @param $model
     * @param $name
     * @param array ...$args
     *
     * @return Builder
     */
    public static function make($model, $name, ...$args)
    {
        return (new static($model))->callScopeFunction($name, ...$args);
    }

    /**
     * Register a new constraint type.
     *
     * @param $class
     */
    public static function registerConstraint($class)
    {
        static::macro(Str::camel(class_basename($class)), function (...$parameters) use ($class) {
            return new $class(...$parameters);
        });
    }

    /**
     * @param $name
     * @param array ...$args
     *
     * @return mixed
     */
    public function callScopeFunction($name, ...$args)
    {
        $method = Str::camel('scope_'.$name);

        if (!method_exists($this->model, $method)) {
            throw new BadMethodCallException('Scope method could not be resolved: '.$method);
        }

        return $this->model->$method($this, ...$args);
    }

    // _________________________________________________________________________________________________________________

    /**
     * @return bool
     */
    public function toCheck()
    {
        return collect($this->stack)
            ->filter(function (QueryConstraint $constraint) {
                return $constraint->check($this->model);
            })
            ->count() === count($this->stack);
    }

    /**
     * Debug stack.
     */
    public function dd()
    {
        dd($this->stack);
    }

    /**
     * Stack constraint for this builder.
     *
     * @param QueryConstraint $query
     *
     * @return Builder
     */
    protected function pushStack($query)
    {
        $query = is_array($query) ? $query : [$query];
        $this->stack = array_merge($this->stack, $query);

        return $this;
    }
}
