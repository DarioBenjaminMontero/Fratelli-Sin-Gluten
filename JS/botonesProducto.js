const  botonMenos = document.getElementById("btn-cantidad-menos");
const botonMas = document.getElementById("btn-cantidad-mas");
const cantidadPlasmada = document.getElementById("cantidad-numero");
const precioProducto = document.querySelector(".precio-producto");
let cantidad = 0; 
let precio =0;
let precioFinal=0;
const stockProducto = document.getElementById("stock-producto");
let stock =0;

fetch("../cargarStock.php",{

method : "POST",
headers: {

"Content-Type": "application/x-www-form-urlencoded"

},
body: "productoID=" + encodeURIComponent(productoID)
}).then(res => res.json()).then(res =>{
stock = res.stock_producto;
precio = res.precio;
stockProducto.textContent = stock;
})




botonMas.addEventListener("click", ()=>{

    if(cantidad < stock){
 cantidad =cantidad+1;
    }
cantidadPlasmada.textContent = cantidad;
precioFinal = precio * cantidad
precioProducto.textContent = `$${precioFinal}`
})

botonMenos.addEventListener("click", ()=>{

if(cantidad <=0){
    }
    else {
        cantidad =cantidad-1;
        cantidadPlasmada.textContent = cantidad;
    }
    precioFinal = precio * cantidad
precioProducto.textContent = `$${precioFinal}`

})
cantidadPlasmada.textContent = cantidad;






