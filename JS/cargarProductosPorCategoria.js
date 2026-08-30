
const divProductos = document.getElementById("Productos");
let arraySubCategorias = [];
if (categoria) {
    fetch("../cargarProductosPorCategoria.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "categoria=" + encodeURIComponent(categoria)
    }).then(res => res.json()).then(res => {
            res.forEach(producto => {

                if (!arraySubCategorias.includes(producto.nombreSubCategoria)) {
                    arraySubCategorias.push(producto.nombreSubCategoria);
                }
            })
            for (let i = 0; i < arraySubCategorias.length; i++) {
                let divSubCategoria = document.createElement("div");
                divSubCategoria.className = arraySubCategorias[i]
                let titulo = document.createElement("h2");
                
let textoLimpio = arraySubCategorias[i].replace(/_/g, " ");
    
   
    titulo.textContent = textoLimpio.charAt(0).toUpperCase() + textoLimpio.slice(1);
    divSubCategoria.appendChild(titulo);
                divProductos.appendChild(divSubCategoria);
            }
            res.forEach(producto => {
    let divTextoInfo = document.createElement("div");
    divTextoInfo.className = "producto-info";
    
    let nombreProducto = document.createElement("h3");
    nombreProducto.textContent = producto.nombre_producto;
    divTextoInfo.appendChild(nombreProducto);


    let img = document.createElement("img");
    img.src = producto.imagen;
    img.alt = producto.nombre_producto;

    let divCardCompleta = document.createElement("div");
    divCardCompleta.className = "producto-card"; 
    

    divCardCompleta.appendChild(img);
    divCardCompleta.appendChild(divTextoInfo);

    let contenedorDestino = document.querySelector("." + producto.nombreSubCategoria);
    if (contenedorDestino) {
        contenedorDestino.appendChild(divCardCompleta);
    }
});
        }
    )
    }