<?php
// app/index.php
// CONFIGURACION DE LA ZONA HORARIA 
date_default_timezone_set('America/Lima');

// Iniciar sesión
session_start();

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