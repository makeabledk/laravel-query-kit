<?php

namespace Makeable\QueryKit;

use Makeable\QueryKit\Builder\Builder;

trait QueryKit
{
    /**
     * Check if a model passes the given scope.
     *
     * @param $name
     * @param array ...$args
     *
     * @return mixed
     */
    public function passesScope($name, ...$args)
    {
        return Builder::make($this, $name, ...$args)->check();
    }

    /**
     * Check if a model fails the given scope.
     *
     * @param $name
     * @param array ...$args
     *
     * @return mixed
     */
    public function failsScope($name, ...$args)
    {
        return ! $this->passesScope($name, ...$args);
    }
}
