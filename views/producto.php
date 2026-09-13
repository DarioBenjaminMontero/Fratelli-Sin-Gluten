<!-- producto.php -->
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

    <div class="fila-acciones">
      <button class="btn-ingredientes">🪄 ¡Agrega ingredientes!</button>
    </div>

    <div class="fila-acciones">
      <div class="selector-cantidad">
        <button class="btn-cantidad">-</button>
        <span class="cantidad-numero"></span>
        <button class="btn-cantidad">+</button>
      </div>

      <button class="btn-agregar">
        <span>Agregar</span>
        <div class="precio-boton">
          <span class="precio-tachado-mini"> </span>
          <span class = "precio-producto"><!-- aca va el precio --> </span>
        </div>
      </button>
    </div>
  </div>

  <!-- COLUMNA DERECHA -->
  <div class="card-derecha">
    <h1 id= "nombreProducto"></h1>
    
    <div>
      <div class="descripcion-titulo"></div>
      <p class="descripcion-texto">
       
      </p>
    </div>

    <ul class="lista-detalles">
      <li><strong>Ingredientes principales:</strong> Masa artesanal, dulce de leche, almendras tostadas seleccionadas.</li>
      <li><strong>Ideal para:</strong> Acompañar con un buen café o regalar un mimo dulce.</li>
      <li><strong>Alérgenos:</strong> Contiene frutos secos (almendras), derivados de leche y trigo.</li>
    </ul>
  </div>

  <script>
    const productoID = <?php echo json_encode($_GET['producto'] ?? null); ?>;
  </script>
<script src = "../JS/producto.js">


</script>

</main>