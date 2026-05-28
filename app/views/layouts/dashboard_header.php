<?php
// app/views/layouts/dashboard_header.php
$urlActual = $_GET['url'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Sistema de Asistencias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/registro_asistencia.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>

    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="overlay" id="overlay"></div>

    <aside class="sidebar" id="sidebar">
        <h2 class="logo">
            <span class="icono"><i class="fa-solid fa-users"></i></span>
            <span>Sistema de Asistencias</span>
        </h2>
        
        <nav class="menu-container">
            <ul class="menu">
                <!-- Dashboard -->
                <li class="menu-item <?php echo ($urlActual == 'dashboard' || $urlActual == '') ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/dashboard">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                       <!-- Empleado -->
                <li class="menu-item has-submenu <?php echo (strpos($urlActual, 'cargo') !== false) ? 'active' : ''; ?>">
                    <div class="menu-header">
                        <i class="fa-solid fa-briefcase"></i>
                        <span>Cargo</span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li class="<?php echo ($urlActual == 'cargo/registrar') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/cargo/registrar">Registrar Cargo</a>
                        </li>
                        <li class="<?php echo ($urlActual == 'cargo/ver') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/cargo/ver">Ver Cargos</a>
                        </li>
                    </ul>
                </li>
                
                <!-- Empleado -->
                <li class="menu-item has-submenu <?php echo (strpos($urlActual, 'empleado') !== false) ? 'active' : ''; ?>">
                    <div class="menu-header">
                        <i class="fas fa-user"></i>
                        <span>Empleado</span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li class="<?php echo ($urlActual == 'empleado/registrar') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/empleado/registrar">Registrar Empleados</a>
                        </li>
                        <li class="<?php echo ($urlActual == 'empleado/ver') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/empleado/ver">Ver Empleados</a>
                        </li>
                    </ul>
                </li>
                
                <!-- Asistencia -->
                <li class="menu-item has-submenu <?php echo (strpos($urlActual, 'asistencia') !== false) ? 'active' : ''; ?>">
                    <div class="menu-header">
                        <i class="fas fa-clock"></i>
                        <span>Asistencia</span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li class="<?php echo ($urlActual == 'asistencia/ver') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/asistencia/ver">Ver Asistencias</a>
                        </li>
                    </ul>
                </li>
                
                <!-- Historial -->
                <li class="menu-item has-submenu <?php echo (strpos($urlActual, 'historial') !== false) ? 'active' : ''; ?>">
                    <div class="menu-header">
                        <i class="fas fa-history"></i>
                        <span>Historial</span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li class="<?php echo ($urlActual == 'historial/ver') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/historial/ver">Ver Historial</a>
                        </li>
                    </ul>
                </li>
                
                <!-- Reportes -->
                <li class="menu-item has-submenu <?php echo (strpos($urlActual, 'reporte') !== false) ? 'active' : ''; ?>">
                    <div class="menu-header">
                        <i class="fas fa-chart-line"></i>
                        <span>Reportes</span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li class="<?php echo ($urlActual == 'reporte/ver') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/reporte/ver">Ver reportes</a>
                        </li>
                    </ul>
                </li>

                  <!-- Usuarios -->
                <li class="menu-item has-submenu <?php echo (strpos($urlActual, 'reporte') !== false) ? 'active' : ''; ?>">
                    <div class="menu-header">
                        <i class="fa-solid fa-user-gear"></i>
                        <span>Usuario</span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </div>
                    <ul class="submenu">
                        <li class="<?php echo ($urlActual == 'usuario/ver') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/usuario/ver">Ver usuarios</a>
                        </li>

                         <li class="<?php echo ($urlActual == 'usuario/registrar') ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/usuario/registrar">Registar Usuario</a>
                        </li>
                    </ul>

                  
                </li>
            </ul>
        </nav>
        
        <div class="logout">
            <a href="#" class="logout-button" onclick="confirmarCerrarSesion(event)">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>
    <?php  include __DIR__ . '/dashboard_footer.php' ?>
    <!-- CONTENIDO PRINCIPAL - EL main SE ABRE PERO NO SE CIERRA AQUÍ -->
    <main class="main-content" id="mainContent">
        <!-- EL CONTENIDO DE CADA VISTA SE INSERTA AQUÍ AUTOMÁTICAMENTE -->