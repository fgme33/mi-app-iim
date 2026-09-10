<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Sistema de Solicitudes</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .wrapper { display: flex; width: 100%; min-height: calc(100vh - 60px); }
        /* Sidebar Styling */
        #sidebar { min-width: 260px; max-width: 260px; background: #1e293b; color: #fff; transition: all 0.3s; }
        #sidebar .sidebar-header { padding: 20px; background: #0f172a; border-bottom: 1px solid #334155; }
        #sidebar ul.components { padding: 15px 0; }
        #sidebar ul p { padding: 10px 20px; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        #sidebar ul li a { padding: 12px 20px; font-size: 0.95rem; display: block; color: #cbd5e1; text-decoration: none; border-left: 4px solid transparent; }
        #sidebar ul li a:hover, #sidebar ul li a.active { color: #fff; background: #334155; border-left-color: #3b82f6; }
        #sidebar ul li a.disabled { color: #64748b; pointer-events: none; }
        /* Content Area */
        #content { width: 100%; padding: 25px; }
        .navbar-custom { background-color: #ffffff; border-bottom: 1px solid #e2e8f0; height: 60px; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <!-- 1. HEADER SUPERIOR DE LA IMAGEN -->
    <nav class="navbar navbar-expand-lg navbar-custom px-4 sticky-top">
        <div class="container-fluid p-0">
            <a class="navbar-brand fw-bold text-primary fs-4" href="#">
                <i class="bi bi-layers-half me-2"></i>Header App
            </a>
            
            <div class="d-flex align-items-center ms-auto">
                <!-- Mi Perfil y Salir -->
                <div class="dropdown me-3">
                    <button class="btn btn-light rounded-pill dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1 text-primary"></i> Mi Perfil
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-menu-item dropdown-item" href="#"><i class="bi bi-person me-2"></i> Configuración</a></li>
                        <li><a class="dropdown-menu-item dropdown-item" href="#"><i class="bi bi-shield-check me-2"></i> Rol: Admin</a></li>
                    </ul>
                </div>
                
                <a href="/mi-app/logout" class="btn btn-outline-danger rounded-pill px-3 fw-semibold">
                    <i class="bi bi-box-arrow-right me-1"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <!-- 2. SIDEBAR IZQUIERDO -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h6 class="mb-0 text-white font-monospace"><i class="bi bi-menu-button-wide me-2"></i>PANEL DE CONTROL</h6>
            </div>

            <ul class="list-unstyled components">
                <p>Solicitudes</p>
                <li>
                    <a href="#" class="active" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
                        <i class="bi bi-plus-circle me-2 text-info"></i> Servicio
                    </a>
                </li>
                <li>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
                        <i class="bi bi-cart-plus me-2 text-success"></i> Compra
                    </a>
                </li>
                <li>
                    <a href="#" class="disabled text-decoration-line-through">
                        <i class="bi bi-airplane me-2"></i> Viaje <span class="badge bg-secondary float-end">Inactivo</span>
                    </a>
                </li>

                <p class="mt-3">Consultas</p>
                <li>
                    <a href="#"><i class="bi bi-search me-2 text-warning"></i> Consultar Solicitudes</a>
                </li>
                <li>
                    <a href="/mi-app/users"><i class="bi bi-people me-2"></i> Usuarios y Roles</a>
                </li>
            </ul>
        </nav>

        <!-- 3. ÁREA PRINCIPAL -->
        <div id="content">
            <div class="container-fluid p-0">
                
                <!-- Encabezado de Sección y Botón Nueva Solicitud -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Gestión de Solicitudes</h3>
                        <p class="text-muted small mb-0">Administra las peticiones de compra y servicio creadas en el sistema.</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
                        <i class="bi bi-file-earmark-plus me-1"></i> Nueva Solicitud
                    </button>
                </div>

                <!-- Filtros del Boceto (Estado) -->
                <div class="card card-custom p-3 mb-4 bg-white">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary small mb-1"><i class="bi bi-funnel me-1"></i> Filtrar por Estado:</label>
                            <select class="form-select rounded-pill" id="filterEstado">
                                <option value="">Todos los Estados</option>
                                <option value="Inicio">Inicio / Borrador</option>
                                <option value="Proceso">En Proceso</option>
                                <option value="Atencion">En Atención</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-9 text-end">
                            <span class="badge bg-primary-subtle text-primary p-2 rounded-pill px-3 border border-primary-subtle">
                                <i class="bi bi-info-circle me-1"></i> Vista adaptada para Rol: <strong>Administrador</strong>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tabla Interactiva -->
                <div class="card card-custom p-4 bg-white">
                    <div class="table-responsive">
                        <table id="tablaSolicitudes" class="table table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Folio</th>
                                    <th>Tipo</th>
                                    <th>Solicitante</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Archivos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="text-primary">SOL-12345</strong></td>
                                    <td><span class="badge bg-info text-dark">Servicio</span></td>
                                    <td>Juan Pérez</td>
                                    <td>Mantenimiento de Servidor Rack 2</td>
                                    <td><span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>En Proceso</span></td>
                                    <td><a href="#" class="btn btn-sm btn-light border"><i class="bi bi-paperclip"></i> 2</a></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-x-circle"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary">SOL-12346</strong></td>
                                    <td><span class="badge bg-success">Compra</span></td>
                                    <td>Ana Gómez</td>
                                    <td>Compra de Laptop i7 de 16GB RAM</td>
                                    <td><span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>En Atención</span></td>
                                    <td><a href="#" class="btn btn-sm btn-light border"><i class="bi bi-paperclip"></i> 1</a></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-x-circle"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL DE NUEVA SOLICITUD (Soporta adjuntar archivos y envio de correo) -->
    <div class="modal fade" id="modalNuevaSolicitud" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-plus me-2"></i>Crear Nueva Solicitud</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formSolicitud">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Tipo de Solicitud</label>
                                <select class="form-select rounded-3" required>
                                    <option value="servicio">Servicio</option>
                                    <option value="compra">Compra</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Correo Notificación</label>
                                <input type="email" class="form-control rounded-3" placeholder="notificar@empresa.com" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Descripción Detallada</label>
                                <textarea class="form-control rounded-3" rows="3" placeholder="Explica detalladamente la solicitud..." required></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted"><i class="bi bi-paperclip me-1"></i>Adjuntar Archivos (PDF, PNG, JPG)</label>
                                <input type="file" class="form-control rounded-3" multiple>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-send me-1"></i> Enviar y Notificar por Correo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tablaSolicitudes').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                pageLength: 5
            });
        });
    </script>
</body>
</html>
