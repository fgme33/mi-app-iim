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

                        <dt class="col-sm-4 text-muted">DOI</dt>
                        <dd class="col-sm-8">{{ $user->doi ?? '—' }}</dd>
                    </dl>
                </div>
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
                            'doi'       => ['label' => 'DOI',         'type' => 'textarea', 'value' => $user->doi],
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
</script>
@endpush
