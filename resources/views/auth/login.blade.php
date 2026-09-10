<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mi App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-shield-lock-fill fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Bienvenido</h4>
                        <p class="text-muted small">Ingresa tus credenciales para acceder</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger p-2 small mb-3 border-0 rounded-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="/mi-app/login" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-muted small">Correo Electrónico</label>
                            <input type="email" class="form-control rounded-pill" id="email" name="email" required placeholder="usuario@email.com" value="{{ old('email') }}">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-muted small">Contraseña</label>
                            <input type="password" class="form-control rounded-pill" id="password" name="password" required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">
                            Entrar <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
