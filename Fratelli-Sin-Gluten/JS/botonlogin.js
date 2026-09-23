document.addEventListener("DOMContentLoaded", function() {
  const botonLogin = document.getElementById("botonLogin");

  if (botonLogin) {
    botonLogin.addEventListener("click", () => {
       window.location.href = "index.php?page=Login"; 
    });
  }
});