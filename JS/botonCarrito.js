document.addEventListener("DOMContentLoaded", () => {
    const botonCarrito = document.getElementById("botonCarrito");
    const menuCarrito = document.getElementById("menuCarrito");

    if (!botonCarrito || !menuCarrito) return;

    function actualizarCarrito() {
        menuCarrito.innerHTML = "<p style='padding: 20px;'>Cargando carrito...</p>";

        fetch("../carrito.php")
            .then(res => res.json())
            .then(data => {
                console.log("Respuesta JSON:", data);

                if (data.ok && data.productos.length > 0) {
                    let contenidoHtml = "<h3>Tu Carrito</h3><ul class='lista-carrito-items'>";
                    let totalGeneral = 0;

                    data.productos.forEach(item => {
                        let subtotal = parseFloat(item.precio_total);
                        totalGeneral += subtotal;

                        contenidoHtml += `
                            <li class="item-carrito">
                                <img src="${item.imagen}" alt="${item.nombre_producto}" class="img-carrito">
                                <div class="info-item">
                                    <h4>${item.nombre_producto}</h4>
                                    <p>Cantidad: ${item.cantidad}</p>
                                    <p>Precio unitario: $${item.precio}</p>
                                    <p><strong>Total: $${subtotal.toFixed(2)}</strong></p>
                                </div>
                                <div class="controlCantidad">
                                    <button type="button" class="btn-mas" data-id="${item.ProductoID}">+</button>
                                    <button type="button" class="btn-menos" data-id="${item.ProductoID}">-</button>
                                    <button type="button" class="borrar-producto" data-id="${item.ProductoID}">❌</button>
                                </div>
                            </li>
                        `;
                    });

                    contenidoHtml += "</ul>";
                    contenidoHtml += `
                        <div class="carrito-total-general">
                            <hr>
                            <p><strong>Total General:</strong> $${totalGeneral.toFixed(2)}</p>
                            <button class="btn-finalizar">Finalizar Compra</button>
                        </div>
                    `;

                    menuCarrito.innerHTML = contenidoHtml;
                } else {
                    menuCarrito.innerHTML = "<p style='padding: 20px;'>Tu carrito está vacío.</p>";
                }
            })
            .catch(error => {
                console.error("Error al cargar el carrito:", error);
                menuCarrito.innerHTML = "<p style='padding: 20px;'>Error al cargar el carrito.</p>";
            });
    }

  //abrir cerrar carrito
    botonCarrito.addEventListener("click", (e) => {
        e.stopPropagation();
        menuCarrito.classList.toggle("open");
        document.body.classList.toggle("carrito-abierto");

        if (menuCarrito.classList.contains("open")) {
            actualizarCarrito();
        }
    });

    // Delegación de eventos para los botones de cantidad y borrado (+, -, ❌)
    menuCarrito.addEventListener("click", (e) => {
        e.stopPropagation();

        const boton = e.target.closest("[data-id]");
        if (!boton) return; // Si no se hizo clic en un elemento con data-id, ignora

        const id = boton.dataset.id;

        if (boton.classList.contains("btn-mas")) {
            modificarCantidad(id, 'aumentar');
        } else if (boton.classList.contains("btn-menos")) {
            modificarCantidad(id, 'disminuir');
        } else if (boton.classList.contains("borrar-producto")) {
            eliminarProducto(id);
        }
    });
    // Evitar que los clics dentro del menú lo cierren
    menuCarrito.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    // Cerrar carrito al hacer clic fuera
    document.addEventListener("click", () => {
        if (menuCarrito.classList.contains("open")) {
            menuCarrito.classList.remove("open");
            document.body.classList.remove("carrito-abierto");
        }
    });

    // Funciones que interactúan con el backend en PHP
    function modificarCantidad(id, accion) {
        fetch(`../carritoAcciones.php?accion=${accion}&id=${id}`)
            .then(res => res.json())
            .then(response => {
                if (response.ok) {
                    actualizarCarrito(); // Refresca el carrito llamando a tu carrito.php original
                } else {
                    console.error(response.error);
                }
            })
            .catch(error => console.error("Error al actualizar cantidad:", error));
    }

    function eliminarProducto(id) {
        fetch(`../carritoAcciones.php?accion=eliminar&id=${id}`)
            .then(res => res.json())
            .then(response => {
                if (response.ok) {
                    actualizarCarrito(); // Refresca el carrito llamando a tu carrito.php original
                } else {
                    console.error(response.error);
                }
            })
            .catch(error => console.error("Error al eliminar producto:", error));
    }
});