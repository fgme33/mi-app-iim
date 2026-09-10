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
                    <label class="form-label font-weight-bold">Artículo Solicitado</label>
                    <input type="text" name="articulo_solicitado" class="form-control" placeholder="Ej. Laptop Dell Latitude 5420" required>
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
