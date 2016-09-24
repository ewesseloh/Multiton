<?php

namespace App\Core;

/**
 * A 'multiton' trait to add a getInstance method
 * to a class that allows using a specific name
 * to access the instance. It is fully compatible
 * with PHP's `late static binding` - a class that
 * extends another class that uses `Multiton` will
 * not have the same named instances as the parent.
 *
 * To pass additional parameters to the constructor,
 * send them as usual when calling getInstance(),
 * in the appropriate order, but after the $name
 * argument. You may send `null` or any other falsy
 * value in lieu of the name, if you wish to use
 * the default.
 *
 * (slightly modified by ewesseloh)
 *
 * @link: https://github.com/kafene/Multiton
 * @version 0.2.0-2016.09.24
 */
trait Multiton
{
    /**
     * Singleton instances accessible by array key
     */
    protected static $instances = [];

    /**
     * Return an instance of the class.
     * @param string $name -
     */
    public static function instance($name = null)
    {
        $args = array_slice(func_get_args(), 1);
        $name = $name ?: 'default';
        $static = get_called_class();
        $key = sprintf('%s::%s', $static, $name);
        if (!isset(static::$instances[$key])) {
            $ref = new \ReflectionClass($static);
            static::$instances[$key] = (
                $ref->hasMethod('__construct')
                ? $ref->newInstanceArgs($args)
                : $ref->newInstanceWithoutConstructor()
            );
        }

        return static::$instances[$key];
    }
}
