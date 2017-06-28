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
    public function checkScope($name, ...$args)
    {
        return Builder::make($this, $name, ...$args)->toCheck();
    }

    /**
     * Check if a model fails the given scope.
     *
     * @param $name
     * @param array ...$args
     *
     * @return mixed
     */
    public function checkScopeFails($name, ...$args)
    {
        return ! $this->checkScope($name, ...$args);
    }
}
