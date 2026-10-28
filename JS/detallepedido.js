function detallepedido(){
    let numeropedido = parseInt(sessionStorage.getItem('idpedido'));
    fetch("../detallepedido.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "numero=" + encodeURIComponent(numeropedido)
    })
    .then(res => res.json())
    .then(res => {
        let sDiv = document.getElementById("pedido");
        sDiv.innerHTML = "";
        
        if (res.pedidos === "") {
            sDiv.innerHTML = "<p>No hay pedidos en el historial.</p>";
        } else {
            let htmlTabla = `
                <table class="historial-table">
                    <thead>
                        <tr>
                            <th>producto</th>
                            <th>cantidad</th>
                            <th>precio unitario</th>
                            <th>precio total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            res.forEach(pedidos => {
                let precioUnitario = pedidos.precio / pedidos.cantidad; 
                
                htmlTabla += `
                    <tr>
                        <td><img src="${pedidos.imagen}" alt=""> ${pedidos.nombre_producto}</td>
                        <td>${pedidos.cantidad}</td>
                        <td>${precioUnitario}</td>
                        <td>${pedidos.precio}</td>
                    </tr>
                `;
            });
            htmlTabla += `
                    </tbody>
                </table>
            `;

            sDiv.innerHTML = htmlTabla;
        }
    });
}
detallepedido();