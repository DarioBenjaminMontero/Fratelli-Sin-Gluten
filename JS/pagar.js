document.addEventListener("DOMContentLoaded", () => {
    const contenedorResumen = document.getElementById("resumen-productos");
    const spanSubtotal = document.getElementById("subtotal-monto");
    const spanTotal = document.getElementById("total-monto");

    if (!contenedorResumen) return;

    // Llamamos a tu archivo carrito.php para obtener los productos en formato JSON
    fetch("../carrito.php") 
        .then(res => res.json())
        .then(data => {
            if (data.ok && data.productos && data.productos.length > 0) {
                let html = "";
                let totalGeneral = 0;

                data.productos.forEach(item => {
                    let subtotal = parseFloat(item.precio_total);
                    totalGeneral += subtotal;

                    html += `
                        <div class="resumen-item" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>${item.nombre_producto} (x${item.cantidad})</span>
                            <span>$${subtotal.toFixed(2)}</span>
                        </div>
                    `;
                });

                contenedorResumen.innerHTML = html;
                
                // Actualizar los montos totales en la vista
                if (spanSubtotal) spanSubtotal.textContent = `$${totalGeneral.toFixed(2)}`;
                if (spanTotal) spanTotal.textContent = `$${totalGeneral.toFixed(2)}`;
            } else {
                contenedorResumen.innerHTML = "<p class='text-muted'>Tu carrito está vacío.</p>";
            }
        })
        .catch(error => {
            console.error("Error al cargar el resumen del carrito:", error);
            contenedorResumen.innerHTML = "<p class='text-muted'>Error al cargar el resumen.</p>";
        });
        
});