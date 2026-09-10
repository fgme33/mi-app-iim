@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Nuevo Producto</h1>

    <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-gray-700">Nombre del producto:</label>
            <input type="text" name="name" class="w-full border rounded p-2 focus:outline-blue-500" required>
        </div>

        <div>
            <label class="block text-gray-700">Precio:</label>
            <input type="number" name="price" step="0.01" class="w-full border rounded p-2 focus:outline-blue-500" required>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Guardar</button>
            <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancelar</a>
        </div>
    </form>
@endsection
