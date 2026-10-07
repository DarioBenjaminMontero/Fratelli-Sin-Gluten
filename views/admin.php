<div class="admin-container">
    <!-- Barra lateral o de navegación rápida del panel -->
    <aside class="admin-sidebar">
        <h2>Panel Admin</h2>
        <ul class="admin-menu">
            <li><a href="#productos" class="active">📦 Gestionar Productos</a></li>
            <li><a href="#pedidos">🛒 Ver Pedidos</a></li>
            <li><a href="#usuarios">👥 Usuarios</a></li>
            <li><a href="index.php?page=main">🏠 Volver a la Tienda</a></li>
        </ul>
    </aside>

    <!-- Contenido principal del panel -->
    <main class="admin-content">
        <header class="admin-header">
            <h1>Bienvenido al Panel de Control</h1>
            <p>Aquí puedes administrar los productos, pedidos y la configuración de Fratelli.</p>
        </header>

        <!-- Sección de acciones rápidas o estadísticas -->
        <div class="admin-cards">
            <div class="card">
                <h3>Total Productos</h3>
                <p class="card-number">--</p>
            </div>
            <div class="card">
                <h3>Pedidos Pendientes</h3>
                <p class="card-number">--</p>
            </div>
            <div class="card">
                <h3>Usuarios Registrados</h3>
                <p class="card-number">--</p>
            </div>
        </div>

        <!-- Sección de Gestión de Productos (Ejemplo de tabla) -->
        <section id="productos" class="admin-section">
            <div class="section-header">
                <h2>Listado de Productos</h2>
                <button class="btn-primary">+ Agregar Producto</button>
            </div>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí puedes hacer un bucle en PHP para traer los productos de la BD -->
                        <tr>
                            <td>1</td>
                            <td><img src="../imagenes/producto-ejemplo.jpg" alt="" width="40"></td>
                            <td>Ejemplo de Producto</td>
                            <td>$0.00</td>
                            <td>
                                <button class="btn-edit">✏️ Editar</button>
                                <button class="btn-delete">🗑️ Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>