let usuarioPuesto = document.getElementById("Usuario");
let contraseñaPuesta = document.getElementById("contraseña");
let contraseñaPuesta2 = document.getElementById("contraseña-2");
let correoPuesto = document.getElementById("correo");
const restrincion = /[^A-Za-z0-9_-]/
function registrarse() {
    if (usuarioPuesto && contraseñaPuesta && contraseñaPuesta2 && correoPuesto) {
        if (usuarioPuesto.length >= 6 && contraseñaPuesta.length >= 6 && contraseñaPuesta2 >= 6) {
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
            if (Contra !== Contra2) {
                document.getElementById("error").innerHTML = "Las contraseñas no coinciden";
                return;
            }
            fetch("../registro.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "Usuario=" + encodeURIComponent(usuarioPuesto) + "&contraseña=" + encodeURIComponent(contraseñaPuesta) + "$correo=" + encodeURIComponent(correoPuesto)
            }).then(res => res.json()).then(res => {

                if (res.error) {
                    mostrarMensaje(res.msj, false)
                } else {
                    mostrarMensaje("Registro exitoso. ¡Ahora puedes iniciar sesión!", true);
                    window.location.href = "login.php";
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