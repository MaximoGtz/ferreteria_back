<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="row">
        @foreach ($products as $item)
            <div class="col-md-4 mb-4">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" 
                         src="{{ $item->images->first()->image ?? 'https://th.bing.com/th/id/OIP._jBYWOpqRGeR052Kh5OyHwHaH8?rs=1&pid=ImgDetMain' }}" 
                         alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name ?? 'dr mario' }}</h5>
                        <p class="card-text">{{ $item->description ?? 'Sin descripción' }}</p>
                        <p class="card-text">Marca: {{ $item->brand->name ?? 'Desconocida' }}</p>
                        <p class="card-text">Precio: ${{ $item->sell_price }}</p>
                        <p class="card-text">stock: {{ $item->stock}}</p>
                        <form action="api/cart/add" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="client_id" value="3">
                            <input type="hidden" name="price" value="{{ $item->sell_price }}">
                            <input type="hidden" name="quantity" value="1">
                          
                            <button type="submit">Agregar al carrito</button>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
