<?php
// app/index.php
// Asegurar que la sesión se inicia
session_start();
date_default_timezone_set('America/Lima');
// Cargar configuración
require_once __DIR__ . '/config/config.php';

// Cargar clases core
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php'; 
require_once __DIR__ . '/core/Controller.php'; 
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/App.php';

// Iniciar aplicación
$app = new App();
$app->run();