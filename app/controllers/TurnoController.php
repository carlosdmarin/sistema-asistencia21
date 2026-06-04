<?php
// app/controllers/TurnoController.php

class TurnoController extends Controller 
{
    private $pdo;
    public $Turno;

    public function __construct() {
    
        $this->pdo = Database::getConnection();
        $this->loadModel('Turno');
    }
    
    public function ver(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $turnos = $this->Turno->obtenerTodos();
        
        $this->view('turno/ver', [
            'turnos' => $turnos
        ], 'dashboard');
    }
    
    public function registrar(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $this->view('turno/registrar', [], 'dashboard');
    }
    
    public function guardar(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/turno/registrar');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $hora_inicio = $_POST['hora_inicio'] ?? '08:00:00';
        $hora_salida = $_POST['hora_salida'] ?? '17:00:00';
        $tolerancia = $_POST['tolerancia'] ?? 10;
        
        $resultado = $this->Turno->registrar($nombre, $hora_inicio, $hora_salida, $tolerancia);
        
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
        
        header('Location: ' . BASE_URL . '/turno/ver');
        exit;
    }
    
    public function editar(int $id): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $turno = $this->Turno->obtenerPorId($id);
        
        if (!$turno) {
            $_SESSION['mensaje'] = 'Turno no encontrado';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/turno/ver');
            exit;
        }
        
        $this->view('turno/editar', [
            'turno' => $turno
        ], 'dashboard');
    }
    
    public function actualizar(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/turno/ver');
            exit;
        }
        
        $id = $_POST['id_turno'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $hora_inicio = $_POST['hora_inicio'] ?? '08:00:00';
        $hora_salida = $_POST['hora_salida'] ?? '17:00:00';
        $tolerancia = $_POST['tolerancia'] ?? 10;
        
        $resultado = $this->Turno->actualizar($id, $nombre, $hora_inicio, $hora_salida, $tolerancia);
        
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
        
        header('Location: ' . BASE_URL . '/turno/ver');
        exit;
    }
    
    public function eliminar(int $id): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $resultado = $this->Turno->eliminar($id);
        
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
        
        header('Location: ' . BASE_URL . '/turno/ver');
        exit;
    }
}