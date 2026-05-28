// Elementos del DOM
const hamburgerBtn = document.getElementById("hamburgerBtn");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("sidebarOverlay");
const closeSidebarBtn = document.getElementById("closeSidebarBtn");

// Función para abrir el sidebar
function openSidebar() {
  sidebar.classList.add("active");
  overlay.classList.add("active");
  document.body.style.overflow = "hidden";
}

// Función para cerrar el sidebar
function closeSidebar() {
  sidebar.classList.remove("active");
  overlay.classList.remove("active");
  document.body.style.overflow = "";
}

// Eventos
if (hamburgerBtn) {
  hamburgerBtn.addEventListener("click", openSidebar);
}
if (closeSidebarBtn) {
  closeSidebarBtn.addEventListener("click", closeSidebar);
}
if (overlay) {
  overlay.addEventListener("click", closeSidebar);
}

// Cerrar con tecla ESC
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape" && sidebar.classList.contains("active")) {
    closeSidebar();
  }
});





 
// FUNCION DE VER CONTRASENA EN LOS INPUTS
  // Función para toggle de contraseña (login)
  const toggleLoginPassword = document.getElementById('toggle_login_password');
  const loginPasswordInput = document.getElementById('login_password');

  if (toggleLoginPassword && loginPasswordInput) {
    toggleLoginPassword.addEventListener('click', function() {
      // Cambiar el tipo de input
      const type = loginPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      loginPasswordInput.setAttribute('type', type);
      
      // Cambiar el ícono (ojo abierto/cerrado)
      if (type === 'text') {
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash');
      } else {
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye');
      }
    });
  }

  // Función para toggle de contraseña (registro)
  const toggleRegisterPassword = document.getElementById('toggle_register_password');
  const registerPasswordInput = document.getElementById('register_password');

  if (toggleRegisterPassword && registerPasswordInput) {
    toggleRegisterPassword.addEventListener('click', function() {
      const type = registerPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      registerPasswordInput.setAttribute('type', type);
      
      if (type === 'text') {
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash');
      } else {
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye');
      }
    });
  }




