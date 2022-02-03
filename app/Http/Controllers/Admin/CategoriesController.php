<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function delete($id)
    {
        $delCategoty = Category::find($id);
         $result = $delCategoty->delete();
         if ($result) 
             return back()->with('success', 'دسته بندی حذف شد');
        return back()->with('faild', ' خطا در حذف دسته بندی');    
    }

    public function edit($id)
    {
        $putCategory = Category::find($id);
        
        return view('admin.categories.edit', compact('putCategory'));

    }

    public function update(UpdateRequest $request, $id)
    {
        $requestData = $request->validated();
        $updateCategoty = Category::find($id);
        $date  = $updateCategoty->update(
            [
                'title' => $requestData['title'],
                'slug' => $requestData['slug'],
            ]
        );
        if (!$date)
            return back()->with('failed', 'دسته بندی بروزرسانی نشد');

        return back()->with('success', 'دسته بندی بروزرسانی شد');
            
    }

    public function all()
    {
        $allCategories = Category::paginate(1);
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
