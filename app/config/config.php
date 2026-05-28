<?php
define("TITLE_BUSINESS", "ASISTENCIA");

// Leemos el archivo .env que está en la raíz del proyecto.
$envFile = dirname(__DIR__, 2) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$key, $value] = explode("=", $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

define("DB_HOST", $_ENV['DB_HOST']     ?? 'localhost');
define("DB_PORT", $_ENV['DB_PORT']     ?? '3306');
define("DB_NAME", $_ENV['DB_DATABASE'] ?? '');
define("DB_USER", $_ENV['DB_USERNAME'] ?? 'root');
define("DB_PASS", $_ENV['DB_PASSWORD'] ?? '');

define("BASE_URL", $_ENV['APP_URL'] ?? 'http://localhost');