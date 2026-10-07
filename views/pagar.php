<div class="checkout-container">
    <h2>Finalizar Compra</h2>
    
    <div class="checkout-grid">
        <!-- Formulario de Datos de Envío y Pago -->
        <div class="checkout-form-section">
            <form action="procesar_pago.php" method="POST" class="form-pagar">
                <h3>1. Datos de Envío</h3>
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan Pérez">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" required placeholder="Calle, número, depto">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" required>
                    </div>
                    <div class="form-group">
                        <label for="codigo_postal">Código Postal</label>
                        <input type="text" id="codigo_postal" name="codigo_postal" required>
                    </div>
                </div>

                <h3 class="mt-4">2. Método de Pago</h3>
                <div class="payment-methods">
                    <label class="radio-label">
                        <input type="radio" name="metodo_pago" value="tarjeta" checked> Tarjeta de Crédito / Débito
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="metodo_pago" value="efectivo"> Efectivo / Transferencia
                    </label>
                </div>

                <div class="form-group">
                    <label for="tarjeta">Número de Tarjeta</label>
                    <input type="text" id="tarjeta" name="tarjeta" placeholder="XXXX XXXX XXXX XXXX">
                </div>

                <button type="submit" class="btn-confirmar-pago">Confirmar Pedido</button>
            </form>
        </div>

        <!-- Resumen del Pedido (puedes poblarlo dinámicamente con JS o PHP) -->
        <div class="checkout-summary-section">
            <h3>Resumen de tu Compra</h3>
            <div id="resumen-productos" class="resumen-lista">
                <!-- Aquí puedes inyectar los productos con JS o listarlos desde tu $_SESSION['carrito'] -->
                <p class="text-muted">Cargando productos...</p>
            </div>
            <div class="resumen-totales">
                <div class="linea-total">
                    <span>Subtotal</span>
                    <span id="subtotal-monto">$0.00</span>
                </div>
                <div class="linea-total">
                    <span>Envío</span>
                    <span>Gratis</span>
                </div>
                <hr>
                <div class="linea-total final">
                    <strong>Total a Pagar</strong>
                    <strong id="total-monto">$0.00</strong>
                </div>
            </div>
        </div>
    </div>
</div>