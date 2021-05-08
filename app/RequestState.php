<?php

namespace App;

use Illuminate\Http\Request;


class RequestState
{

    public static function fromArray(array $state): string
    {
        return self::encodeState($state);
    }

    public static function fromString(string $state): array
    {
        $state = $state ?? self::encodeState([
            'redirect' => null,
        ]);

        return json_decode(
            base64_decode(
                urldecode($state)
            )
        , true);        
    }

    private static function encodeState(array $state)
    {
        return urlencode(
            base64_encode(
                json_encode($state)
            )
        );
    }

}
