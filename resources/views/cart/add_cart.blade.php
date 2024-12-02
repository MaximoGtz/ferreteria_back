<h1>Detalles del Carrito</h1>

@forelse ($cart as $item)
    <div class="cart-item">
        <h2>Carrito #{{ $item['id'] }}</h2>
        <p><strong>Cliente:</strong> {{ $item['client']['name'] }} {{ $item['client']['last_name'] }}</p>
        <p><strong>Total:</strong> ${{ $item['total'] }}</p>
        <p><strong>Descuento:</strong> {{ $item['discount'] ?? 'N/A' }}</p>
        <p><strong>Creado:</strong> {{ $item['created_at'] }}</p>
        
        <h3>Productos en el Carrito:</h3>
        @forelse ($item['producto_cart'] as $producto_cart)
            <div class="producto">
                <p><strong>Nombre:</strong> {{ $producto_cart['producto']['name'] }}</p>
                <p><strong>Cantidad:</strong> {{ $producto_cart['quantity'] }}</p>
                <p><strong>Precio Unitario:</strong> ${{ $producto_cart['producto']['sell_price'] }}</p>
                <p><strong>Subtotal:</strong> ${{ $producto_cart['subtotal'] }}</p>
                <p><strong>Estado:</strong> {{ $producto_cart['state'] }}</p>
                <form action="/api/cart/{{ $producto_cart['producto']['id'] }}/less" method="post">
                    @csrf
                    @method('PUT') <!-- Campo oculto para usar el método PUT -->
                    <input type="hidden" name="id" value="{{ $producto_cart['id'] }}">
                    <button type="submit"
                        class="group rounded-[50px] border border-gray-200 shadow-sm shadow-transparent p-2.5 flex items-center justify-center bg-white transition-all duration-500 hover:shadow-gray-200 hover:bg-gray-50 hover:border-gray-300 focus-within:outline-gray-300">
                        <svg class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                            width="18" height="19" viewBox="0 0 18 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.75 9.5H14.25M9 14.75V4.25" stroke="" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>  
                <form action="/api/cart/{{ $producto_cart['producto']['id'] }}/more" method="post">
                    @csrf
                    @method('PUT') <!-- Campo oculto para usar el método PUT -->
                    <input type="hidden" name="id" value="{{ $producto_cart['id'] }}">
                    <button type="submit"
                        class="group rounded-[50px] border border-gray-200 shadow-sm shadow-transparent p-2.5 flex items-center justify-center bg-white transition-all duration-500 hover:shadow-gray-200 hover:bg-gray-50 hover:border-gray-300 focus-within:outline-gray-300">
                        <svg class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                            width="18" height="19" viewBox="0 0 18 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.75 9.5H14.25M9 14.75V4.25" stroke="" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>     
                <form action="/api/cart/{{ $producto_cart['producto']['id'] }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $producto_cart['id'] }}">
                    <button type="submit"
                        class="rounded-full group flex items-center justify-center focus-within:outline-red-500">
                        <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle class="fill-red-50 transition-all duration-500 group-hover:fill-red-400"
                                cx="17" cy="17" r="17" fill="" />
                            <path class="stroke-red-500 transition-all duration-500 group-hover:stroke-white"
                                d="M14.1673 13.5997V12.5923C14.1673 11.8968 14.7311 11.333 15.4266 11.333H18.5747C19.2702 11.333 19.834 11.8968 19.834 12.5923V13.5997M19.834 13.5997C19.834 13.5997 14.6534 13.5997 11.334 13.5997C6.90804 13.5998 27.0933 13.5998 22.6673 13.5997C21.5608 13.5997 19.834 13.5997 19.834 13.5997ZM12.4673 13.5997H21.534V18.8886C21.534 20.6695 21.534 21.5599 20.9807 22.1131C20.4275 22.6664 19.5371 22.6664 17.7562 22.6664H16.2451C14.4642 22.6664 13.5738 22.6664 13.0206 22.1131C12.4673 21.5599 12.4673 20.6695 12.4673 18.8886V13.5997Z"
                                stroke="#EF4444" stroke-width="1.6" stroke-linecap="round" />
                        </svg>
                    </button>
                </form>           
            </div>
        @empty
            <p>No hay productos en este carrito.</p>
        @endforelse
    </div>
@empty
    <p>No se encontraron carritos.</p>
@endforelse
<form action="/api/sells/" method="post">
    @csrf
    <input type="hidden" name="cart_id" value="{{ $item['id'] }}">
    <input type="hidden" name="client_id" value="{{ $item['client']['id'] }}">
    <input type="hidden" name="total" value="{{ $item['total'] }}">
    <input type="hidden" name="iva" value="{{ $item['total'] * 0.16 }}">
    <button type="submit" class="btn btn-primary">Buy</button>
</form>

