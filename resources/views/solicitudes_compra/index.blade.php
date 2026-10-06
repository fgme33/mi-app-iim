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
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $solicitud->folio }}</span></td>
                                <td>{{ $solicitud->usuario->name ?? 'N/A' }}</td>
                                <td>
    					<button type="button" class="btn btn-sm btn-outline-primary btn-ver-detalle"
				            data-bs-toggle="modal" data-bs-target="#modalDetalleArticulos"
				            data-folio="{{ $solicitud->folio }}"
				            data-articulos="{{ $solicitud->detalles->map(fn($d) => ['nombre' => $d->articulo->nombre, 'cantidad' => $d->cantidad])->toJson() }}">
				        <i class="bi bi-list-ul me-1"></i> Ver detalle ({{ $solicitud->detalles->count() }})
					    </button>
				</td>
                                <td>{{ $solicitud->email_notificacion }}</td>
                                <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{-- Estado estático con Insignias (Badges) --}}
                                    @if($solicitud->estado == 'pendiente')
                                        <span class="badge bg-secondary text-white px-3 py-2">Pendiente</span>
                                    @elseif($solicitud->estado == 'en_proceso')
                                        <span class="badge bg-warning text-dark px-3 py-2">En Proceso</span>
                                    @elseif($solicitud->estado == 'completado')
                                        <span class="badge bg-success text-white px-3 py-2">Completado</span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-2">Cancelado</span>
                                    @endif
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
                                <td class="text-end">
                                    {{-- Botón de cancelar solo si el estado es pendiente --}}
                                    @if($solicitud->estado == 'pendiente')
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmarCancelacion({{ $solicitud->id }})">
                                            <i class="bi bi-x-circle me-1"></i> Cancelar
                                        </button>

                                        <form id="cancel-form-{{ $solicitud->id }}" action="{{ route('solicitudes-compra.cancelar', $solicitud->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
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
<!-- Modal: Detalle de artículos -->
<div class="modal fade" id="modalDetalleArticulos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Artículos de la solicitud <span id="detalleFolio" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul id="detalleListaArticulos" class="list-group list-group-flush"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmarCancelacion(id) {
        Swal.fire({
            title: '¿Cancelar solicitud?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar pedido',
            cancelButtonText: 'Volver',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`cancel-form-${id}`).submit();
            }
        });
    }

    document.getElementById('modalDetalleArticulos').addEventListener('show.bs.modal', function (event) {
    const boton = event.relatedTarget;
    const folio = boton.getAttribute('data-folio');
    const articulos = JSON.parse(boton.getAttribute('data-articulos'));

    document.getElementById('detalleFolio').textContent = folio;

    const lista = document.getElementById('detalleListaArticulos');
    lista.innerHTML = '';

    articulos.forEach(function (item) {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = '<span>' + item.nombre + '</span><span class="badge bg-secondary">×' + item.cantidad + '</span>';
        lista.appendChild(li);
    });
    });
</script>
@endpush
