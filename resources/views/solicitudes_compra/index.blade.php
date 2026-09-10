@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Solicitudes de Compra</h1>
            <p class="text-muted small mb-0">Gestión de requerimientos y adquisiciones</p>
        </div>
        <a href="{{ route('solicitudes-compra.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Nueva Solicitud
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Folio</th>
                            <th>Usuario</th>
                            <th>Artículo Solicitado</th>
                            <th>Email Notificación</th>
                            <th>Fecha</th>
                            <th>Estado</th>
			    <th>Adjunto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $solicitud->folio }}</span></td>
                                <td>{{ $solicitud->usuario->name ?? 'N/A' }}</td>
                                <td>{{ $solicitud->articulo_solicitado }}</td>
                                <td>{{ $solicitud->email_notificacion }}</td>
				<td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
				<td>
    <select class="form-select form-select-sm cambiar-estado 
        {{ $solicitud->estado == 'completado' ? 'bg-success text-white' : '' }}
        {{ $solicitud->estado == 'en_proceso' ? 'bg-warning text-dark' : '' }}
        {{ $solicitud->estado == 'cancelado' ? 'bg-danger text-white' : '' }}
        {{ $solicitud->estado == 'pendiente' ? 'bg-secondary text-white' : '' }}" 
        data-id="{{ $solicitud->id }}" 
        style="width: 140px; font-weight: 500;">
        
        <option value="pendiente" {{ $solicitud->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        <option value="en_proceso" {{ $solicitud->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
        <option value="completado" {{ $solicitud->estado == 'completado' ? 'selected' : '' }}>Completado</option>
        <option value="cancelado" {{ $solicitud->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
    </select>
</td>
				<td>
				@if($solicitud->archivo)
			        <a href="{{ asset('storage/' . $solicitud->archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
			            <i class="bi bi-paperclip"></i> Ver archivo
			        </a>
				    @else
			        <span class="text-muted small">Sin archivo</span>
				    @endif
				</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No hay solicitudes de compra registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('change', function(e) {
    if (e.target && e.target.classList.contains('cambiar-estado')) {
        const select = e.target;
        const id = select.dataset.id;
        const nuevoEstado = select.value;

        fetch(`/mi-app/solicitudes-compra/${id}/estado`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ estado: nuevoEstado })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                select.className = 'form-select form-select-sm cambiar-estado ' + (
                    nuevoEstado === 'completado' ? 'bg-success text-white' :
                    nuevoEstado === 'en_proceso' ? 'bg-warning text-dark' :
                    nuevoEstado === 'cancelado' ? 'bg-danger text-white' : 'bg-secondary text-white'
                );
            } else {
                alert('No se pudo actualizar el estado.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al actualizar.');
        });
    }
});
</script>
@endpush
