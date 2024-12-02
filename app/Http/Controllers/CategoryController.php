<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller 
 implements HasMiddleware
{
    public static function middleware()
     {
             
        return [
                     new Middleware('auth:sanctum', except: ['index', 'show'])
                 ];
             }
            
            /**
             * Display a listing of the resource.
             */
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
            'tags' => 'required',
        ]);

        // Crear la categoría
        $category = Category::create($fields);


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
            'tags' => 'required',
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
