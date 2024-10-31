<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Brand::with('images')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create a new brand
        $brand = Brand::create($request->only('name', 'description', 'contact'));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $image = Image::create(['image' => $path]);
                $brand->images()->attach($image->id);
            }
        }

        $brand->load('images');
        foreach ($brand->images as $img) {
            $img->image = url('storage/' . $img->image);
        }

        return response()->json($brand);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return $brand->load('images');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $brand = Brand::findOrFail($id);
        $brand->update($request->only('name', 'description', 'contact'));

        if ($request->hasFile('images')) {
            foreach ($brand->images as $image) {
                if (Storage::disk('public')->exists($image->image)) {
                    Storage::disk('public')->delete($image->image);
                }
                $image->delete();
            }

            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $image = Image::create(['image' => $path]);
                $brand->images()->attach($image->id);
            }
        }

        $brand->load('images');
        foreach ($brand->images as $img) {
            $img->image = url('storage/' . $img->image);
        }

        return response()->json($brand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        foreach ($brand->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }

        $brand->delete();
        return response()->json(['message' => 'Brand and associated images deleted successfully']);
    }
}