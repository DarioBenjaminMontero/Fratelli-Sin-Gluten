<div class="bodycontenedor">
    <div class="perfilcontenedor">
    <div>
    <img src="../imagenes/perfil.png"alt="foto de perfil">
    </div>
    <h2>nombre del usuario</h2>
    <input type="text" id="nombre" placeholder="<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>">
    <h2>ubicacion</h2>
    <input type="text">
    <div>
    <input type="checkbox" id="i">
    <label for="h">¿vive en un departamento?</label>
    <h4>numero de departamento</h4>
    <input type="text" id="depn" disabled>
    <h4>piso de departamento</h4>
    <input type="text" id="depp" disabled>
    </div>
    <button id="ingresar">aceptar</button>
    </div>
    
</div>
<script src="../JS/perfil.js"></script>