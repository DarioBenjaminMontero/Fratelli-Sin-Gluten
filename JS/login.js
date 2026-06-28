const botonLogin = document.getElementById("boton-Login");
botonLogin.addEventListener("click", () => {
    console.log("click");
    let usuarioPuesto = document.getElementById("Usuario").value;
    let contraseñaPuesta = document.getElementById("contraseña").value;
    if (usuarioPuesto && contraseñaPuesta) {
        fetch("../login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "Usuario=" + encodeURIComponent(usuarioPuesto) + "&contraseña=" + encodeURIComponent(contraseñaPuesta)
        }).then(res => res.json()).then(res => {
            if (res.success) {
    window.location.href = "index.php?page=main";
} else {
    document.getElementById("errores").innerHTML = res.error;
}
        } )
    }
    else {
        console.log("Escribi algo");
    }
 });