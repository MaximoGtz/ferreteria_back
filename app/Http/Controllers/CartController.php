<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductsCart;

class CartController extends Controller
{
    public function get()
    {
        $cart = Cart::with('client', 'producto_cart.producto')->get();
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        // Agregar el producto al carrito de sesión
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => [
                'image' => $request->image,
                'description' => $request->description,
            ]
        ]);

        // Obtener o crear un carrito asociado al cliente
        $cart = Cart::firstOrCreate(
            ['client_id' => 1], // Cambiar este ID por el del cliente autenticado
            ['total' => 0]
        );

        // Actualizar el total del carrito
        $cart->total = \Cart::getSubTotal();
        $cart->save();

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
            \Cart::remove($id);

            // Eliminar el registro correspondiente en `products_cart`
            ProductsCart::where('cart_id', Cart::where('client_id', 1)->value('id'))
                        ->where('product_id', $id)->where('state', 'waiting')
                        ->delete();

            // Actualizar el total del carrito
            $cart = Cart::where('client_id', 1)->first();
            if ($cart) {
                $cart->total = \Cart::getSubTotal();
                $cart->save();
            }

            return redirect()->route('cart.get')->with('success', 'Producto eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un problema al eliminar el producto: ' . $e->getMessage()]);
        }
    }

    public function more(Request $request, $id)
{
    // Actualiza la cantidad del producto en el carrito de sesión
    \Cart::update($id, ['quantity' => \Cart::get($id)->quantity + 1]);

    // Obtiene el carrito asociado al cliente
    $cart = Cart::where('client_id', 1)->first(); // Debes cambiar '1' por el id real del cliente, por ejemplo, auth()->user()->id

    if ($cart) {
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    }

    // Obtener el producto en el carrito para actualizar la cantidad
    $productCart = ProductsCart::where('cart_id', $cart->id)
        ->where('product_id', $id)
        ->where('state', 'waiting')  // Solo los productos con estado 'waiting' deben actualizarse
        ->first();

    if ($productCart) {
        // Actualizar la cantidad y el subtotal del producto en el carrito
        $productCart->quantity += 1;
        $productCart->subtotal = $productCart->quantity * \Cart::get($id)->price; // Recalcular el subtotal

        // Si es necesario, cambiar el estado (por ejemplo, si cambia de 'waiting' a 'sell')
        $productCart->state = 'sell'; // Cambiar el estado según lo que desees

        $productCart->save();
    }

    return response()->json([
        'status' => 'success',
        'data' => $cart
    ], 200);
}





    public function less(Request $request, $id)
    {
        \Cart::update($request->id, ['quantity' => -1]);

        $cart = Cart::where('client_id', 1)->first();
        if ($cart) {
            $cart->total = \Cart::getSubTotal();
            $cart->save();
        }
        $productCart = ProductsCart::where('cart_id', $cart->id)
        ->where('product_id', $id)
        ->where('state', 'waiting')->where('quantity', '>', 1)
        ->first();

    if ($productCart) {
        $productCart->quantity -= 1;
        $productCart->subtotal = $productCart->quantity * \Cart::get($id)->price; // Recalcular subtotal
        $productCart->save();
    }
        return redirect()->route('cart.get')->with('success', 'Producto reducido correctamente.');
    }

    public function clear()
    {
        try {
            // Limpiar el carrito de la sesión actual
            \Cart::clear();

            // Obtener el carrito del cliente
            $cart = Cart::where('client_id', 1)->where('state', 'waiting')->first();

            if ($cart) {
                // Eliminar todos los productos asociados al carrito en `products_cart`
                ProductsCart::where('cart_id', $cart->id)->delete();
                // Reiniciar el total del carrito
                $cart->total = 0;
                $cart->save();
            }

            return redirect()->route('cart.get')->with('success', 'El carrito se ha vaciado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un problema al vaciar el carrito: ' . $e->getMessage()]);
        }
    }
}
