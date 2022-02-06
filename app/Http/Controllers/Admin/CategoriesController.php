<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Models\Category;
use App\Utilities\DiePages;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function delete($id)
    {
        $delCategoty = $this->find_category_id($id);
        $result = $delCategoty->delete();
        if ($result)
            return back()->with('success', 'دسته بندی حذف شد');
        return back()->with('faild', ' خطا در حذف دسته بندی');
    }

    public function edit($id)
    {
        $putCategory = $this->find_category_id($id);

        return view('admin.categories.edit', compact('putCategory'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $requestData = $request->validated();
        $updateCategoty = $this->find_category_id($id);
        $date  = $updateCategoty->update(
            [
                'title' => $requestData['title'],
                'slug' => $requestData['slug'],
            ]
        );
        if (!$date)
            return DiePages::messages('failed', 'دسته بندی بروزرسانی نشد');
        return DiePages::messages('success', 'دسته بندی بروزرسانی شد');
    }



    public function all()
    {
        $allCategories = Category::paginate(3);
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
            return DiePages::messages('failed', 'دسته بندی ایجاد نشد');
        return DiePages::messages('success', 'دسته بندی  ایجاد شد');
    }

    private  function find_category_id($id)
    {
        return Category::find($id);
    }


    
}
