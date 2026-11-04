<?php
require_once __DIR__ . '/../config/config.php';

// Verificar seguridad: solo administradores
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 1) {
    echo "<script>alert('Acceso denegado.'); window.location.href='index.php';</script>";
    exit;
}

$seccion = $_GET['subpage'] ?? 'productos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Frattelli sin Gluten</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .admin-sidebar { min-height: 100vh; background: #343a40; color: #fff; padding-top: 20px; }
        .admin-sidebar a { color: #cfd8dc; text-decoration: none; display: block; padding: 10px 20px; transition: 0.2s; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: #495057; color: #fff; border-left: 4px solid #ffc107; }
        .admin-content { padding: 30px; }
        .admin-section { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05); }
        .admin-table th { background: #e9ecef; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-2 admin-sidebar d-flex flex-column justify-content-between">
            <div>
                <h4 class="text-center text-warning mb-4">Frattelli Admin</h4>
                <ul class="list-unstyled">
                    <li><a href="index.php?page=admin&subpage=productos" class="<?= ($seccion === 'productos') ? 'active' : '' ?>">📦 Productos</a></li>
                    <li><a href="index.php?page=admin&subpage=usuarios" class="<?= ($seccion === 'usuarios') ? 'active' : '' ?>">👥 Usuarios</a></li>
                    <li><a href="index.php?page=admin&subpage=pedidos" class="<?= ($seccion === 'pedidos') ? 'active' : '' ?>">📋 Ver Pedidos</a></li>
                    <li><a href="index.php?page=admin&subpage=crear_pedido" class="<?= ($seccion === 'crear_pedido') ? 'active' : '' ?>">📝 Crear Pedido</a></li>
                </ul>
            </div>
            <div class="p-3">
                <a href="index.php" class="btn btn-outline-light btn-sm w-100">Volver al Sitio</a>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="col-md-10 admin-content">

            <!-- ================= SECCIÓN: PRODUCTOS ================= -->
            <?php if ($seccion === 'productos' || empty($seccion)): ?>
            <section id="productos" class="admin-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Productos</h2>
                    <button type="button" class="btn btn-success btn-sm" onclick="document.getElementById('form-nuevo-producto').classList.toggle('d-none')">
                        + Nuevo Producto
                    </button>
                </div>

                <!-- FORMULARIO DE EDICIÓN -->
                <?php 
                $edit_id = $_GET['editar'] ?? null;
                if ($edit_id):
                    $q_edit = "SELECT * FROM productos WHERE ProductoID = ?";
                    $stmt_e = mysqli_prepare($conexion, $q_edit);
                    mysqli_stmt_bind_param($stmt_e, "i", $edit_id);
                    mysqli_stmt_execute($stmt_e);
                    $prod_edit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_e));
                    
                    if ($prod_edit):
                ?>
                <div class="admin-form-card mb-4 p-4" style="background: #fff3cd; border: 1px solid #ffeeba; border-radius: 8px;">
                    <h4 class="mb-3">Editar Producto (ID: <?= $prod_edit['ProductoID'] ?>)</h4>
                    <form action="../crud/actualizar_producto.php" method="POST">
                        <input type="hidden" name="ProductoID" value="<?= $prod_edit['ProductoID'] ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Producto</label>
                                <input type="text" name="nombre_producto" class="form-control" value="<?= htmlspecialchars($prod_edit['nombre_producto']) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio ($)</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="<?= $prod_edit['precio'] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock_producto" class="form-control" value="<?= $prod_edit['stock_producto'] ?>" required>
                            </div>

                            <!-- Categoría -->
                            <div class="col-md-6">
                                <label class="form-label">Categoría</label>
                                <select name="categoria" class="form-control" required>
                                    <option value="">-- Seleccionar Categoría --</option>
                                    <?php
                                    $r_cat = mysqli_query($conexion, "SELECT * FROM categorias");
                                    while ($cat = mysqli_fetch_assoc($r_cat)) {
                                        $sel = ($cat['idCategoria'] == $prod_edit['categoria']) ? 'selected' : '';
                                        echo "<option value='{$cat['idCategoria']}' {$sel}>" . htmlspecialchars($cat['nombreCategoria']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Subcategoría -->
                            <div class="col-md-6">
                                <label class="form-label">Subcategoría</label>
                                <select name="subCategoria" class="form-control" required>
                                    <option value="">-- Seleccionar Subcategoría --</option>
                                    <?php
                                    $r_sub = mysqli_query($conexion, "SELECT * FROM subcategorias");
                                    while ($sub = mysqli_fetch_assoc($r_sub)) {
                                        $sel = ($sub['idSubCategoria'] == $prod_edit['subCategoria']) ? 'selected' : '';
                                        echo "<option value='{$sub['idSubCategoria']}' {$sel}>" . htmlspecialchars($sub['nombreSubCategoria']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2"><?= htmlspecialchars($prod_edit['descripcion'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Alérgenos</label>
                                <textarea name="alergenos" class="form-control" rows="2"><?= htmlspecialchars($prod_edit['alergenos'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Imagen (URL o Ruta)</label>
                                <input type="text" name="imagen" class="form-control" value="<?= htmlspecialchars($prod_edit['imagen'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?page=admin&subpage=productos" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Actualizar Producto</button>
                        </div>
                    </form>
                </div>
                <?php endif; endif; ?>

                <!-- FORMULARIO DE AGREGAR PRODUCTO -->
                <div id="form-nuevo-producto" class="admin-form-card mb-4 p-4 d-none" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <h4 class="mb-3">Agregar Nuevo Producto</h4>
                    <form action="../crud/guardar_producto.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Producto</label>
                                <input type="text" name="nombre_producto" class="form-control" required placeholder="Ej: Pan sin tacc">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio ($)</label>
                                <input type="number" step="0.01" name="precio" class="form-control" required placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock_producto" class="form-control" required placeholder="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Categoría</label>
                                <select name="categoria" class="form-control" required>
                                    <option value="">-- Seleccionar Categoría --</option>
                                    <?php
                                    $r_cat = mysqli_query($conexion, "SELECT * FROM categorias");
                                    while ($cat = mysqli_fetch_assoc($r_cat)) {
                                        echo "<option value='{$cat['idCategoria']}'>" . htmlspecialchars($cat['nombreCategoria']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Subcategoría</label>
                                <select name="subCategoria" class="form-control" required>
                                    <option value="">-- Seleccionar Subcategoría --</option>
                                    <?php
                                    $r_sub = mysqli_query($conexion, "SELECT * FROM subcategorias");
                                    while ($sub = mysqli_fetch_assoc($r_sub)) {
                                        echo "<option value='{$sub['idSubCategoria']}'>" . htmlspecialchars($sub['nombreSubCategoria']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Detalles..."></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Alérgenos</label>
                                <textarea name="alergenos" class="form-control" rows="2" placeholder="Ej: Contiene lácteos..."></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Imagen (URL o Ruta)</label>
                                <input type="text" name="imagen" class="form-control" placeholder="assets/img/producto.jpg">
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="document.getElementById('form-nuevo-producto').classList.add('d-none')">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar Producto</button>
                        </div>
                    </form>
                </div>

                <!-- TABLA DE PRODUCTOS -->
                <div class="table-responsive">
                    <table class="table table-striped admin-table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $q_prods = "SELECT * FROM productos WHERE esta_borrado = 0 OR esta_borrado IS NULL";
                            $r_prods = mysqli_query($conexion, $q_prods);
                            while($p = mysqli_fetch_assoc($r_prods)){
                                echo "<tr>
                                        <td>{$p['ProductoID']}</td>
                                        <td>" . htmlspecialchars($p['nombre_producto']) . "</td>
                                        <td>\${$p['precio']}</td>
                                        <td>{$p['stock_producto']}</td>
                                        <td>
                                            <a href='index.php?page=admin&subpage=productos&editar={$p['ProductoID']}' class='btn btn-warning btn-sm'>Editar</a>
                                            <a href='../crud/eliminar_producto.php?id={$p['ProductoID']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de eliminar este producto?\")'>Eliminar</a>
                                        </td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ================= SECCIÓN: USUARIOS ================= -->
            <?php elseif ($seccion === 'usuarios'): ?>
            <section id="usuarios" class="admin-section">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h2>Usuarios Registrados</h2>
                    <button type="button" class="btn btn-success btn-sm" onclick="document.getElementById('form-nuevo-usuario').classList.toggle('d-none')">
                        + Nuevo Usuario
                    </button>
                </div>

                <!-- FORMULARIO NUEVO USUARIO -->
                <div id="form-nuevo-usuario" class="admin-form-card mb-4 p-4 d-none" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <h5 class="mb-3">Registrar Nuevo Usuario / Admin</h5>
                    <form action="../crud/guardar_usuario_admin.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre_usuario" class="form-control form-control-sm" required placeholder="Nombre">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Correo</label>
                                <input type="email" name="correo" class="form-control form-control-sm" required placeholder="correo@ejemplo.com">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="contrasena" class="form-control form-control-sm" required placeholder="••••••••">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rol</label>
                                <select name="admin" class="form-control form-control-sm" required>
                                    <option value="0">Cliente (0)</option>
                                    <option value="1">Administrador (1)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-secondary btn-sm me-2" onclick="document.getElementById('form-nuevo-usuario').classList.add('d-none')">Cancelar</button>
                            <button type="submit" class="btn btn-success btn-sm">Guardar Usuario</button>
                        </div>
                    </form>
                </div>

                <!-- BUSCADOR -->
                <div class="mb-3">
                    <?php $busquedaUsuario = trim($_GET['q_usuario'] ?? ''); ?>
                    <form method="GET" action="index.php" class="d-flex gap-2">
                        <input type="hidden" name="page" value="admin">
                        <input type="hidden" name="subpage" value="usuarios">
                        <input type="text" name="q_usuario" value="<?= htmlspecialchars($busquedaUsuario) ?>" class="form-control form-control-sm" placeholder="Buscar por nombre o correo..." style="max-width: 300px;">
                        <button type="submit" class="btn btn-dark btn-sm">Buscar</button>
                        <?php if (!empty($busquedaUsuario)): ?>
                            <a href="index.php?page=admin&subpage=usuarios" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- TABLA DE USUARIOS -->
                <div class="table-responsive">
                    <table class="table table-striped admin-table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre de Usuario</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $whereUser = "";
                            if (!empty($busquedaUsuario)) {
                                $escapedUser = mysqli_real_escape_string($conexion, $busquedaUsuario);
                                $whereUser = " WHERE nombre_usuario LIKE '%$escapedUser%' OR correo LIKE '%$escapedUser%'";
                            }

                            $q_users = "SELECT * FROM usuarios" . $whereUser . " ORDER BY UsuarioID DESC";
                            $r_users = mysqli_query($conexion, $q_users);
                            
                            if (mysqli_num_rows($r_users) > 0) {
                                while($u = mysqli_fetch_assoc($r_users)){
                                    $esAdminBadge = ($u['admin'] == 1) 
                                        ? '<span class="badge bg-success">Administrador</span>' 
                                        : '<span class="badge bg-secondary">Cliente</span>';
                                    
                                    if ($u['admin'] == 1) {
                                        $btnAdmin = "<a href='../crud/toggle_admin.php?id={$u['UsuarioID']}&action=0' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"¿Quitar privilegios de administrador a este usuario?\")'>Quitar Admin</a>";
                                    } else {
                                        $btnAdmin = "<a href='../crud/toggle_admin.php?id={$u['UsuarioID']}&action=1' class='btn btn-outline-primary btn-sm' onclick='return confirm(\"¿Hacer administrador a este usuario?\")'>Hacer Admin</a>";
                                    }

                                    echo "<tr>
                                            <td>{$u['UsuarioID']}</td>
                                            <td>" . htmlspecialchars($u['nombre_usuario']) . "</td>
                                            <td>" . htmlspecialchars($u['correo']) . "</td>
                                            <td>{$esAdminBadge}</td>
                                            <td>{$btnAdmin}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-muted'>No se encontraron usuarios.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

 <!-- ================= SECCIÓN: VER PEDIDOS ================= -->
<?php elseif ($seccion === 'pedidos'): ?>
<section id="pedidos" class="admin-section">
    <div class="section-header mb-4">
        <h2>Historial de Pedidos</h2>
        <p class="text-muted">Listado de todas las ventas y pedidos registrados en el sistema.</p>
    </div>

    <!-- FILTROS Y BUSCADOR DE PEDIDOS -->
    <div class="card bg-light p-3 mb-4 border-0">
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="subpage" value="pedidos">
            
            <!-- Buscador por usuario -->
            <div class="col-md-4">
                <label class="form-label small fw-bold">Buscar por Cliente</label>
                <?php $busquedaPedido = trim($_GET['q_pedido'] ?? ''); ?>
                <input type="text" name="q_pedido" value="<?= htmlspecialchars($busquedaPedido) ?>" class="form-control form-control-sm" placeholder="Nombre de usuario o correo...">
            </div>

            <!-- Filtro por Estado -->
            <div class="col-md-3">
                <label class="form-label small fw-bold">Filtrar por Estado</label>
                <?php $filtroEstado = $_GET['estado'] ?? '0'; ?>
                <select name="estado" class="form-control form-control-sm">
                    <option value="">-- Todos los estados --</option>
                    <option value="0" <?= ($filtroEstado === '0') ? 'selected' : '' ?>>Pendientes</option>
                    <option value="1" <?= ($filtroEstado === '1') ? 'selected' : '' ?>>Completados</option>
                </select>
            </div>

        <!-- Botones -->
            <div class="col-md-3 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-dark btn-sm w-100">Filtrar</button>
                <?php 
                // Mostrar "Limpiar" solo si hay texto de búsqueda o si el usuario cambió explícitamente el filtro a "Todos los estados" (valor vacío '')
                if (!empty($busquedaPedido) || (isset($_GET['estado']) && $_GET['estado'] === '')): 
                ?>
                    <a href="index.php?page=admin&subpage=pedidos" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                <?php endif; ?>
            </div>

    <div class="table-responsive">
        <table class="table table-striped admin-table align-middle">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Detalle / Productos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Construir condiciones dinámicas para la consulta SQL
                $wherePedido = "WHERE 1=1";
                
                // Filtro por texto de usuario
                if (!empty($busquedaPedido)) {
                    $escapedPed = mysqli_real_escape_string($conexion, $busquedaPedido);
                    $wherePedido .= " AND (u.nombre_usuario LIKE '%$escapedPed%' OR u.correo LIKE '%$escapedPed%')";
                }

                // Filtro por estado
                if ($filtroEstado !== '' && ($filtroEstado === '0' || $filtroEstado === '1')) {
                    $estadoInt = (int)$filtroEstado;
                    $wherePedido .= " AND p.estado = $estadoInt";
                }

                $q_pedidos = "SELECT p.PedidoID, p.estado, u.nombre_usuario, u.correo 
                              FROM pedidos p 
                              JOIN usuarios u ON p.UsuarioID = u.UsuarioID 
                              $wherePedido
                              ORDER BY p.PedidoID DESC";
                $r_pedidos = mysqli_query($conexion, $q_pedidos);

                if (mysqli_num_rows($r_pedidos) > 0) {
                    while ($ped = mysqli_fetch_assoc($r_pedidos)) {
                        $badgeEstado = ($ped['estado'] == 1) 
                            ? '<span class="badge bg-success">Completado</span>' 
                            : '<span class="badge bg-warning text-dark">Pendiente</span>';

                        // Lógica del botón de acción
                        if ($ped['estado'] == 1) {
                            $btnAccionEstado = "<span class='text-muted small'>Finalizado y descontado</span>";
                        } else {
                            $btnAccionEstado = "<a href='../crud/actualizar_estado_pedido.php?id={$ped['PedidoID']}' class='btn btn-success btn-sm' onclick='return confirm(\"¿Estás seguro de marcar este pedido como completado? Esta acción descontará el stock de los productos y no se puede deshacer.\")'>Marcar Completado</a>";
                        }

                        // Buscar los productos de este pedido
                        $pedidoId = $ped['PedidoID'];
                        $q_det = "SELECT dp.cantidad, pr.nombre_producto, pr.precio 
                                  FROM detallepedido dp 
                                  JOIN productos pr ON dp.ProductoID = pr.ProductoID 
                                  WHERE dp.PedidoID = $pedidoId";
                        $r_det = mysqli_query($conexion, $q_det);
                        
                        $productosHtml = "<ul class='mb-0 ps-3'>";
                        while ($det = mysqli_fetch_assoc($r_det)) {
                            $productosHtml .= "<li>{$det['cantidad']}x " . htmlspecialchars($det['nombre_producto']) . " (\${$det['precio']} c/u)</li>";
                        }
                        $productosHtml .= "</ul>";

                        echo "<tr>
                                <td><strong>#{$ped['PedidoID']}</strong></td>
                                <td>" . htmlspecialchars($ped['nombre_usuario']) . "<br><small class='text-muted'>" . htmlspecialchars($ped['correo']) . "</small></td>
                                <td>{$badgeEstado}</td>
                                <td>{$productosHtml}</td>
                                <td>{$btnAccionEstado}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-muted'>No se encontraron pedidos con los filtros seleccionados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>


        <!-- ================= SECCIÓN: CREAR PEDIDO ================= -->
<?php elseif ($seccion === 'crear_pedido'): ?>
<section id="crear_pedido" class="admin-section">
    <div class="section-header mb-4">
        <h2>Crear Nuevo Pedido (Mostrador)</h2>
        <p class="text-muted">Genera un pedido manual para un cliente presencial con múltiples productos y descuenta stock automáticamente.</p>
    </div>

    <div class="admin-form-card p-4 border rounded bg-light" style="max-width: 900px;">
        <form action="../crud/guardar_pedido_mostrador.php" method="POST">
            <!-- Seleccionar Cliente -->
            <div class="mb-4">
                <label class="form-label fw-bold">Seleccionar Cliente:</label>
                <select name="usuario_id" class="form-control" required>
                    <option value="">-- Seleccionar cliente --</option>
                    <?php
                    $q_cli = "SELECT UsuarioID, nombre_usuario, correo FROM usuarios ORDER BY nombre_usuario ASC";
                    $r_cli = mysqli_query($conexion, $q_cli);
                    while ($c = mysqli_fetch_assoc($r_cli)) {
                        echo "<option value='{$c['UsuarioID']}'>" . htmlspecialchars($c['nombre_usuario']) . " (" . htmlspecialchars($c['correo']) . ")</option>";
                    }
                    ?>
                </select>
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Productos del Pedido</h5>
            
            <!-- CONTENEDOR DE FILAS DE PRODUCTOS -->
            <div id="productos-container">
                <div class="row align-items-end mb-3 producto-fila">
                    <div class="col-md-7">
                        <label class="form-label">Artículo</label>
                        <select name="productos[]" class="form-control" required>
                            <option value="">-- Seleccionar producto --</option>
                            <?php
                            // Reiniciamos el puntero o volvemos a consultar los productos
                            $q_pro = "SELECT ProductoID, nombre_producto, precio, stock_producto FROM productos WHERE esta_borrado = 0 OR esta_borrado IS NULL";
                            $r_pro = mysqli_query($conexion, $q_pro);
                            while ($pr = mysqli_fetch_assoc($r_pro)) {
                                echo "<option value='{$pr['ProductoID']}'>[Stock: {$pr['stock_producto']}] " . htmlspecialchars($pr['nombre_producto']) . " - \${$pr['precio']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidades[]" value="1" min="1" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="eliminarFila(this)" disabled>Eliminar</button>
                    </div>
                </div>
            </div>

            <!-- BOTÓN PARA AGREGAR OTRA FILA -->
            <div class="mb-4">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-agregar-producto">+ Agregar otro producto</button>
            </div>

            <div class="mt-4 border-top pt-3">
                <button type="submit" class="btn btn-success btn-lg">Generar Pedido y Descontar Stock</button>
            </div>
        </form>
    </div>
</section>

<!-- SCRIPT JAVASCRIPT PARA AGREGAR/ELIMINAR FILAS DINÁMICAMENTE -->
<script>
document.getElementById('btn-agregar-producto').addEventListener('click', function() {
    const container = document.getElementById('productos-container');
    const primeraFila = container.querySelector('.producto-fila');
    const nuevaFila = primeraFila.cloneNode(true);
    
    // Limpiar los valores de la nueva fila clonada
    nuevaFila.querySelector('select').value = '';
    nuevaFila.querySelector('input').value = '1';
    
    // Habilitar el botón de eliminar en la nueva fila
    const btnEliminar = nuevaFila.querySelector('button');
    btnEliminar.removeAttribute('disabled');
    
    container.appendChild(nuevaFila);
});

function eliminarFila(button) {
    const fila = button.closest('.producto-fila');
    const container = document.getElementById('productos-container');
    // Solo permitir borrar si hay más de una fila
    if (container.querySelectorAll('.producto-fila').length > 1) {
        fila.remove();
    }
}
</script>
<?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>