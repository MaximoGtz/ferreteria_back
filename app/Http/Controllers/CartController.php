<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductsCart;

class CartController extends Controller
{
    public function get()
    {
        return view('cart.add_cart')->with('cartProduct', \Cart::getContent());
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
    
            // Agregar el product al carrito
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
    //el "1" se debera actualizar una vez tengamos el inicio de secion
    $cart = Cart::where('client_id', 1)->first();

    if ($cart) {
        // Si ya existe, actualizar el total
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    } else {
        // Si no existe, crear uno nuevo
        $cart = new Cart();
        $cart->client_id = 1;
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    }

                 $pcart = new ProductsCart();
                 $pcart->cart_id = $cart->id; // Este es el ID del carrito recién creado o actualizado
                 $pcart->product_id = $request->id; // ID del producto enviado en el request
                 $pcart->quantity = $request->quantity; // Cantidad del producto
                 $pcart->subtotal = $request->price * $request->quantity;
                 $pcart->save();

            return redirect()->route('cart.get');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mostrar los errores de validación
            return back()->withErrors($e->validator)->withInput();
        }
    }
    public function quitItem($id, Request $request){
    $cart = Cart::where('client_id', 1)->first();

    if ($cart) {
        // Si ya existe, actualizar el total
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    }
    // Eliminar el ítem del carrito de la biblioteca Cart (si aplica)
    \Cart::remove($request->id);

    // Buscar el registro en la base de datos usando el ID proporcionado
    $cart = Cart::find($id);

    if ($cart) {
        // Si ya existe, actualizar el total
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    }
    // Verificar si el registro existe
    if (!$cart) {
        return redirect()->route('cart.get')->withErrors(['error' => 'El producto no se encontró en el carrito.']);
    }

    // Eliminar el registro de la base de datos
    // $cart->delete();

    // Redirigir con mensaje de éxito
    return redirect()->route('cart.get')->with('success', 'Producto eliminado correctamente.');
}
public function more(Request $request)
{
    \Cart::update($request->id, [
        'quantity' => 1
    ]);
    $cart = Cart::where('client_id', 1)->first();

    if ($cart) {
        // Si ya existe, actualizar el total
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    }
    return redirect()->route('cart.get')->with('success', 'Producto incrementado correctamente.');
}
public function less(Request $request)
{
    \Cart::update($request->id, [
        'quantity' => -1
    ]);
    $cart = Cart::where('client_id', 1)->first();

    if ($cart) {
        // Si ya existe, actualizar el total
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    }
    return redirect()->route('cart.get')->with('success', 'Producto incrementado correctamente.');
}
public function clear()
{
    try {
        // Limpia el carrito de la sesión actual
        \Cart::clear();
            $cart = Cart::where('client_id', 1)->first();

    if ($cart) {
        // Si ya existe, actualizar el total
        $cart->total = \Cart::getSubTotal();
        $cart->save();
    }
        return redirect()->route('cart.get')->with('success', 'El carrito se ha vaciado correctamente.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Hubo un problema al vaciar el carrito: ' . $e->getMessage()]);
    }
}


}    