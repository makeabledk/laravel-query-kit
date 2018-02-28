<?php

namespace Makeable\QueryKit\Builder;

use BadMethodCallException;
use Closure;
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
    protected $stack;

    /**
     * Builder constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->stack = new Stack();
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
            $this->stack->apply(call_user_func_array(static::$macros[$method], $parameters));

            return $this;
        }

        try {
            // Check for a scope function
            $this->callScopeFunction($method, ...$parameters);

            return $this;
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
        if (method_exists($this->model, $method = Str::camel('scope_'.$name))) {
            return $this->model->$method($this, ...$args);
        }

        if (is_callable($name)) {
            return call_user_func($name, ...$args);
        }

        throw new BadMethodCallException('Scope method could not be resolved: '.$method);
    }

    // _________________________________________________________________________________________________________________

    /**
     * @return bool
     */
    public function check()
    {
        return $this->stack->first(function ($layer) {
            return ! $this->passesLayer($layer);
        }) === null;
    }

    /**
     * @param array $layer
     * @return bool
     */
    protected function passesLayer($layer)
    {
        foreach ($layer as $constraint)
        {
            if ($constraint->check($this->model)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Debug stack.
     */
    public function dd()
    {
        dd($this->stack);
    }

}
