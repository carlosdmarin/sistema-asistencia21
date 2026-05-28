
<!doctype html>
<html lang="es" data-theme="light">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Asistencia | Sistema Simple y Eficiente</title>
    <!-- DaisyUI CSS - DEBE IR PRIMERO -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/index.css" />
        <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- EVITAR FOUC DE SIDEBAR -->
  </head>
  <body class="bg-gray-100">
  
    <!-- MODALES -->
    <?php include __DIR__ . '/../auth/login_modal.php'; ?>
    
    <!-- SECCIÓN HERO CON IMAGEN DE FONDO -->
    <div class="hero-image w-full relative min-h-[300px] sm:min-h-[400px] md:min-h-[450px] lg:min-h-[500px]">
      <!-- Imagen de fondo que ocupa todo el ancho -->
      <div class="absolute inset-0 w-full h-full">
        <img 
          src="<?php echo BASE_URL; ?>/public/images/slider.png"
          alt="Gestión de asistencia"
          class="w-full h-full object-cover"
        />
        <!-- Capa oscura para mejor legibilidad del texto -->
        <div class="absolute inset-0 bg-black/40"></div>
      </div>
      
      <!-- Contenido del texto sobre la imagen - ALINEADO A LA IZQUIERDA Y ARRIBA -->
      <div class="relative z-10 flex flex-col justify-start text-left px-6 sm:px-8 md:px-12 lg:px-20 pt-12 sm:pt-14 md:pt-16 lg:pt-20 pb-10 sm:pb-12 md:pb-14 lg:pb-16">
        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-white mb-3 sm:mb-4 max-w-3xl leading-tight">
          Gestion de asistencia<br>simple y eficiente
        </h1>
        <p class="text-xs sm:text-base md:text-lg lg:text-xl text-gray-400 max-w-2xl">
          Registra, controla y visualiza la asistencia <br> de tu equipo en tiempo real
        </p>
      </div>
    </div>
    <!-- Texto de Funciones -->
    <div class="texto-funciones">
      <h1>Funciones Principales</h1>
      <p>Todo lo que necesitas para gestionar las asistencias de tus empleados de <br> tu empresa</p>
    </div>

    <!-- SECCIÓN DE CARDS -->
    <div class="container mx-auto px-4 py-12 md:py-16 lg:py-20">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 lg:gap-10 max-w-6xl mx-auto">
    
        <!-- Card 1 - Registro de Asistencia -->
        <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="h-48 md:h-56 overflow-hidden">
            <img src="<?php echo BASE_URL; ?>/public/images/registro.png" alt="Registro de Asistencia" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
          </div>
          <div class="p-6 md:p-8">
            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Registro de Asistencia</h3>
            <p class="text-gray-500 text-sm md:text-base mb-4">Registro de asistencias de empleados</p>
            <a href="<?php echo BASE_URL; ?>/asistencia/registro" class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-800 transition-colors">
    Ver más <i class="fas fa-arrow-right text-sm"></i>
</a>
          </div>
        </div>

        <!-- Card 2 - Panel de control -->
      <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
        <div class="h-48 md:h-56 overflow-hidden">
          <img src="<?php echo BASE_URL; ?>/public/images/panel.png" alt="Panel de control" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
        </div>
          <div class="p-6 md:p-8">
            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Panel de control</h3>
            <p class="text-gray-500 text-sm md:text-base mb-4">Visualiza reportes, estadísticas de asistencias y gestión de empleados y turnos</p>
            <a href="#" onclick="login_modal.showModal(); return false;" class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-800 transition-colors">
              Ver más <i class="fas fa-arrow-right text-sm"></i>
            </a>
          </div>
        </div>
      </div>
    </div>


    <script src="<?php echo BASE_URL; ?>/public/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </body>
</html>