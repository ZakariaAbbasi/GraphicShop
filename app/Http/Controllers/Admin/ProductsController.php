<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Utilities\DiePages;
use Illuminate\Http\Request;
use App\Utilities\FileRemover;
use App\Utilities\ImageUploader;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;

class ProductsController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.add', compact('categories'));
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $delProduct = Product::find($id);
            $result = $delProduct->delete();
            File::deleteDirectory(public_path('products/' . $id));
            File::deleteDirectory(storage_path('app/local_storage/products/' . $id));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('faild', $th->getMessage());
        }
        DB::commit();
        if ($result)
            return back()->with('success', 'محصول حذف شد');
    }

    public function edit($id)
    {
        $categories = Category::all();
        $product = Product::findOrFail($id);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $validateData = $request->validated();
        $product = Product::findOrFail($id);
        $updateProducts = $product->update(
            [
                'title' => $validateData['title'],
                'category_id' => $validateData['category_id'],
                'price' => $validateData['price'],
                'description' => $validateData['description'],

            ]
        );
        $this->removeOldImages($product, $validateData);
        if (!$this->uploadImages($product, $validateData) or !$updateProducts)

            return DiePages::messages('faild', ' بروزرسانی انجام  نشد');

        return DiePages::messages('success', 'محصول بروزسانی شد');
    }

    public function store(StoreRequest $request)
    {
        // DB::beginTransaction();
        $validateData = $request->validated();

        $users = User::where('email', 'zakaria@gmail.com')->first();
        $products = Product::create(
            [
                'title' => $validateData['title'],
                'category_id' => $validateData['category_id'],
                'price' => $validateData['price'],
                'description' => $validateData['description'],
                'owner_id' => $users->id
            ]
        );
        // DB::commit();
        if (!$this->uploadImages($products, $validateData))
            return DiePages::messages('faild', ' محصول ایجادنشد');

        return DiePages::messages('success', 'محصول ایجاد شد');
    }


    public function all()
    {
        $products = Product::paginate(1);
        return view('admin.products.all', compact('products'));
    }

    public function downloadDemo($id)
    {
        $demoProduct = Product::findOrFail($id);
        return response()->download(public_path($demoProduct->demo_url));
    }

    public function downloadSource($id)
    {
        $sourceProduct = Product::findOrFail($id);
        return response()->download(storage_path('app/local_storage/' . $sourceProduct->source_url));
    }

    private  function uploadImages($createdProduct, $validateData)
    {
        try {

            $basePath = 'products/' . $createdProduct->id . '/';
            $fullPathImageSource = null;
            $data = [];

            if (isset($validateData['source_url'])) {

                $fullPathImageSource = $basePath .  'source_url_' . $validateData['source_url']->getClientOriginalName();
                ImageUploader::uploaded($validateData['source_url'], $fullPathImageSource);
                $data += ['source_url' => $fullPathImageSource];
            }
            if (isset($validateData['thumbnail_url'])) {

                $fullPath = $basePath .  'thumbnail_url_' . $validateData['thumbnail_url']->getClientOriginalName();
                ImageUploader::uploaded($validateData['thumbnail_url'], $fullPath, 'public_storage');
                $data += ['thumbnail_url' => $fullPath];
            }

            if (isset($validateData['demo_url'])) {

                $fullPath = $basePath .  'demo_url_' . $validateData['demo_url']->getClientOriginalName();
                ImageUploader::uploaded($validateData['demo_url'], $fullPath, 'public_storage');
                $data += ['demo_url' => $fullPath];
            }

            $updateProducts = $createdProduct->update($data);
            if (!$updateProducts)

                throw new \Exception('تصاویر آپلود نشدند');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function removeOldImages($product, $validatedData)
    {
        if (isset($validatedData['source_url'])) {
            $sourcePath = $product->source_url;
            FileRemover::remove($sourcePath, 'local_storage');
        }

        if (isset($validatedData['thumbnail_url'])) {
            $thumbnailPath = $product->thumbnail_url;
            FileRemover::remove($thumbnailPath);
        }

        if (isset($validatedData['demo_url'])) {
            $demoPath = $product->demo_url;
            FileRemover::remove($demoPath);
        }
    }
}
