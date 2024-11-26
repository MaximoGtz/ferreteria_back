<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {

        return User::with('directions')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|string|max:255',
            'rfc' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);


        $user = User::create([
            'name' => $fields['name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'phone' => $fields['phone'],
            'rfc' => $fields['rfc'] ?? null,
            'role' => $fields['role'],
            'password' => Hash::make($fields['password']),
        ]);

        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $nuevoNombre = 'cliente_' . $user->id . '.' . $img->extension();
            $ruta = $img->storeAs('images/image_user', $nuevoNombre, 'public');
            $rutaCompleta = asset('storage/' . $ruta);

            $user->image = $rutaCompleta;
            $user->save();
        }
        $cart = Cart::firstOrCreate(
            ['client_id' => $user->id], // Cambiar este ID por el del cliente autenticado
            ['total' => 0]
        );

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user->load('directions');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $fields = $request->validate([
            'name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'image' => 'nullable|image|max:1024',
            'email' => 'email|unique:users,email,' . $user->id . '|max:255',
            'phone' => 'string|max:255',
            'rfc' => 'nullable|string|max:255',
            'role' => 'string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        if (isset($fields['password'])) {
            $fields['password'] = Hash::make($fields['password']);
        }


        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $nuevoNombre = 'cliente_' . $user->id . '.' . $img->extension();
            $ruta = $img->storeAs('images/image_user', $nuevoNombre, 'public');
            $rutaCompleta = asset('storage/' . $ruta);
            $fields['image'] = $rutaCompleta;
        }

        $user->update($fields);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User has been eliminated']);
    }
}
