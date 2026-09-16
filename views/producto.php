<main class="producto-contenedor">
  
  <!-- COLUMNA IZQUIERDA -->
  <div class="card-izquierda">
    <div class="imagen-contenedor">
      <img alt="Producto" class="imagen-producto">
      <div class="banner-descuento">50% off</div>
    </div>

    <div class="precio-seccion">
      <span class="precio-anterior">  </span>
      <span class="precio-actual">  </span>
    </div>

    <!-- Modificado: Añadido el Stock al lado del botón -->
    <div class="fila-acciones">
      <button class="btn-ingredientes">🪄 ¡Agrega ingredientes!</button>
      <div class="stock-indicador">
        <span class="stock-etiqueta">Stock disponible:</span>
        <strong id="stock-producto"></strong>
      </div>
    </div>

    <div class="fila-acciones">
      <!-- Modificado: Clases añadidas a los botones para que el CSS funcione -->
      <div class="selector-cantidad">
        <button id="btn-cantidad-menos" class="btn-cantidad">-</button>
        <span id="cantidad-numero" class="cantidad-numero">0</span>
        <button id="btn-cantidad-mas" class="btn-cantidad">+</button>
      </div>

      <button class="btn-agregar">
        <span>Agregar</span>
        <div class="precio-boton">
          <span class="precio-tachado-mini"> </span>
          <span class="precio-producto"><!-- aca va el precio --> </span>
        </div>
      </button>
    </div>
  </div>

  <!-- COLUMNA DERECHA -->
  <div class="card-derecha">
    <h1 id="nombreProducto"></h1>
    
    <div>
      <div class="descripcion-titulo"></div>
      <p class="descripcion-texto"></p>
    </div>

    <ul class="lista-detalles">
      <li><strong>Ingredientes principales:</strong> <strong id="ingredientes"></strong></li>
      <li><strong>Descripcion:</strong> <strong id="descripcion"></strong></li>
      <li><strong>Alérgenos:</strong><strong id="alergenos"></strong></li>
    </ul>
  </div>

  <script>
    const productoID = <?php echo json_encode($_GET['producto'] ?? null); ?>;
  </script>
  <script src="../JS/producto.js"></script>
  <script src="../JS/botonesProducto.js"></script>

</main>