@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mi Perfil</h1>
        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalModificarDatos">
            <i class="bi bi-pencil-square me-1"></i> Modificar datos
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-4 mb-md-0">
                    <img src="{{ $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : 'https://via.placeholder.com/150?text=Sin+foto' }}"
                         class="rounded-circle border" width="150" height="150" style="object-fit: cover;">
                </div>

                <div class="col-md-9">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Nombre</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>

                        <dt class="col-sm-4 text-muted">Correo</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4 text-muted">Teléfono</dt>
                        <dd class="col-sm-8">{{ $user->telefono ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted">Plaza</dt>
                        <dd class="col-sm-8">{{ $user->plaza ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted">ORCID iD</dt>
                        <dd class="col-sm-8">{{ $user->orcid_id ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted">Scopus ID</dt>
                        <dd class="col-sm-8">{{ $user->scopus_id ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted">SNI</dt>
                        <dd class="col-sm-8">{{ $user->sni ?? '—' }}</dd>

                     </dl>
                </div>
            </div>
        </div>
    </div>
<div class="d-flex justify-content-between align-items-center mt-5 mb-3">
    <h5 class="mb-0 text-gray-800">Últimas publicaciones</h5>
    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarPublicacion">
        <i class="bi bi-plus-circle me-1"></i> Agregar publicación
    </button>
</div>

@php
    $publicacionesPorAnio = $user->publicaciones->groupBy('anio');
@endphp

@if($publicacionesPorAnio->isEmpty())
    <p class="text-muted small">Aún no has agregado publicaciones.</p>
@else
    <div class="accordion" id="accordionPublicaciones">
        @foreach($publicacionesPorAnio as $anio => $publicaciones)
            <div class="accordion-item border-0 shadow-sm mb-2 rounded">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold text-primary bg-white rounded"
                            type="button" data-bs-toggle="collapse"
                            data-bs-target="#anio-{{ $anio }}">
                        {{ $anio }}
                    </button>
                </h2>
                <div id="anio-{{ $anio }}" class="accordion-collapse collapse" data-bs-parent="#accordionPublicaciones">
                    <div class="accordion-body pt-2">
                        <ul class="list-group list-group-flush">
                            @foreach($publicaciones as $publicacion)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <a href="https://doi.org/{{ $publicacion->doi }}" target="_blank" class="text-decoration-none">
                                        {{ $publicacion->doi }}
                                    </a>
                                    <form action="{{ route('publicaciones.destroy', $publicacion->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta publicación?');" class="ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Modal: Agregar publicación -->
<div class="modal fade" id="modalAgregarPublicacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('publicaciones.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">DOI</label>
                        <input type="text" name="doi" class="form-control" placeholder="10.1000/xyz123" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Año</label>
                        <input type="number" name="anio" class="form-control" min="1900" max="{{ date('Y') + 1 }}" value="{{ date('Y') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Modal: Modificar datos -->
<div class="modal fade" id="modalModificarDatos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">Modificar mis datos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted small">Marca la casilla de cada dato que quieras modificar. Lo que no marques se quedará igual.</p>

                    @php
                        $campos = [
                            'name'      => ['label' => 'Nombre',      'type' => 'text',  'value' => $user->name],
                            'email'     => ['label' => 'Correo',      'type' => 'email', 'value' => $user->email],
                            'telefono'  => ['label' => 'Teléfono',    'type' => 'text',  'value' => $user->telefono],
                            'plaza'     => ['label' => 'Plaza',       'type' => 'text',  'value' => $user->plaza],
                            'orcid_id'  => ['label' => 'ORCID iD',    'type' => 'text',  'value' => $user->orcid_id],
                            'scopus_id' => ['label' => 'Scopus ID',   'type' => 'text',  'value' => $user->scopus_id],
                            'sni'       => ['label' => 'SNI',         'type' => 'text',  'value' => $user->sni],
                            ];
                    @endphp

                    @foreach($campos as $name => $campo)
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <input type="checkbox" class="form-check-input campo-toggle" data-target="input-{{ $name }}">
                            </div>
                            <div class="col">
                                <label class="form-label small mb-1">{{ $campo['label'] }}</label>
                                @if($campo['type'] === 'textarea')
                                    <textarea id="input-{{ $name }}" name="{{ $name }}" class="form-control" rows="2" disabled>{{ old($name, $campo['value']) }}</textarea>
                                @else
                                    <input type="{{ $campo['type'] }}" id="input-{{ $name }}" name="{{ $name }}" class="form-control" value="{{ old($name, $campo['value']) }}" disabled>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="row align-items-center mb-2">
                        <div class="col-auto">
                            <input type="checkbox" class="form-check-input campo-toggle" data-target="input-foto_perfil">
                        </div>
                        <div class="col">
                            <label class="form-label small mb-1">Foto de perfil</label>
                            <input type="file" id="input-foto_perfil" name="foto_perfil" class="form-control" accept="image/png, image/jpeg" disabled>
                            <div class="form-text">JPG o PNG, máx. 2MB.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.querySelectorAll('.campo-toggle').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const target = document.getElementById(this.dataset.target);
            target.disabled = !this.checked;
            if (this.checked && target.type !== 'file') {
                target.focus();
            }
        });
    });

    // Reabre el modal si hubo error de validación (para que el usuario vea qué falló)
    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('modalModificarDatos')).show();
        });
    @endif

    // Valida tamaño de la foto en el navegador antes de enviar
    document.getElementById('input-foto_perfil').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const maxSize = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSize) {
            Swal.fire('Archivo muy grande', 'La imagen no debe pesar más de 2MB.', 'warning');
            e.target.value = '';
        }
    });

    // Confirmación antes de guardar los cambios del perfil
    const formModificarDatos = document.querySelector('#modalModificarDatos form');

    formModificarDatos.addEventListener('submit', function (e) {
        e.preventDefault();

        const seleccionados = document.querySelectorAll('.campo-toggle:checked');

        if (seleccionados.length === 0) {
            Swal.fire('Nada que guardar', 'Marca al menos un dato que quieras modificar.', 'info');
            return;
        }

        const nombresCampos = Array.from(seleccionados).map(function (checkbox) {
            const label = checkbox.closest('.row').querySelector('label');
            return label ? label.textContent.trim() : '';
        }).filter(Boolean);

        Swal.fire({
            title: '¿Guardar cambios?',
            html: 'Vas a modificar: <strong>' + nombresCampos.join(', ') + '</strong>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0dcaf0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Seguir editando',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                formModificarDatos.submit();
            }
        });
    });
</script>
@endpush
