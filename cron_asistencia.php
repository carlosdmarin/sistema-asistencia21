<?php
// cron_asistencia.php - ESTE ARCHIVO SE EJECUTA AUTOMATICAMENTE

// Cargamos la configuracion
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';      
require_once __DIR__ . '/app/controllers/AsistenciaController.php';

// Creamos una instancia del controlador 
$controller = new AsistenciaController();

// Ejecuta actualizaciones 
echo "[" . date('Y-m-d H:i:s') . "] Ejecutando CRON...\n";

// Marcar faltas a los que no vinieron automaticamente 
$controller->marcarFaltasSilencioso();
echo "Faltas actualizadas\n";

// Marcar salida automaticas para los que se olvidaron marcar su salida 
$controller->marcarSalidasAutomaticasSilencioso();
echo "Salidas actualizadas\n";

echo "[" . date('Y-m-d H:i:s') . "] CRON completado\n";