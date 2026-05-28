<?php

class UsuarioController extends Controller
{
    // Mostrar la página de usuario
    public function ver(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        // Instanciamos el modelo manualmente
        require_once __DIR__ . '/../models/Usuario.php';
        $usuarioModel = new Usuario();
        
        // Obtener todos los usuarios
        $usuarios = $usuarioModel->obtenerTodos();
        
        // Pasar datos a la vista
        $this->view('usuario/ver_usuario', [
            'usuarios' => $usuarios
        ], 'dashboard');
    }

    // Eliminar usuario 
    public function eliminar(int $id): void{
        if(!isset($_SESSION['usuario_id'])){
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // No permitir que el propio usuario se elimine 
        if ($id == $_SESSION['usuario_id']){
            $_SESSION['mensaje'] = 'No puedes eliminar tu propio usuario';
            $_SESSION['tipo'] = 'error';
            header ('Location: ' . BASE_URL . '/usuario/ver');
            exit;
        }
         // Instanciamos el modelo manualmente
        require_once __DIR__ . '/../models/Usuario.php';
        $usuarioModel = new Usuario();

        $usuarios = $usuarioModel->eliminar($id);
        $_SESSION['mensaje'] = 'Usuario eliminado correctamente.';
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . '/usuario/ver');
        exit;
    }

    public function registrar(): void{
         if(!isset($_SESSION['usuario_id'])){
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // mostramos la vista de registrar usuario
        $this->view('auth/registrar_usuario', [] , 'dashboard');
    }
    // Procesar el registro de usuario (guardar en BD)
public function guardar(): void 
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '/usuario/registrar');
        exit;
    }
    
    $nombre = $_POST['usuario'] ?? '';  
    $clave = $_POST['clave'] ?? '';
    
    if (empty($nombre) || empty($clave)) {
        $_SESSION['mensaje'] = 'Todos los campos son obligatorios.';
        $_SESSION['tipo'] = 'error';
        header('Location: ' . BASE_URL . '/usuario/registrar');
        exit;
    }
    
    require_once __DIR__ . '/../models/Usuario.php';
    $usuarioModel = new Usuario();
    $resultado = $usuarioModel->registrar($nombre, $clave);
    
    $_SESSION['mensaje'] = $resultado['mensaje'];
    $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
    
    if ($resultado['ok']) {
        header('Location: ' . BASE_URL . '/usuario/ver');
    } else {
        header('Location: ' . BASE_URL . '/usuario/registrar');
    }
    exit;
}

}