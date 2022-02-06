<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Utilities\ImageUploader;

use Illuminate\Http\Request;


class ProductsController extends Controller
{
    public function create()
    {
        $allCategories = Category::all();
        return view('admin.products.add', compact('allCategories'));
    }

    public function store(StoreRequest $request)
    {
        $requestData = $request->validated();

        // ImageUploader::uploaded($requestData['thumbnail_url'],'');
        // ImageUploader::uploaded($requestData['demo_url'],'');
        // ImageUploader::uploaded($requestData['source_url'],'');

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
            return back()->with('faild', $e->getMessage());
        }
    }


    public function all()
    {
        $allCategories = Product::all();
        return view('admin.products.all', compact('allCategories'));
    }
}
