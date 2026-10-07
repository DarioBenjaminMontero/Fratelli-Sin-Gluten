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
const boton = document.getElementById("volver");
boton.addEventListener("click", () => {
        fetch("../perfil.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "volver=true"
        }).then(res => res.json()).then(res => {
            if (res.success) {
                document.getElementById("errores").innerHTML = res.message;
                window.location.href = "index.php?page=Login";
            } else {
                document.getElementById("errores").innerHTML = res.error;
            }
        })
});
const botoninsertar = document.getElementById("ingresar");
botoninsertar.addEventListener("click", () => {
     usuarioPuesto = document.getElementById("nombre").value ? document.getElementById("nombre").value:usuarioPuesto;
    let contraseñaPuesta = document.getElementById("contra").value;
    let contraseñanueva = document.getElementById("contran").value;
     ubicacion = document.getElementById("ubicacion").value ? document.getElementById("ubicacion").value:ubicacion;
     departamentonum = document.getElementById("depn").value ? document.getElementById("depn").value:departamentonum;
     departamentopiso = document.getElementById("depp").value ? document.getElementById("depp").value:departamentopiso;
    if (usuarioPuesto || contraseñaPuesta || contraseñanueva || ubicacion || departamentonum || departamentopiso) {
        fetch("../perfil.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "Usuario=" + encodeURIComponent(usuarioPuesto) + "&contraseña=" + encodeURIComponent(contraseñaPuesta) + "&contraseñanueva=" + encodeURIComponent(contraseñanueva) + "&ubicacion=" + encodeURIComponent(ubicacion) + "&departamentonumero=" + encodeURIComponent(departamentonum) + "&departamentopiso=" + encodeURIComponent(departamentopiso)+ "&volver=false"
        }).then(res => res.json()).then(res => {
            if (res.success) {
                document.getElementById("errores").innerHTML = res.message;
            } else {
                document.getElementById("errores").innerHTML = res.error;
            }
        })
    }
    else {
        console.log("Escribi algo");
    }

});
