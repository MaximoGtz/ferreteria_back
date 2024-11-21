<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with(['images', 'category', 'brand'])->get();
        //dd($products);
        if ($request->wantsJson()) {
            return response()->json($products);
        }
        return view('cart.cart')->with('products', $products);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'wholesale_price' => 'numeric',
            'brand_id' => 'required|integer|exists:brands,id',
            'sell_price' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'bar_code' => 'required|numeric|unique:products,bar_code',
            'stock' => 'required|integer',
            'description' => 'required|string',
            'state' => 'required|in:ACTIVO,INACTIVO',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $product = Product::create($request->all());
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $image = Image::create(['image' => $path]);
                $product->images()->attach($image->id);
            }
        }
    
        $product->load(['images', 'category', 'brand']);
    
        foreach ($product->images as $img) {
            $img->image = url('storage/' . $img->image);
        }
    
        return response()->json($product, 201);
    }
    
    

    /**
     * Display the specified resource.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'category_id' => 'integer|exists:categories,id',
        'name' => 'string|max:255',
        'sell_price' => 'numeric',
        'wholesale_price' => 'numeric',
        'buy_price' => 'numeric',
        'bar_code' => 'numeric|unique:products,bar_code,' . $id,
        'stock' => 'integer',
        'description' => 'string',
        'state' => 'in:ACTIVO,INACTIVO',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $product = Product::findOrFail($id);
    $product->update($request->all());

    if ($request->hasFile('images')) {
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }

        foreach ($request->file('images') as $file) {
            $path = $file->store('images', 'public');
            $image = Image::create(['image' => $path]);
            $product->images()->attach($image->id);
        }
    }

    $product->load(['images', 'categories', 'brands']);
    
    foreach ($product->images as $img) {
        $img->image = url('storage/' . $img->image);
    }

    return response()->json($product);
}

public function show($id)
{
    $product = Product::with(['images', 'category', 'brand'])->findOrFail($id);
    // return response()->json($product);
    if ($product) {
        // Pasar el producto a la vista
        return view('/products/'.$id)->with('product', $product);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $product = Product::findOrFail($id);

    foreach ($product->images as $image) {
        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        $image->delete();
    }

    $product->delete();

    return response()->json(['message' => 'Producto eliminado']);
}
}