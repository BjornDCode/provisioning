<?php

namespace App;

class SubscriptionId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;    
    }

    public static function fromString(string $id)
    {
        return new SubscriptionId($id);
    }

    public function toString()
    {
        return $this->id;
    }

}
