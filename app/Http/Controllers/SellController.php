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
    // Validación de los campos de la solicitud
    $validated = $request->validate([
        'cart_id' => 'required|exists:carts,id',
        'client_id' => 'required|exists:users,id',
        'direction_id' => 'required|exists:directions,id',
        'purchase_method' => 'nullable|string',
    ]);

    // Obtener el carrito de acuerdo al cart_id recibido
    $cart = Cart::find($request->cart_id);

    if (!$cart) {
        return response()->json(['status' => 'error', 'message' => 'Carrito no encontrado.'], 404);
    }

    // Calcular el total del carrito sumando los subtotales de los productos en el carrito
    $total = ProductsCart::where('cart_id', $request->cart_id)
                         ->where('state', 'waiting') // Solo productos en estado "waiting"
                         ->sum('subtotal');

    // Agregar el IVA al total (si es necesario, ajusta la lógica según cómo se deba calcular)
    $iva = $total * 0.16;
    $totalConIva = $total + $iva;

    // Crear la venta con el total calculado y los datos proporcionados
    $sell = Sell::create([
        'cart_id' => $request->cart_id,
        'client_id' => $request->client_id,
        'direction_id' => $request->direction_id,
        'total' => $totalConIva, // Guardar el total con IVA
        'iva' => $iva,
        'purchase_method' => $request->purchase_method,
    ]);

    // Aquí puedes actualizar el estado de los productos en el carrito
    $cartItems = ProductsCart::where('state', 'waiting')
                             ->where('cart_id', $request->cart_id)
                             ->get();

    foreach ($cartItems as $cartItem) {
        $cartItem->state = 'sell';  // Cambiar el estado de "waiting" a "sell"
        $cartItem->sell_id = $sell->id; // Asociar el producto con la venta
        $cartItem->save();
    }

    // Actualizar el total del carrito (aunque ya se calculó al principio, se puede hacer como validación)
    $cart->total = $total; // Si deseas actualizar el total sin IVA
    $cart->save();

    return response()->json([
        'status' => 'success',
        'data' => $sell
    ], 201);
}


    // Mostrar todas las ventas
    public function index()
    {
        $sells = Cart::with(['client', 'producto_cart' => function ($query) {
            $query->where('state', 'sell')->with('producto');
        }])->get();

        return response()->json([
            'status' => 'success',
            'data' => $sells
        ], 200);
    }

    // Mostrar una venta específica
    public function show($id)
    {
        $sells = Cart::with(['client', 'sell.direction', 'producto_cart' => function ($query) {
            $query->where('state', 'sell')->with('producto');
        }])->where('client_id', $id)->get();

        if ($sells->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'user not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $sells
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
