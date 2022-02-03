<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function all()
    {
        $allCategories = Category::all();
        return view('admin.categories.all', compact('allCategories'));
    }

    public function create()
    {
        return view('admin.categories.add');
    }

    public function store(StoreRequest $request)
    {
        $requestData = $request->validated();
        $addCategory = Category::create(
            [
                'title' => $requestData['title'],
                'slug' => $requestData['slug'],
            ]
        );
        if (!$addCategory)
            return back()->with('failed', 'دسته بندی ایجاد نشد');

        return back()->with('success', 'دسته بندی  با موفقیت ایجاد شد');
    }
}
