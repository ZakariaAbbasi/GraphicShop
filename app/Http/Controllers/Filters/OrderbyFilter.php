<?php

namespace App\Http\Controllers\Filters;

use App\Models\Product;


class OrderbyFilter
{
    public function newest()
    {
        return Product::orderBy('created_at', 'desc') ->get();
    }
}
