<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

// se usa implements HasMiddleware y la funcion middleware para proteger las funciones de los usuarios que no están registrados, necesita el barrelToken para poder entrar a la funcion
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
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'description' => 'required',
        ]);
        $category = Category::create(($fields));
        // return 'Ok';
        return ['category' => $category];

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $category;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $fields = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'description' => 'required',
        ]);
        $category->update($fields);
        // return 'Ok';
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return ['message' => 'Category has been eliminated'];
    }
}
