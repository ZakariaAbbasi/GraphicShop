<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Utilities\ImageUploader;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

use App\Http\Requests\Admin\Products\StoreRequest;

class ProductsController extends Controller
{
    public function create()
    {
        $allCategories = Category::all();
        return view('admin.products.add', compact('allCategories'));
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

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        $requestData = $request->validated();

        $users = User::where('email', 'zakaria@gmail.com')->first();
        $addProducts = Product::create(
            [
                'title' => $requestData['title'],
                'category_id' => $requestData['category_id'],
                'price' => $requestData['price'],
                'description' => $requestData['description'],
                'owner_id' => $users->id
            ]
        );
        DB::commit();
        try {
           
            $basePath = 'products/' . $addProducts->id . '/';
            $imageSourceUrl = $basePath .  'source_url_' . $requestData['source_url']->getClientOriginalName();

            $images = [
                'thumbnail_url' => $requestData['thumbnail_url'],
                'demo_url' => $requestData['demo_url'],
            ];
            $imagesPathThumbnailUrlAndDemoUrl = ImageUploader::uploadMany($images, $basePath);
            ImageUploader::uploaded($requestData['source_url'], $imageSourceUrl);

            $updateProducts = $addProducts->update(
                [
                    'thumbnail_url' => $imagesPathThumbnailUrlAndDemoUrl['thumbnail_url'],
                    'demo_url' => $imagesPathThumbnailUrlAndDemoUrl['demo_url'],
                    'source_url' => $imageSourceUrl
                ]
            );
            if (!$updateProducts)
                throw new \Exception('تصاویر آپلود نشدند');

            return back()->with('success', 'محصول ایجاد شد');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('faild', $e->getMessage());
        }
        
    }


    public function all()
    {
        $allProducts = Product::paginate(1);
        return view('admin.products.all', compact('allProducts'));
    }

    public function downloadDemo($id)
    {
        $demoProduct = Product::findOrFail($id);
        return response()->download(public_path($demoProduct->demo_url));
    }

    public function downloadSource($id)
    {
        $demoProduct = Product::findOrFail($id);
        return response()->download(storage_path('app/local_storage/' . $demoProduct->source_url));
    }
}
