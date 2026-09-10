@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 700px;">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-gray-800"><i class="bi bi-tools me-2 text-info"></i>Nueva Solicitud de Servicio</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('solicitudes-servicio.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Correo para Notificaciones</label>
                    <input type="email" name="email_notificacion" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Descripción del Problema / Requerimiento</label>
                    <textarea name="descripcion_problema" class="form-control" rows="4" placeholder="Detalla el problema o servicio requerido..." required></textarea>
                </div>

		<div class="mb-3">
        <label class="form-label font-weight-bold">Adjuntar Archivo / Cotización (opcional)</label>
        <input type="file" name="archivo" class="form-control">
        <div class="form-text">Formatos permitidos: PDF, JPG, PNG, DOCX (Máx. 5MB)</div>
    </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('solicitudes-servicio.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-info text-white"><i class="bi bi-save me-1"></i> Guardar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
