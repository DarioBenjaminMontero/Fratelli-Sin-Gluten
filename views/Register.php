<div class="registro-container">

    <img src="../imagenes/logo-fratelli.png" alt="Logo" class="logo">

    <h2>Crear Cuenta</h2>
    <p class="subtitulo">Únete a Fratelli Sin Gluten</p>

    <form id="formRegistro" autocomplete="off">

        <div class="form-group">
            <label>Nombre de Usuario</label>
            <input
                type="text"
                id="Usuario"
                name="Usuario"
                placeholder="Ingresa tu usuario"
                required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input
                type="password"
                id="contraseña"
                name="contraseña"
                placeholder="Crea una contraseña"
                required>
        </div>

        <div class="form-group">
            <label>Confirmar contraseña</label>
            <input
                type="password"
                id="contraseña-2"
                name="contraseña-2"
                placeholder="Repite tu contraseña"
                required>
        </div>

        <div class="info">
            El usuario y la contraseña deben tener al menos <b>6 caracteres</b>.
        </div>

        <button type="button" id="botonRegistro" onclick="registrarse()">
            Crear cuenta
        </button>

    </form>

    <div id="error"></div>

    <?php if(isset($_GET['exito'])): ?>
        <div class="success-message">
            Registro exitoso. ¡Ahora puedes iniciar sesión!
        </div>
    <?php endif; ?>

    <div class="login-link">
        ¿Ya tienes cuenta?
        <br>
        <a href="index.php?page=Login">Iniciar sesión</a>
    </div>

</div>

<script src="../JS/registro-js.js"></script>