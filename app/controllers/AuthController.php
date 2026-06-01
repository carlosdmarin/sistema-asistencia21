<?php
// app/controllers/AuthController.php

class AuthController extends Controller 
{
    private $usuarioModel; 
    public $Usuario;
    
    public function __construct() 
    {
        $this->loadModel('Usuario');
        $this->usuarioModel = $this->Usuario;
    }
    
    public function registrar(): void 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $clave = $_POST['clave'] ?? '';
            
            $resultado = $this->usuarioModel->registrar($usuario, $clave);
            
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
            
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
            }
            
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
            
            $destino = $resultado['ok'] ? BASE_URL . '/dashboard' : BASE_URL;
            header('Location: ' . $destino);
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