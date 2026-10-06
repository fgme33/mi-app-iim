@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 700px;">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-gray-800"><i class="bi bi-cart-plus me-2 text-success"></i>Nueva Solicitud de Compra</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('solicitudes-compra.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Correo para Notificaciones</label>
                    <input type="email" name="email_notificacion" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>

                <div class="mb-3">
    <label class="form-label font-weight-bold">Artículos a solicitar</label>

    <datalist id="catalogoArticulos">
        @foreach($articulos as $nombre)
            <option value="{{ $nombre }}">
        @endforeach
    </datalist>

    <div id="articulos-container">
        <div class="row g-2 mb-2 articulo-row">
            <div class="col-7">
                <input type="text" name="articulos[]" class="form-control" list="catalogoArticulos"
                       placeholder="Ej. Laptop Dell Latitude 5420" value="{{ old('articulos.0') }}" required>
            </div>
            <div class="col-3">
                <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad" min="1"
                       value="{{ old('cantidades.0') }}" required>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-outline-danger w-100 btn-eliminar-articulo" disabled>
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <button type="button" id="btnAgregarArticulo" class="btn btn-sm btn-outline-success mt-1">
        <i class="bi bi-plus-circle me-1"></i> Agregar otro artículo
    </button>
    <div class="form-text">Escribe el nombre del artículo; si ya se ha pedido antes, te aparecerá como sugerencia.</div>
</div>
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Justificación de la Compra</label>
                    <textarea name="justificacion" class="form-control" rows="4" placeholder="Explica por qué se requiere este artículo..." required></textarea>
                </div>

		<div class="mb-3">
        <label class="form-label font-weight-bold">Adjuntar Archivo / Cotización (opcional)</label>
        <input type="file" name="archivo" class="form-control">
        <div class="form-text">Formatos permitidos: PDF, JPG, PNG, DOCX (Máx. 5MB)</div>
    </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('solicitudes-compra.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Guardar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
	document.getElementById('btnAgregarArticulo').addEventListener('click', function () {
    const container = document.getElementById('articulos-container');
    const fila = container.querySelector('.articulo-row').cloneNode(true);
    fila.querySelectorAll('input').forEach(input => input.value = '');
    fila.querySelector('.btn-eliminar-articulo').disabled = false;
    container.appendChild(fila);
    actualizarBotonesEliminarArticulo();
});

document.getElementById('articulos-container').addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-eliminar-articulo');
    if (btn) {
        btn.closest('.articulo-row').remove();
        actualizarBotonesEliminarArticulo();
    }
});

function actualizarBotonesEliminarArticulo() {
    const filas = document.querySelectorAll('.articulo-row');
    filas.forEach(fila => {
        fila.querySelector('.btn-eliminar-articulo').disabled = filas.length === 1;
    });
}
</script>
@endpush
