<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductCart;
class SellController extends Controller
{
    public function index(){
        $carts = Cart::with('User')->with('producto_cart.producto')->get();
        dd($carts);
    }

    public function show($id)
    {
        $carts = Cart::with('User')->with('producto_cart.producto')->find($id);
        dd($carts);  
    }
    
    public function store(Request $request){
        DB::beginTransaction();
        try{
            // Crear cart
            $cart = new Cart();
            $cart->cart_id = $request->id_cliente;
            $cart->client_id = $request->client_id; 
            $cart->total = $request->total;  
            $cart->iva = $request->iva; 
            $cart->purchase_method = $request->purchase_method;
            $cart->save();

            
            
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function destroy($id){
        $cart = Cart::find($id);
        $cart->producto_cart()->delete();
        $cart->delete();
        dd($cart);
    }
}
