<div class="bodycontenedor">
    <div class="perfilcontenedor">
    <div>
    <img src="../imagenes/perfil.png"alt="foto de perfil">
    </div>
    <h2>nombre del usuario</h2>
    <input type="text" id="nombre" placeholder="<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>">
    <h2>contraseña actual</h2>
    <input type="text" id="contra">
    <h2>contraseña nueva</h2>
    <input type="text" id="contran">
    <h2>ubicacion</h2>
    <input type="text" id="ubicacion" placeholder="<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>">
    <div>
    <input type="checkbox" id="i">
    <label for="h">¿vive en un departamento?</label>
    <h4>numero de departamento</h4>
    <input type="text" id="depn" disabled placeholder="<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>">
    <h4>piso de departamento</h4>
    <input type="text" id="depp" disabled placeholder="<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>">
    </div>
    <button id="ingresar">aceptar</button>
    <div id="errores"></div>
    </div>
    
</div>
<script>const productoID = <?php echo json_encode($_GET['producto'] ?? null); ?>;</script>
<script src="../JS/perfil.js"></script>