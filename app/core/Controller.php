<?php
// app/core/Controller.php

class Controller 
{
    public function __construct() 
    {
        // Constructor vacío
    }
    
    protected function loadModel(string $modelName): void 
    {
        $modelPath = __DIR__ . '/../models/' . $modelName . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            if (class_exists($modelName)) {
                $this->{$modelName} = new $modelName();
            }
        }
    }

    protected function view(string $vista, array $datos = [], string $layout = 'default'): void 
    {
        extract($datos);
        
        // =============================================
        // LAYOUT PDF (sin header ni footer)
        // =============================================
        if ($layout === 'pdf') {
            $vistaFile = __DIR__ . '/../views/' . $vista . '.php';
            if (file_exists($vistaFile)) {
                require_once $vistaFile;
            }
            return;
        }
        
        // =============================================
        // ELEGIR HEADER Y FOOTER SEGÚN LAYOUT
        // =============================================
        if ($layout === 'dashboard') {
            $headerFile = __DIR__ . '/../views/layouts/dashboard_header.php';
            $footerFile = __DIR__ . '/../views/layouts/dashboard_footer.php';
        } elseif ($layout === 'lector') {
            $headerFile = __DIR__ . '/../views/layouts/lector_header.php';
            $footerFile = __DIR__ . '/../views/layouts/lector_footer.php';
        } else {
            $headerFile = __DIR__ . '/../views/layouts/header.php';
            $footerFile = __DIR__ . '/../views/layouts/footer.php';
        }
        
        // Cargar header (si existe)
        if ($headerFile && file_exists($headerFile)) {
            require_once $headerFile;
        }
        
        // Cargar vista principal
        $vistaFile = __DIR__ . '/../views/' . $vista . '.php';
        if (file_exists($vistaFile)) {
            require_once $vistaFile;
        } else {
            echo "<p>Vista no encontrada: $vista</p>";
        }
        
        // Cargar footer (si existe)
        if ($footerFile && file_exists($footerFile)) {
            require_once $footerFile;
        }
    }
}