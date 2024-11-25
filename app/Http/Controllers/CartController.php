<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductsCart;

class CartController extends Controller
{
    public function get()
    {
        $cart = Cart::with(['client', 'producto_cart' => function ($query) {
            $query->where('state', 'waiting')->with('producto');
        }])->get();
    
        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Carrito no encontrado'
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $cart
        ], 200);
    }
    public function show()
    {
        $cart = Cart::with(['client', 'producto_cart' => function ($query) {
            $query->where('state', 'waiting')->with('producto');
        }])->where('client_id', 2)->get();
    
        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Carrito no encontrado'
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $cart
        ], 200);
    }
    

    public function add(Request $request)
{
    try {
        // Validar los datos del formulario
        $request->validate([
            'id' => 'required|integer', 
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        // Obtener o crear un carrito asociado al cliente
        $cart = Cart::firstOrCreate(
            ['client_id' => 2], // Cambiar este ID por el del cliente autenticado
            ['total' => 0]
        );

        // Registrar o actualizar el producto en `products_cart`
        $productCart = ProductsCart::where('cart_id', $cart->id)
            ->where('product_id', $request->id)
            ->where('state', 'waiting') // Solo productos en estado "waiting"
            ->first();

        if ($productCart) {
            // Si ya existe, incrementar la cantidad y el subtotal
            $productCart->quantity += $request->quantity;
            $productCart->subtotal += $request->price * $request->quantity;
            $productCart->save();
        } else {
            // Si no existe, crear un nuevo registro
            ProductsCart::create([
                'cart_id' => $cart->id,
                'product_id' => $request->id,
                'quantity' => $request->quantity,
                'subtotal' => $request->price * $request->quantity,
                'state' => 'waiting', // Asegurar que el estado sea "waiting"
            ]);
        }

        // Calcular el nuevo total del carrito basado en los productos "waiting"
        $total = ProductsCart::where('cart_id', $cart->id)
            ->where('state', 'waiting')
            ->sum('subtotal');

        // Actualizar el total del carrito
        $cart->total = $total;
        $cart->save();

        return response()->json([
            'status' => 'success',
            'data' => $cart
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Hubo un problema al añadir el producto: ' . $e->getMessage()]);
    }
}



    public function quitItem(Request $request, $id)
    {
        try {
            // Eliminar el ítem del carrito de la biblioteca Cart


            // Eliminar el registro correspondiente en `products_cart`
            ProductsCart::where('cart_id', Cart::where('client_id', 2)->value('id'))
                        ->where('product_id', $id)->where('state', 'waiting')
                        ->delete();
                        $total=ProductsCart::where('cart_id', Cart::where('client_id', 2)->value('id'))->where('state', 'waiting')
                        ->sum('subtotal');


            // Actualizar el total del carrito
            $cart = Cart::where('client_id', 2)->first();
            if ($cart) {
                $cart->total = $total;
                $cart->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Sell deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un problema al eliminar el producto: ' . $e->getMessage()]);
        }
    }

    public function more(Request $request, $id)
    {
        // Obtener el ID del cliente autenticado
        // $clientId = auth()->id();
        // if (!$clientId) {
        //     return response()->json(['status' => 'error', 'message' => 'Usuario no autenticado.'], 401);
        // }
    
        // Obtener el carrito del cliente
        $cart = Cart::where('client_id', 2)->first();
        if (!$cart) {
            return response()->json(['status' => 'error', 'message' => 'Carrito no encontrado para este cliente.'], 404);
        }
    
        // Verificar si el producto existe en la base de datos
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Producto no encontrado.'], 404);
        }
    
        // Buscar el producto en el carrito con estado 'waiting'
        $productCart = ProductsCart::where('cart_id', $cart->id)
            ->where('product_id', $id)
            ->where('state', 'waiting')
            ->first();
    
        if (!$productCart) {
            return response()->json(['status' => 'error', 'message' => 'Producto no encontrado en el carrito con el estado requerido.'], 404);
        }
    
        // Actualizar la cantidad y el subtotal del producto
        $productCart->quantity += 1;
        $productCart->subtotal = $productCart->quantity * $product->sell_price; // Precio directo de la base de datos
        $productCart->save();
    
        // Recalcular el total del carrito sumando todos los subtotales de los productos en el carrito
        $cart->total = ProductsCart::where('cart_id', $cart->id)->where('state', 'waiting')->sum('subtotal');
        $cart->save();
    
        return response()->json(['status' => 'success', 'data' => $cart], 200);
    }
    





    public function less(Request $request, $id)
    {
   // Obtener el ID del cliente autenticado
        // $clientId = auth()->id();
        // if (!$clientId) {
        //     return response()->json(['status' => 'error', 'message' => 'Usuario no autenticado.'], 401);
        // }
    
        // Obtener el carrito del cliente
        $cart = Cart::where('client_id', 2)->first();
        if (!$cart) {
            return response()->json(['status' => 'error', 'message' => 'Carrito no encontrado para este cliente.'], 404);
        }
    
        // Verificar si el producto existe en la base de datos
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Producto no encontrado.'], 404);
        }
    
        // Buscar el producto en el carrito con estado 'waiting'
        $productCart = ProductsCart::where('cart_id', $cart->id)
            ->where('product_id', $id)
            ->where('state', 'waiting')->where('quantity', '>', 1)
            ->first();
    
        if (!$productCart) {
            return response()->json(['status' => 'error', 'message' => 'Producto no encontrado en el carrito con el estado requerido.'], 404);
        }
    
        // Actualizar la cantidad y el subtotal del producto
        $productCart->quantity -= 1;
        $productCart->subtotal = $productCart->quantity * $product->sell_price; // Precio directo de la base de datos
        $productCart->save();
    
        // Recalcular el total del carrito sumando todos los subtotales de los productos en el carrito
        $cart->total = ProductsCart::where('cart_id', $cart->id)->where('state', 'waiting')->sum('subtotal');
        $cart->save();
    
        return response()->json(['status' => 'success', 'data' => $cart], 200);
    }
    
    public function clear(Request $request, $id)
    {
        try {
            // Eliminar el ítem del carrito de la biblioteca Cart


            // Eliminar el registro correspondiente en `products_cart`
            ProductsCart::where('cart_id', Cart::where('client_id', 2))->where('state', 'waiting')
                        ->delete();
                        $total=ProductsCart::where('cart_id', Cart::where('client_id', 2)->value('id'))->where('state', 'waiting')
                        ->sum('subtotal');


            // Actualizar el total del carrito
            $cart = Cart::where('client_id', 2)->first();
            if ($cart) {
                $cart->total = $total;
                $cart->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Sell deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un problema al eliminar el producto: ' . $e->getMessage()]);
        }
    }
}