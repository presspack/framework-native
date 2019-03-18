<?php

namespace Presspack\Framework\Support\Traits;

Trait isFacade {
    protected static $instance;

    public static function __callStatic($method, $args)
    {
        $called_class = get_called_class();
        if (! static::$instance) {
            static::$instance = new $called_class();
        }

        return static::$instance->{$method}(...$args);
    }

    public function __call($method, $args)
    {
        $called_class = get_called_class();
        if (! static::$instance) {
            static::$instance = new $called_class();
        }

        return static::$instance->{$method}(...$args);
    }
}
