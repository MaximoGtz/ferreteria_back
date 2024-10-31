<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image; // Asegúrate de importar el modelo Image
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index()
    {
        return Category::with('products')->get(); // Carga las categorías y los productos
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'description' => 'required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imágenes
        ]);

        // Crear la categoría
        $category = Category::create($fields);

        // // Manejar la carga de imágenes
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $file) {
        //         $path = $file->store('images', 'public');
        //         $image = Image::create(['image' => $path]);


        //     }
        // }

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $category->load('products'); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $fields = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id, 
            'description' => 'required',
        ]);
        
        $category->update($fields);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category has been eliminated']);
    }
}
