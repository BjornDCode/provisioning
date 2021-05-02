<?php 

namespace App\Enums;

use ReflectionClass;
use App\Exceptions\InvalidTypeException;

abstract class Enum
{

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function all()
    {
        return collect((new ReflectionClass(static::class))->getConstants())->values()->toArray();
    }

    public static function fromString(string $value)
    {
        if (!collect(self::all())->contains($value)) {
            throw new InvalidTypeException();            
        }

        $class = get_called_class();

        return new $class($value);
    }

    public function toString()
    {
        return $this->value;
    }

}
