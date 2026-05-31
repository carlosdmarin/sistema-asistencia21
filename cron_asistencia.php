<?php
// cron_asistencia.php - ESTE ARCHIVO SE EJECUTA AUTOMATICAMENTE

// Cargamos la configuracion
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';      
require_once __DIR__ . '/app/core/Model.php';              
require_once __DIR__ . '/app/models/Asistencia.php';       
require_once __DIR__ . '/app/controllers/AsistenciaController.php';

// Creamos una instancia del controlador 
$controller = new AsistenciaController();

// Instanciamos el modelo directamente (para las funciones automáticas)
$asistenciaModel = new Asistencia();

// Ejecuta actualizaciones 
echo "[" . date('Y-m-d H:i:s') . "] Ejecutando CRON...\n";

// Marcar faltas a los que no vinieron automaticamente 
$asistenciaModel->marcarFaltasAutomaticas(date('Y-m-d'));
echo "Faltas actualizadas\n";

// Marcar salida automaticas para los que se olvidaron marcar su salida 
$asistenciaModel->marcarSalidasAutomaticas(date('Y-m-d'));
echo "Salidas actualizadas\n";

echo "[" . date('Y-m-d H:i:s') . "] CRON completado\n";