document.addEventListener("DOMContentLoaded", () => {
  const imagen = document.querySelector(".imagen-producto");
  const nombre = document.getElementById("nombreProducto");
  const precioActual = document.querySelector(".precio-actual");
  const precioProducto = document.querySelector(".precio-producto"); // El precio dentro del botón "Agregar"
const desc = document.getElementById("descripcion");
const aler = document.getElementById("alergenos");
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
      desc.textContent = res.descripcion;
      aler.textContent = res.alergenos
      if (precioProducto) {
        precioProducto.textContent = "$0";
      }

      if(res.ingredientes && res.ingredientes.length > 0){

      const listaIngredientes = res.ingredientes
          .map(ing => ing.nombre_ingrediente)
          .join(", ");
          console.log(listaIngredientes)
const elementoIngredientes = document.getElementById("ingredientes");

 if (elementoIngredientes) {
          elementoIngredientes.textContent = listaIngredientes;
        }
      }

    })
    .catch(err => console.error("Error al obtener el producto:", err));

  }

});