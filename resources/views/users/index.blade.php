@extends('layouts.app')

@section('title', 'Control de Usuarios')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</h5>
        <a href="/mi-app/users/create" class="btn btn-primary btn-sm rounded-pill shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
        </a>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="usersTable" class="table table-hover align-middle mb-0 w-100">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 10%">ID</th>
			<th style="width: 15%">Rol</th>
                        <th style="width: 40%">Nombre Completo</th>
                        <th style="width: 30%">Correo Electrónico</th>
                        <th class="text-end pe-3" style="width: 20%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="ps-3 text-muted fw-bold">#{{ $user->id }}</td>
				<td>
				    @if($user->role == 'admin')
				        <span class="badge bg-danger"><i class="bi bi-shield-lock me-1"></i> Admin</span>
				    @else
				        <span class="badge bg-secondary"><i class="bi bi-person me-1"></i> Usuario</span>
				    @endif
				</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-2 me-2 d-inline-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</span>
                            </td>
				<td class="text-end pe-3">
    <div class="btn-group btn-group-sm shadow-sm" role="group">
        {{-- Botón Editar --}}
        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-secondary" title="Editar">
            <i class="bi bi-pencil-square text-primary"></i>
        </a>

        {{-- Botón Eliminar --}}
        <button type="button" class="btn btn-outline-secondary" title="Eliminar" onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
            <i class="bi bi-trash3 text-danger"></i>
        </button>

        {{-- Formulario oculto para el método DELETE --}}
        <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    </div>
</td>                        



                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            pageLength: 10,
            responsive: true
        });
    });

    function confirmDeleteUser(id, name) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: `¿Estás seguro de que deseas eliminar a: "${name}"? Perderá el acceso al sistema.`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-user-${id}`).submit();
            }
        });
    }
</script>
@endsection

@push('scripts')
<script>
    function confirmDeleteUser(id, name) {
        if (confirm(`¿Estás seguro de que deseas eliminar al usuario "${name}"?`)) {
            document.getElementById(`delete-user-${id}`).submit();
        }
    }
</script>
@endpush
