<?php

namespace App\Http\Controllers\Filters;

use App\Models\Product;


class OrderbyFilter
{
    public static function newest()
    {
        return Product::orderBy('created_at', 'desc') ->get();
    }

    public static function lowestPrice()
    {
        return Product::orderBy('price', 'asc') ->get();
    }

    public static function highestPrice()
    {
        return Product::orderBy('price', 'desc') ->get();
    }

    private  function findFilter(string $className, string $methodName)
    {
        $baseNamespace = 'App\\Http\\Controllers\\Filters\\';

        $className = $baseNamespace . (ucfirst($className) . 'Filter');

        if (!class_exists($className)) {
            return null;
        }

        $obj = new $className;

        if (!method_exists($obj, $methodName)) {
            return null;
        }
        return $obj->{$methodName}();
    }
}
