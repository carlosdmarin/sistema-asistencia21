<?php
// app/controllers/AuthController.php

class AuthController extends Controller 
{
    private Usuario $usuarioModel;
    
    public function __construct() 
    {
        require_once __DIR__ . '/../models/Usuario.php';
        $this->usuarioModel = new Usuario();
    }
    
    public function registrar(): void 
    {
        // Si es GET, redirigir al home
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Si es POST, procesar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $clave = $_POST['clave'] ?? '';
            
            $resultado = $this->usuarioModel->registrar($usuario, $clave);
            
            if ($resultado['ok']) {
                $_SESSION['mensaje'] = $resultado['mensaje'];
                $_SESSION['tipo'] = 'success';
            } else {
                $_SESSION['mensaje'] = $resultado['mensaje'];
                $_SESSION['tipo'] = 'error';
            }
            
            header('Location: ' . BASE_URL);
            exit;
        }
    }
    
    public function login(): void 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $clave = $_POST['clave'] ?? '';
            
            $resultado = $this->usuarioModel->login($usuario, $clave);
            
            if ($resultado['ok']) {
                $_SESSION['usuario_id'] = $resultado['usuario']['id_usuario'];
                $_SESSION['usuario_nombre'] = $resultado['usuario']['nombre'];
                $_SESSION['mensaje'] = $resultado['mensaje'];
                $_SESSION['tipo'] = 'success';
            } else {
                $_SESSION['mensaje'] = $resultado['mensaje'];
                $_SESSION['tipo'] = 'error';
            }
            
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    
public function logout(): void 
{
    session_start();
    session_destroy();
    header('Location: ' . BASE_URL);
    exit;
}
}