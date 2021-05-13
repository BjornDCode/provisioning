<?php

namespace App;

class CustomerId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;    
    }

    public static function fromString(string $id)
    {
        return new CustomerId($id);
    }

    public function toString()
    {
        return $this->id;
    }

}
