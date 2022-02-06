<?php

namespace App\Utilities;

class DiePages
{


    public static function messages($data, string $string)
    {
        return back()->with($data, $string);
    }
}