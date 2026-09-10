<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; color: #333; padding: 20px; }
        .card { background: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 20px; }
        .badge { background: #0d6efd; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>Nueva Solicitud Registrada</h2>
        </div>
        <p>Hola,</p>
        <p>Tu solicitud ha sido registrada correctamente en el sistema con los siguientes datos:</p>
        
        <ul>
            <li><strong>Folio:</strong> {{ $solicitud->folio }}</li>
            <li><strong>Tipo:</strong> {{ ucfirst($solicitud->tipo) }}</li>
            <li><strong>Estado Actual:</strong> <span class="badge">{{ $solicitud->estado }}</span></li>
            <li><strong>Descripción:</strong> {{ $solicitud->descripcion }}</li>
        </ul>

        <p>Te mantendremos informado sobre cualquier actualización en tu trámite.</p>

        <div class="footer">
            Este es un correo automático enviado por el sistema Mi App. Por favor no respondas a este mensaje.
        </div>
    </div>
</body>
</html>

