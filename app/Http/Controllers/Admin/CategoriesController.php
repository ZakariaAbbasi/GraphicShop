<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Utilities\DiePages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;

class CategoriesController extends Controller
{
    public function delete($id)
    {
        $delCategoty = Category::find($id);
        $result = $delCategoty->delete();
        if ($result)
            return DiePages::messages('success', 'دسته بندی حذف شد');
        return DiePages::messages('faild', ' خطا در حذف دسته بندی');
    }

    public function edit($id)
    {
        $categories = Category::find($id);

        return view('admin.categories.edit', compact('categories'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $validateData = $request->validated();
        $categoty = Category::find($id);

        $date  = $categoty->update(
            [
                'title' => $validateData['title'],
                'slug' => $validateData['slug'],
            ]
        );
        if (!$date)
            return DiePages::messages('failed', 'دسته بندی بروزرسانی نشد');
        return DiePages::messages('success', 'دسته بندی بروزرسانی شد');
    }



    public function all()
    {
        $categories = Category::paginate(3);
        return view('admin.categories.all', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.add');
    }

    public function store(StoreRequest $request)
    {
        $validateData = $request->validated();
        $categories = Category::create(
            [
                'title' => $validateData['title'],
                'slug' => $validateData['slug'],
            ]
        );
        if (!$categories)
            return DiePages::messages('failed', 'دسته بندی ایجاد نشد');
        return DiePages::messages('success', 'دسته بندی  ایجاد شد');
    }
}
