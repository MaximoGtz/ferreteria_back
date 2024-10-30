<?php

namespace App\Http\Controllers;

use App\Models\Product;
<<<<<<< HEAD
use Illuminate\Http\Request;
=======
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
>>>>>>> 6c1f19c026fb45f78715aec293191ed6868a7a9f

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
<<<<<<< HEAD
        return Product::all();
=======
        //
>>>>>>> 6c1f19c026fb45f78715aec293191ed6868a7a9f
    }

    /**
     * Store a newly created resource in storage.
     */
<<<<<<< HEAD
    public function store(Request $request)
=======
    public function store(StoreProductRequest $request)
>>>>>>> 6c1f19c026fb45f78715aec293191ed6868a7a9f
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
<<<<<<< HEAD
        return $product;
=======
        //
>>>>>>> 6c1f19c026fb45f78715aec293191ed6868a7a9f
    }

    /**
     * Update the specified resource in storage.
     */
<<<<<<< HEAD
    public function update(Request $request, Product $product)
=======
    public function update(UpdateProductRequest $request, Product $product)
>>>>>>> 6c1f19c026fb45f78715aec293191ed6868a7a9f
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
