document.addEventListener("DOMContentLoaded", function() {
  const botonRegister = document.getElementById("botonRegister");

  if (botonRegister) {
    botonRegister.addEventListener("click", () => {
       window.location.href = "index.php?page=Registro"; 
    });
  }
});