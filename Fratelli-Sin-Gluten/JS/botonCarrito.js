document.addEventListener("DOMContentLoaded", () => {
    const botonCarrito = document.getElementById("botonCarrito");
    const menuCarrito = document.getElementById("menuCarrito");

    if (botonCarrito && menuCarrito) {
        botonCarrito.addEventListener("click", (e) => {
            e.stopPropagation();
            menuCarrito.classList.toggle("open");
            document.body.classList.toggle("carrito-abierto");

            // Mostrar estado de carga mientras responde el servidor
            menuCarrito.innerHTML = "<p style='padding: 20px;'>Cargando carrito...</p>";

            fetch(`../carrito.php`)
                .then(res => res.json())
                .then(data => {
                    console.log("Respuesta JSON:", data);

                    if (data.ok && data.productos.length > 0) {
                        let contenidoHtml = "<h3>Tu Carrito</h3><ul class='lista-carrito-items'>";
                        let totalGeneral = 0; // Variable para acumular la suma de todos los productos

                        data.productos.forEach(item => {
                            let subtotal = parseFloat(item.precio_total);
                            totalGeneral += subtotal; // Sumamos cada total al acumulador

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
    <button type="button" >+</button>
     <button type="button" >-</button>
     <button class="borrar-producto">❌</button>
</div>
                    
                </li>
            `;
                        });

                        contenidoHtml += "</ul>";

                        // Agregamos la sección del Total General al final de la lista
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
        });

        menuCarrito.addEventListener("click", (e) => {
            e.stopPropagation();
        });

        document.addEventListener("click", () => {
            if (menuCarrito.classList.contains("open")) {
                menuCarrito.classList.remove("open");
                document.body.classList.remove("carrito-abierto");
            }
        });

      function actualizarCarrito(){
            menuCarrito.innerHTML = "<p style='padding: 20px;'>Cargando carrito...</p>";

            fetch(`../carrito.php`)
                .then(res => res.json())
                .then(data => {
                    console.log("Respuesta JSON:", data);

                    if (data.ok && data.productos.length > 0) {
                        let contenidoHtml = "<h3>Tu Carrito</h3><ul class='lista-carrito-items'>";
                        let totalGeneral = 0; // Variable para acumular la suma de todos los productos

                        data.productos.forEach(item => {
                            let subtotal = parseFloat(item.precio_total);
                            totalGeneral += subtotal; // Sumamos cada total al acumulador

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
    <button type="button" >+</button>
     <button type="button" >-</button>
     <button class="borrar-producto">❌</button>
</div>
                    
                </li>
            `;
                        });

                        contenidoHtml += "</ul>";

                        // Agregamos la sección del Total General al final de la lista
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
        
    }
    
}
);
