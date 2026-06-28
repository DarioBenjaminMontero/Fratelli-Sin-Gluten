  <div class="login-container">
    <img src="../imagenes/fratelli.png" alt="Logo" class="logo" />
    <h2>Iniciar Sesión</h2>
    <form action="#" method="POST">
      <div class="form-group">
        <label for="Usuario">Nombre De Usuario</label>
        <input type="text" id="emLogin" name="USER"  required />
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="passwordLogin" name="password" required />
      </div>
      <button type="button" id="boton-Login">Entrar</button>
    </form>
    <br>
    <label id="errores"></label>
    <div class="register-link">
      ¿No tienes cuenta? <a href="index.php?page=Registro">Regístrate</a>
    </div>
  </div>
  <script src="../JS/login.js"></script>