<?php require_once '../config/config.php'; ?>

<header class="header">

    <!-- Logo -->
    <a href="index.php?page=main" class="logo">
        <img src="../imagenes/fratelli.png" alt="Logo Fratelli" class="logo-img">
    </a>

    <!-- Menú de navegación -->
    <nav class="header-left">

        <button id="botonMenu">☰ Menú</button>

        <ul id="menu">
            <li id="li-inicio">Inicio</li>
        </ul>

    </nav>

    <!-- Usuario -->
    <div class="header-right">

        <?php if(isset($_SESSION['user_id'])): ?>

            <div class="user-profile">

                <span class="saludo">
                    ¡Hola, <?= htmlspecialchars($_SESSION['username']) ?>!
                </span>

                <button id="botonPerfil">Perfil</button>

                <ul>
                    <li id="li-carrito">🛒 Carrito</li>
                </ul>

            </div>

        <?php else: ?>

            <button id="botonRegister">Registrarse</button>
            <button id="botonLogin">Iniciar sesión</button>

        <?php endif; ?>

    </div>

</header>

<script src="../JS/buscarUsuario.js"></script>