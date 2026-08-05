document.addEventListener("DOMContentLoaded", () => {
    const botonMenu = document.getElementById("botonMenu");
    const menu = document.getElementById("menu");

    // Abrir/Cerrar al hacer clic en el botón del menú
    botonMenu.addEventListener("click", (e) => {
        e.stopPropagation(); // Evita que el clic se propague al document
        menu.classList.toggle("open");
        document.body.classList.toggle("menu-abierto");
    });

    // Evitar que los clics DENTRO del menú lo cierren
    sideMenu.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    // Cerrar el menú al hacer clic en CUALQUIER otra parte de la pantalla
    document.addEventListener("click", () => {
        if (menu.classList.contains("open")) {
            menu.classList.remove("open");
            document.body.classList.remove("menu-abierto");
        }
    });
});