<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;
class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rate' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Crear comentario
        $comment = Comment::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $comment,
        ], 201);
    }

    public function getProductRating($productId)
    {
        $product = Product::findOrFail($productId);

        $averageRating = $product->comments()->avg('rate');

        return response()->json([
            'status' => 'success',
            'product_id' => $product->id,
            'average_rating' => round($averageRating, 2),
        ]);
    }
}


