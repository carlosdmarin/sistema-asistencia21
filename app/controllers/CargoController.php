<?php
// app/controllers/EmpleadoController.php

class CargoController extends Controller 
{
    private $pdo;
    
    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }
    
    public function registrar(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $this->view('cargo/registrar_cargo', [
        ], 'dashboard');
    }

     public function guardar(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cargo/registrar');
            exit;
        }
        
        $nombre   = $_POST['nombre'] ?? '';
        
        // Validar
        if (empty($nombre)) {
            $_SESSION['mensaje'] = 'Nombre del cargo es obligatorio.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/cargo/registrar');
            exit;
        }
        
        // Verificar cargo único
        $stmt = $this->pdo->prepare("SELECT id_cargo FROM CARGO WHERE nombre_cargo = :nombre LIMIT 1");
        $stmt->execute(['nombre' => $nombre]);
        if ($stmt->fetch()) {
            $_SESSION['mensaje'] = 'Ya existe un cargo con ese nombre.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/cargo/registrar');
            exit;
        }
        
        // Insertar
        $stmt = $this->pdo->prepare("
            INSERT INTO CARGO (nombre_cargo) 
            VALUES (:nombre)
        ");
        $stmt->execute([
            'nombre'    => $nombre
        ]);
        
        $_SESSION['mensaje'] = 'Cargo registrado correctamente.';
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . '/cargo/ver');
        exit;
    }

    public function ver(): void {

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
    
    // Preparamos la consulta 
    $stmt = $this->pdo->prepare("SELECT * FROM CARGO");
    
    // EJECUTAMOS LA CONSULTA 
    $stmt->execute();

    // Obtenemos los resultados 
    $cargos = $stmt->fetchAll();

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
        
        // Verificar que el cargo existe
        $stmt = $this->pdo->prepare("SELECT id_cargo FROM CARGO WHERE id_cargo = :id");
        $stmt->execute(['id' => $id]);
        
        if (!$stmt->fetch()) {
            $_SESSION['mensaje'] = 'Cargo no encontrado.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/cargo/ver');
            exit;
        }
        
        // Verificar si hay empleados con este cargo
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE id_cargo = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        
        if ($stmt->fetch()) {
            $_SESSION['mensaje'] = 'No puedes eliminar este cargo porque tiene empleados asociados.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/cargo/ver');
            exit;
        }
        
        // Eliminar cargo
        $stmt = $this->pdo->prepare("DELETE FROM CARGO WHERE id_cargo = :id");
        $stmt->execute(['id' => $id]);
        
        $_SESSION['mensaje'] = 'Cargo eliminado correctamente.';
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . '/cargo/ver');
        exit;
    }
    
}