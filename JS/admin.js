document.addEventListener("DOMContentLoaded", () => {
    cargarProductos();
});

function cargarProductos() {
    const tbody = document.getElementById("tabla-productos");
    if (!tbody) return;

    // Petición al backend PHP
    fetch("../obtenerProductos.php")
    .then(response => response.json())
    .then(data => {
        if (data.ok && data.productos.length > 0) {
            let html = "";
            
            
            
            data.productos.forEach(prod => {
                let rutaImagen = prod.imagen && prod.imagen.startsWith('http') 
                ? prod.imagen 
                : `../imagenes/${prod.imagen || 'producto-ejemplo.jpg'}`;
                
html += `
    <tr>
        <td>${prod.id}</td>
        <td><img src="${rutaImagen}" alt="" width="40" style="border-radius: 4px; object-fit: cover;"></td>
        <td>${prod.nombre}</td>
        <td>$${parseFloat(prod.precio).toFixed(2)}</td>
        <td>${prod.stock}</td>
        <td>
            <button class="btn-edit" onclick="editarProducto(${prod.id})">✏️ Editar</button>
            <button class="btn-delete" onclick="eliminarProducto(${prod.id})">🗑️ Eliminar</button>
        </td>
    </tr>
`;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align: center;">No hay productos registrados.</td></tr>`;
            }
        })
        .catch(error => {
            console.error("Error al cargar productos:", error);
            tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: red;">Error al conectar con el servidor.</td></tr>`;
        });
}

// Funciones de ejemplo para los botones de acción
function editarProducto(id) {
    alert("Editar producto con ID: " + id);
    // Aquí puedes abrir un modal o redirigir a un formulario de edición
}

function eliminarProducto(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        // Aquí harías otro fetch a un archivo PHP como 'eliminar_producto.php?id=' + id
        console.log("Eliminando producto " + id);
    }
}

function eliminarProducto(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto de la tienda?")) {
    
        fetch(`../eliminarProductos.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
                if (data.ok) {
                    // Recargamos la tabla para que desaparezca el producto eliminado
                    cargarProductos();
                } else {
                    alert("Error: " + data.mensaje);
                }
            })
            .catch(error => {
                console.error("Error en la petición de eliminación:", error.response);
                alert("Hubo un error al conectar con el servidor.");
            });
    }
}