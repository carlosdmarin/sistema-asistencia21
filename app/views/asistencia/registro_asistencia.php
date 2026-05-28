<!-- FONDO CORPORATIVO -->
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 flex items-center justify-center p-6 relative">
    
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="relative z-10 w-full max-w-md">
        
        <!-- CABECERA -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-qrcode text-white text-lg"></i>
                </div>
                <h1 class="text-white font-bold text-2xl tracking-tight">REGISTRO DE ASISTENCIA</h1>
            </div>
            <div class="w-20 h-0.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- RELOJ DIGITAL -->
        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 mb-6 border border-gray-700/50 shadow-xl">
            <p id="date" class="text-gray-400 text-sm font-medium text-center mb-3 tracking-wide uppercase">--/--/----</p>
            <p id="clock" class="text-7xl font-bold text-white text-center tracking-wider" style="font-family: 'Poppins', monospace;">--:--:--</p>
            <div class="flex items-center justify-center gap-2 mt-5">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="text-green-500 text-xs font-medium uppercase tracking-wider">Sistema activo</span>
            </div>
        </div>

        <!-- CAMPO PARA LECTOR -->
        <input type="password" 
               id="lectorDni" 
               class="absolute opacity-0" 
               style="top:0;left:0;width:1px;height:1px;"
               autofocus 
               autocomplete="off">

        <!-- MENSAJE DE ESPERA -->
        <div class="text-center mb-6">
            <p class="text-gray-500 text-sm font-medium">
                <i class="far fa-hand-point-up mr-2"></i>Acerque su DNI al lector
            </p>
        </div>

        <!-- RESULTADO -->
        <div id="resultCard" class="hidden mb-6"></div>

        <!-- ÚLTIMOS REGISTROS -->
        <div class="bg-gray-800/60 backdrop-blur-md rounded-2xl p-6 border border-gray-700/50">
            <h3 class="text-gray-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fas fa-list text-blue-400"></i> Últimos registros
            </h3>
            <div id="listaAsistencias" class="text-gray-400 text-sm space-y-2">
                <p class="text-center">Sin registros recientes</p>
            </div>
        </div>

        <!-- BOTÓN REGRESAR -->
        <div class="mt-6 text-center">
            <a href="<?php echo BASE_URL; ?>" 
               class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-300 text-sm font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>

<!-- PASAR VARIABLES DE PHP A JAVASCRIPT -->
<script>
    // Variables globales que vienen de PHP
    const BASE_URL = '<?php echo BASE_URL; ?>';
</script>

<!-- TU ARCHIVO JS EXTERNO -->
<script src="<?php echo BASE_URL; ?>/public/js/lector.js"></script>