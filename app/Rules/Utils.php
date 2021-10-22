<?php

namespace App\Rules;

class Utils
{
    public static function stringToArray(string $string, string $separator = ','): array
    {
        return array_map('trim', explode($separator, $string));
    }
}
