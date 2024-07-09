<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function storeApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:255|unique:products',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                $images[] = $path;
            }
        }

        $product = new Product([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'sku' => $request->get('sku'),
            'description' => $request->get('description'),
            'images' => json_encode($images),
        ]);

        $product->save();

        return response()->json($product, 201);
    }

    public function indexApi()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

}