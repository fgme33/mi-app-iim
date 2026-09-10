@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-gray-800"><i class="bi bi-pencil-square me-2 text-primary"></i>Editar Usuario</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nombre Completo</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Rol de Usuario</label>
                    <select name="role" class="form-select" required>
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Usuario (User)</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador (Admin)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nueva Contraseña <span class="text-muted fw-normal">(Opcional)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para conservar la actual">
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
