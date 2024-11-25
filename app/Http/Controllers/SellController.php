<?php
namespace App\Http\Controllers;

use App\Models\Sell;
use App\Models\ProductsCart;
use App\Models\Cart;
use Illuminate\Http\Request;

class SellController extends Controller
{
    // Crear una venta
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'client_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'iva' => 'required|numeric',
            'purchase_method' => 'nullable|string',
        ]);

        // Crear la venta
        $sell = Sell::create($validated);

        // Aquí también puedes actualizar el estado del carrito si lo deseas
        $cartItems = ProductsCart::where('state', 'waiting')
        ->where('cart_id', $request->cart_id)
        ->get(); 

        foreach ($cartItems as $cartItem) {
        $cartItem->state = 'sell';  // Cambiar el estado del carrito si lo necesitas
        $cartItem->save();  // Guardar cada uno de los cambios  
        }

        return response()->json([
            'status' => 'success',
            'data' => $sell
        ], 201);
    }

    // Mostrar todas las ventas
    public function index()
    {
        $sells = Sell::with('cart.producto_cart.producto', 'client')->get();

        return response()->json([
            'status' => 'success',
            'data' => $sells
        ], 200);
    }

    // Mostrar una venta específica
    public function show($id)
    {
        $sell = Sell::with('cart.producto_cart.producto', 'client')->find($id);

        if (!$sell) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sell not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $sell
        ], 200);
    }

    // Eliminar una venta
    public function destroy($id)
    {
        $sell = Sell::find($id);
        if (!$sell) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sell not found'
            ], 404);
        }

        $sell->delete();  // Eliminar la venta

        return response()->json([
            'status' => 'success',
            'message' => 'Sell deleted successfully'
        ], 200);
    }
}
