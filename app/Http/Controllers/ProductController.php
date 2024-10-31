<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('images')->get();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
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
    
        // Modificar la respuesta para incluir la URL completa
        $product->load('images');
        foreach ($product->images as $img) {
            $img->image = url('storage/' . $img->image); // Cambia 'images/' a 'storage/' según el disco
        }
    
        return response()->json($product, 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'integer',
            'name' => 'string|max:255',
            'brand' => 'string|max:100',
            'sell_price' => 'numeric',
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

            // Guardar las nuevas imágenes
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $image = Image::create(['image' => $path]);
                $product->images()->attach($image->id);
            }
        }
        $product->load('images');
        foreach ($product->images as $img) {
            $img->image = url('storage/' . $img->image); // Cambia 'images/' a 'storage/' según el disco
        }

        return response()->json($product->load('images'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Eliminar las imágenes asociadas
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
