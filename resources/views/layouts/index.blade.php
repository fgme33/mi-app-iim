@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Lista de Productos</h1>
        <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear Producto</a>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="p-3">Nombre</th>
                <th class="p-3">Precio</th>
                <th class="p-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $product->name }}</td>
                <td class="p-3">${{ number_format($product->price, 2) }}</td>
                <td class="p-3 flex justify-center gap-2">
                    <a href="{{ route('products.edit', $product) }}" class="text-yellow-500 hover:underline">Editar</a>
                    
                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Borrar producto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
