@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-4 text-gray-800">Mi Perfil</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-3 text-center mb-4">
                        <img src="{{ $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : 'https://via.placeholder.com/150?text=Sin+foto' }}"
                             class="rounded-circle mb-3 border" width="150" height="150" style="object-fit: cover;">
                        <div>
                            <label class="form-label small">Cambiar foto de perfil</label>
                            <input type="file" name="foto_perfil" class="form-control form-control-sm" accept="image/*">
                            @error('foto_perfil') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Plaza</label>
                                <input type="text" name="plaza" class="form-control" value="{{ old('plaza', $user->plaza) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">ORCID iD</label>
                                <input type="text" name="orcid_id" class="form-control" placeholder="0000-0000-0000-0000" value="{{ old('orcid_id', $user->orcid_id) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Scopus ID</label>
                                <input type="text" name="scopus_id" class="form-control" value="{{ old('scopus_id', $user->scopus_id) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">SNI</label>
                                <input type="text" name="sni" class="form-control" value="{{ old('sni', $user->sni) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">DOI</label>
                                <textarea name="doi" class="form-control" rows="2" placeholder="Uno o varios DOI, separados por coma">{{ old('doi', $user->doi) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-info text-white">
                        <i class="bi bi-save me-1"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
