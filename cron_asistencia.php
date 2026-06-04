<?php
// cron_asistencia.php - ESTE ARCHIVO SE EJECUTA AUTOMATICAMENTE

// Cargamos la configuracion
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Model.php';              
require_once __DIR__ . '/app/models/Asistencia.php';       

// Instanciamos el modelo directamente
$asistenciaModel = new Asistencia();
$fecha = date('Y-m-d');

echo "[" . date('Y-m-d H:i:s') . "] Ejecutando CRON...\n";

// Marcar faltas a los que no vinieron
$faltas = $asistenciaModel->marcarFaltasAutomaticas($fecha);
echo "Faltas actualizadas: $faltas empleados\n";

// Marcar salidas automáticas para los que olvidaron marcar
$salidas = $asistenciaModel->marcarSalidasAutomaticas($fecha);
echo "Salidas actualizadas: $salidas registros\n";

echo "[" . date('Y-m-d H:i:s') . "] CRON completado\n";