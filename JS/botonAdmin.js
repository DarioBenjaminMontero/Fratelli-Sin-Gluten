document.addEventListener("DOMContentLoaded", function() {
  const botonAdmin = document.getElementById("botonAdmin");

  if (botonAdmin) {
    botonAdmin.addEventListener("click", () => {
       window.location.href = "index.php?page=admin"; 
    });
  }
});