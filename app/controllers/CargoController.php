<?php
// app/controllers/EmpleadoController.php

class CargoController extends Controller {

    private $pdo;
    public $Cargo;

    
    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }
    // Mostramos la vista de registrar cargo
    public function registrar(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $this->view('cargo/registrar_cargo', [
        ], 'dashboard');
    }
    // Procesamos le registrar el empleado
    public function guardar(): void{

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cargo/registrar');
            exit;
        }

        $nombre = $_POST['nombre'] ?? '';
        // Usar el modelo
        $this->loadModel('Cargo');
        $resultado = $this->Cargo->registrarCargo($nombre);

         $_SESSION['mensaje'] = $resultado['mensaje'];
         $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
         if ($resultado['ok']) {
            header('Location: ' . BASE_URL . '/cargo/ver');
        } else {
        header('Location: ' . BASE_URL . '/cargo/registrar');
        }
         exit;
    }
    // MOSTRAMOS TODOS LOS CARGOS EN LA TABLA DE LA VISTA
    public function ver(): void {

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
    
    $this->loadModel('Cargo');
    $cargos = $this->Cargo->obtenerTodos();


    $this->view('cargo/ver_cargo', [
        'cargos' => $cargos
    ], 'dashboard');
    }
    // METODO ELIMINAR
    public function eliminar(int $id): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // cargamos el modelo 
        $this->loadModel('Cargo');
        $resultado = $this->Cargo->eliminarCargo($id);

        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';

        header('Location: ' .BASE_URL . '/cargo/ver');
        exit;
    }
    // Mostramos el formulario de edicion 
    public function editar(int $id): void{
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $this->loadModel('Cargo');
        $cargo = $this->Cargo->obtenerPorId($id);

        if(!$cargo){
            $_SESSION['mensaje'] = 'Cargo no encontrado';
            $_SESSION['tipo'] = 'error';
            header('Location: ' .BASE_URL. '/cargo/ver');
            exit;
        }

        $this->view('cargo/editar_cargo', [
            'cargo' => $cargo
        ], 'dashboard');

    }
    // Actualizamos el cargo 
    public function actualizar():void{

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cargo/ver');
            exit;
        }

        $id = $_POST['id_cargo'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';

        $this->loadModel('Cargo');
        $resultado = $this->Cargo->actualizarCargo($id, $nombre);

        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ?  'success' : 'error';

        header('Location: ' .BASE_URL. '/cargo/ver');
        exit;
    }
    
}