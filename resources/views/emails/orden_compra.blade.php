<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .content {
            white-space: pre-wrap; /* Respetar saltos de línea y espacios del textarea */
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Solicitud de Cotización/Pedido
        </div>
        <div class="content">
            {!! nl2br(e($mensaje)) !!}
        </div>
        <div class="footer">
            <p>Este es un correo generado automáticamente desde el sistema de gestión de inventario.</p>
            <p><strong>{{ $nombreEmpresa }}</strong><br>RIF: {{ $rifEmpresa }}</p>
        </div>
    </div>
</body>
</html>
