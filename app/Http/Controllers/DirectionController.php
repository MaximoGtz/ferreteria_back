<?php

namespace App\Http\Controllers;
use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    public function index()
    {
        return Direction::with('user')->get(); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'state' => 'required|max:100',
            'city' => 'required|max:100',
            'postal_code' => 'required|integer|max:99999999999',
            'description' => 'required|max:100',
        ]);

        // Crear la categoría
        $direction = Direction::create($fields);


        return response()->json($direction, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Direction $direction)
    {
        return $direction;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Direction $direction)
    {
        $fields = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'state' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'postal_code' => 'nullable|integer|max:99999999',
            'description' => 'nullable|max:100',
        ]);
        
        $direction->update($fields);

        return response()->json($direction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Direction $direction)
    {
        $direction->delete();

        return response()->json(['message' => 'Direction has been eliminated']);
    }
}
