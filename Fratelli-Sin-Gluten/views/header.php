<?php require_once '../config/config.php'; ?>

<header class="header">
<link rel="stylesheet" href="../CSS/cssMenu.css">
    <!-- Logo -->
    <a href="index.php?page=main" class="logo">
        <img src="../imagenes/fratelli.png" alt="Logo Fratelli" class="logo-img">
    </a>

    <!-- Menú de navegación -->
    <nav class="header-left">

        <button id="botonMenu">☰ Menú</button>

        <ul id="menu">
            <li id="li-inicio">INICIO</li>
        </ul>

    </nav>

    <!-- Usuario -->
    <div class="header-right">

        <?php if(isset($_SESSION['user_id'])): ?>

            <div class="user-profile">

                <span class="saludo">
                    ¡Hola, <?= htmlspecialchars($_SESSION['nombre_usuario']) ?>!
                </span>

                <button id="botonPerfil">Perfil</button>

            </div>
        <div class="carrito-container">
    <button id="botonCarrito">🛒 Carrito</button>
    <ul id="menuCarrito">
        <!-- aca va a aparecer lo del select -->
        <li id="li-carrito">Cargando productos...</li>
    </ul>
</div>
        </div>

        <?php else: ?>
            <button id="botonRegister">Registrarse</button>
            <button id="botonLogin">Iniciar sesión</button>

        <?php endif; ?>

    </div>

</header>


<script src="../JS/botoncarrito.js"></script>
<script src="../JS/botonperfil.js"></script>
<script src="../JS/botonlogin.js"></script>
<script src="../JS/botonregistro.js"></script>