

const restrincion = /[^A-Za-z0-9_-]/
function registrarse() {
    let usuarioPuesto = document.getElementById("Usuario").value;
let contraseñaPuesta = document.getElementById("contraseña").value;
let contraseñaPuesta2 = document.getElementById("contraseña-2").value;
let correoPuesto = document.getElementById("correo").value;
    if (usuarioPuesto && contraseñaPuesta && contraseñaPuesta2 && correoPuesto) {
        if (usuarioPuesto.length >= 6 && contraseñaPuesta.length >= 6 && contraseñaPuesta2.length >= 6) {
            for (let i = 0; i < usuarioPuesto.length; i++) {
                let caracter = usuarioPuesto.charAt(i);
                if (restrincion.test(caracter)) {
                    document.getElementById("error").innerHTML = "unicos carcteres especiales permitido son - _"
                    return
                }
            }
            for (let i = 0; i < contraseñaPuesta.length; i++) {
                let caracter = contraseñaPuesta.charAt(i);
                if (restrincion.test(caracter)) {
                    document.getElementById("error").innerHTML = "unicos carcteres especiales permitido son - _"
                    return
                }
            }
            for (let i = 0; i < contraseñaPuesta2.length; i++) {
                let caracter = contraseñaPuesta2.charAt(i);
                if (restrincion.test(caracter)) {
                    document.getElementById("error").innerHTML = "unicos carcteres especiales permitido son - _"
                    return
                }
            }
            if (contraseñaPuesta !== contraseñaPuesta2) {
                document.getElementById("error").innerHTML = "Las contraseñas no coinciden";
                return;
            }
            fetch("../registro.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "Usuario=" + encodeURIComponent(usuarioPuesto) + "&contraseña=" + encodeURIComponent(contraseñaPuesta) + "&correo=" + encodeURIComponent(correoPuesto)
            }).then(res => res.json()).then(res => {

                if (res.error) {
                    mostrarMensaje(res.msj, false)
                } else {
                    mostrarMensaje("Registro exitoso. ¡Ahora puedes iniciar sesión!", true);
                    window.location.href = "index.php?page=Login";
                }
            })
        }
        else {
            document.getElementById("error").innerHTML = "el nombre de usurio y contraseña debe de ser de 6 caracteres de minimo"
        }
    }
    else {
        document.getElementById("error").innerHTML = "Escribi algo";
    }
}
function mostrarMensaje(mensaje, exito){

    const div = document.getElementById("error");

    div.innerHTML = mensaje;

    if(exito){
        div.style.color = "#2d6a2d";
        div.style.background = "#d8f5d2";
        div.style.border = "1px solid #82c582";
    }else{
        div.style.color = "#c0392b";
        div.style.background = "#ffe5e5";
        div.style.border = "1px solid #d63031";
    }

    div.style.padding = "10px";
    div.style.borderRadius = "10px";
    div.style.marginTop = "15px";
}