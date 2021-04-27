<?php 

namespace App\Enums;

use ReflectionClass;

abstract class Enum
{
    public static function all()
    {
        return collect((new ReflectionClass(static::class))->getConstants())->values()->toArray();
    }
}
