<?php require_once '../config/config.php'; ?>

<header class="header">

    <div id="barraInicio">
        <a href="index.php?page=main" class="logo">
            <img src="../imagenes/logo-fratelli.png" alt="Logo fratelli" class="logo-img">
        </a>
    </div>

    <div class="header-left">
        <button id="botonMenu">Menú</button>
        <ul id="menu">
            <li id="li-inicio">Página Principal</li>
        </ul>
    </div>

    <div class="header-right">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-profile">
                <button id="botonPerfil">Perfil</button>
                <span class="saludo">¡Hola, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <ul>
                    <li id="li-carrito">Ver carrito</li>
                </ul>
            </div>
        <?php else: ?>
            <button id="botonRegister">Registrate!</button>
            <button id="botonLogin">Inicie sesión!</button>
        <?php endif; ?>
    </div>

</header>

<script src="../JS/buscarUsuario.js"></script>