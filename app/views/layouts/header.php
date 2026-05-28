<!-- Overlay del sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- NAVBAR -->
<div class="navbar bg-white shadow-md px-6 md:px-10 flex justify-between items-center">
    
    <!-- IZQUIERDA - Logo + Enlaces -->
    <div class="flex items-center gap-8">
        <a class="text-xl font-bold text-gray-900">EMPRESA</a>
        
        <!-- Enlaces desktop -->
        <div class="hidden lg:flex gap-16 absolute left-1/2 -translate-x-1/2">
            <a href="#" class="text-gray-700 hover:text-blue-600 font-bold text-lg">Inicio</a>
            <a href="#" class="text-gray-700 hover:text-blue-600 font-bold text-lg">Funciones</a>
            <a href="#" class="text-gray-700 hover:text-blue-600 font-bold text-lg">Nosotros</a>
        </div>
    </div>

    <!-- DERECHA - Botones solo desktop -->
    <div class="hidden lg:flex gap-5">
        <button class="sidebar-btn-login" onclick="login_modal.showModal()">
            Iniciar Sesión
        </button>
    </div>

    <!-- BOTÓN HAMBURGUESA - solo móvil/tablet -->
    <div class="lg:hidden">
        <button class="hamburger-btn" id="hamburgerBtn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>

<!-- SIDEBAR - móvil -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>Menú</h3>
        <button class="close-sidebar" id="closeSidebarBtn">✕</button>
    </div>
    <div class="sidebar-nav">
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Funciones</a></li>
            <li><a href="#">Nosotros</a></li>
        </ul>
    </div>
    <div class="sidebar-buttons">
        <button class="sidebar-btn-login" onclick="login_modal.showModal()">Iniciar Sesión</button>
    </div>
</div>