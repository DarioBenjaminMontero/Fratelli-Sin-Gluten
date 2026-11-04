<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago Fallido - Fratelli sin Gluten</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .container-pago { max-width: 600px; margin: 60px auto; text-align: center; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .container-pago h1 { color: #dc2626; margin-bottom: 15px; }
        .container-pago p { color: #475569; margin-bottom: 25px; font-size: 1.1rem; }
        .btn-volver { background: #475569; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .btn-volver:hover { background: #334155; color: white; }
    </style>
</head>
<body>
    <div class="container-pago">
        <h1>El pago no pudo completarse</h1>
        <p>Ocurrió un error con el medio de pago o la transacción fue cancelada. No se ha realizado ningun cargo.</p>
        <br>
        <a href="index.php?page=pagar" class="btn-volver">Intentar nuevamente</a>
    </div>
</body>
</html>