document.addEventListener("DOMContentLoaded", () => {
  const imagen = document.querySelector(".imagen-producto");
  const nombre = document.getElementById("nombreProducto");
  const precioActual = document.querySelector(".precio-actual");
  const precioProducto = document.querySelector(".precio-producto"); // El precio dentro del botón "Agregar"

  if (imagen && nombre && precioActual) {

    fetch("../producto.php", { 
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: "productoID=" + encodeURIComponent(productoID)
    })
    .then(res => res.json())
    .then(res => {

      if (res.error) {
        console.error(res.error);
        return;
      }
      imagen.src = res.imagen;
      nombre.textContent = res.nombre_producto;
      precioActual.textContent = `$${res.precio}`;

      if (precioProducto) {
        precioProducto.textContent = `$${res.precio}`;
      }

    })
    .catch(err => console.error("Error al obtener el producto:", err));

  }

});