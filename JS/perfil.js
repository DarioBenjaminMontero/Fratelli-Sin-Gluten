document.addEventListener("DOMContentLoaded", function () {
    const cheackbox = document.getElementById("i");
    if (cheackbox) {
        cheackbox.addEventListener("click", () => {
            if (document.getElementById("depn").disabled == true) {
                document.getElementById("depn").disabled = false;
                document.getElementById("depp").disabled = false;
            }
            else {
                document.getElementById("depn").disabled = true;
                document.getElementById("depp").disabled = true;
            }
        });
    }
});
const botoninsertar = document.getElementById("ingresar");
botoninsertar.addEventListener("click", () => {
    let usuarioPuesto = document.getElementById("nombre").value ? document.getElementById("nombre").value:$_SESSION['nombre_usuario'];
    let contraseñaPuesta = document.getElementById("contra").value;
    let contraseñanueva = document.getElementById("contran").value;
    let ubicacion = document.getElementById("ubicacion").value ? document.getElementById("ubicacion").value:$_SESSION['nombre_usuario'];
    let departamentonum = document.getElementById("depn").value ? document.getElementById("depn").value:$_SESSION['nombre_usuario'];
    let departamentopiso = document.getElementById("depp").value ? document.getElementById("depp").value:$_SESSION['nombre_usuario'];
    if (usuarioPuesto || contraseñaPuesta || contraseñanueva || ubicacion || departamentonum || departamentopiso) {
        fetch("../perfil.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "Usuario=" + encodeURIComponent(usuarioPuesto) + "&contraseña=" + encodeURIComponent(contraseñaPuesta) + "&contraseñanueva=" + encodeURIComponent(contraseñanueva) + "&ubicacion=" + encodeURIComponent(ubicacion) + "&departamentonumero=" + encodeURIComponent(departamentonum) + "&departamentopiso=" + encodeURIComponent(departamentopiso)
        }).then(res => res.json()).then(res => {
            if (res.success) {

            } else {
                document.getElementById("errores").innerHTML = res.error;
            }
        })
    }
    else {
        console.log("Escribi algo");
    }

});
