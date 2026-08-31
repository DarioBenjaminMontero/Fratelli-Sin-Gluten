const botones = document.querySelectorAll(".categoria")

botones.forEach(boton => {

boton.addEventListener("click", (e) => {
console.log("tocado")
const botonTocado = e.target;
const categoria = botonTocado.dataset.categoria;
window.location.href = `index.php?page=categoria&categoria=${categoria}`;

})

})