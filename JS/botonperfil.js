document.addEventListener("DOMContentLoaded", function() {
  const botonperfil = document.getElementById("botonPerfil");

  if (botonperfil) {
    botonperfil.addEventListener("click", () => {
       window.location.href = "index.php?page=perfil"; 
    });
  }
});