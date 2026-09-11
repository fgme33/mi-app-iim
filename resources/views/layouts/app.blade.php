<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema de Solicitudes') }}</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        #wrapper { display: flex; width: 100%; min-height: 100vh; }
        #sidebar-wrapper { min-width: 250px; max-width: 250px; background-color: #212529; color: #fff; }
        #page-content-wrapper { flex-grow: 1; display: flex; flex-direction: column; }
        .sidebar-heading { padding: 1.25rem 1.25rem; font-size: 1.2rem; font-weight: bold; }
        .sidebar-link { color: rgba(255,255,255,.75); text-decoration: none; padding: 0.75rem 1.25rem; display: block; }
        .sidebar-link:hover, .sidebar-link.active { color: #fff; background-color: rgba(255,255,255,.1); }
        .sidebar-sublink { padding-left: 2.5rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar Navigation (Lateral) -->
        <div id="sidebar-wrapper" class="border-end">
            <div class="sidebar-heading border-bottom text-white bg-dark">
                <i class="bi bi-box-seam me-2"></i>Mi Sistema
            </div>
            <div class="list-group list-group-flush pt-2">
                
                <!-- Desplegable Solicitudes -->
                <a class="sidebar-link d-flex justify-content-between align-items-center text-decoration-none" 
                   data-bs-toggle="collapse" 
                   href="#solicitudesSubmenu" 
                   role="button" 
                   aria-expanded="true" 
                   aria-controls="solicitudesSubmenu">
                    <span><i class="bi bi-file-earmark-text me-2"></i>Solicitudes</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                
                <div class="collapse show" id="solicitudesSubmenu">
                    <a href="{{ route('solicitudes-compra.index') }}" 
                       class="sidebar-link sidebar-sublink {{ request()->routeIs('solicitudes-compra.*') ? 'active fw-bold text-warning' : '' }}">
                        <i class="bi bi-cart me-2"></i>Compra
                    </a>
                    <a href="{{ route('solicitudes-servicio.index') }}" 
                       class="sidebar-link sidebar-sublink {{ request()->routeIs('solicitudes-servicio.*') ? 'active fw-bold text-info' : '' }}">
                        <i class="bi bi-tools me-2"></i>Servicio
                    </a>
                </div>

            </div>
        </div>

        <!-- Content Area -->
        <div id="page-content-wrapper">
            <!-- Navbar Superior -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 shadow-sm">
                <div class="container-fluid p-0">
                    <span class="navbar-text text-muted">
                        Bienvenido, <strong>{{ Auth::user()->name ?? 'Usuario' }}</strong>
                    </span>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        
                        <!-- Solo Admins -->
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link text-dark fw-semibold me-2" href="{{ route('users.index') }}">
                                    <i class="bi bi-people-fill me-1 text-primary"></i> Usuarios
                                </a>
                            </li>
                        @endif

                        <!-- Mi Perfil -->
                        <li class="nav-item">
                                <a class="nav-link text-dark fw-semibold me-2" href="{{ route('perfil.edit') }}">
				<i class="bi bi-person-circle me-1"></i> Mi perfil
                            </a>
                        </li>

                        <!-- Salir -->
                        <li class="nav-item ms-2">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i> Salir
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>
            </nav>

            <!-- Main Body Content -->
            <main class="container-fluid p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 (faltaba) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
