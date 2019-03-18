<?php

namespace Presspack\Framework\Support\Traits;

trait isFacade
{
    protected static $instance;

    public static function __callStatic($method, $args)
    {
        $called_class = \get_called_class();
        static::$instance = new $called_class();

        return static::$instance->{$method}(...$args);
    }

    public function __call($method, $args)
    {
        $called_class = \get_called_class();
        static::$instance = new $called_class();

        return static::$instance->{$method}(...$args);
    }
}
