<?php
// app/controllers/EmpleadoController.php

class EmpleadoController extends Controller 
{
    private $pdo;
    public $Cargo;
    public $Empleado;
    public $Turno;

    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }  

    // Medoto o funcion donde mostramos los cargos en el formulario
    public function registrar(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->loadModel('Cargo');
        $cargos = $this->Cargo->obtenerTodos();
        
        $this->loadModel('Turno');
        $turnos = $this->Turno->obtenerTodos();
        $this->view('empleado/registrar', [
            'cargos' => $cargos,
            'turnos' => $turnos
        ], 'dashboard');
    }

    // Metodo o funcion donde registramos los empleados
    public function guardar(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/empleado/registrar');
            exit;
        }

        // Preparar los datos
        $datos = [
            'nombre'    => $_POST['nombre'] ?? '',
            'apellido'  => $_POST['apellido'] ?? '',
            'dni'       => $_POST['dni'] ?? '',
            'telefono'  => $_POST['telefono'] ?? '',
            'id_cargo'  => $_POST['id_cargo'] ?? 0,
            'id_turno'  => $_POST['id_turno'] // Turno por defecto
        ];

        
        // Usamos el modelo
        $this->loadModel('Empleado');
       $resultado = $this->Empleado->registrar($datos);

        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';
        
        if ($resultado['ok']) {
        header('Location: ' . BASE_URL . '/empleado/ver');
        } else {
        header('Location: ' . BASE_URL . '/empleado/registrar');
        }
        exit;
    }

    // Metodo o funcion donde mostramos a todos los empleados y tambien busqueda
    public function ver(): void{

        if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL);
        exit;
        }
        $busqueda = $_GET['buscar'] ?? '';

        // usamos el modelo 
        $this->loadModel('Empleado');
        $empleados = $this->Empleado->buscarEmpleados($busqueda);

        // Si es AJAX devolvemos json
        if(isset($_GET['ajax']) && $_GET['ajax'] == '1'){
            header('Content-Type: application/json');
            echo json_encode($empleados);
            exit;
        }

        // Mostramos la vista de los empleados 
        $this->view('empleado/ver_empleado', [
            'empleados' => $empleados,
            'busqueda' => $busqueda
        ], 'dashboard');
    }

    // Mostramos el formulario de editar
    public function editar(int $id): void {

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Obtener empleado
        $stmt = $this->pdo->prepare("SELECT * FROM EMPLEADO WHERE id_empleado = :id");
        $stmt->execute(['id' => $id]);
        $empleado = $stmt->fetch();
        
        if (!$empleado) {
            $_SESSION['mensaje'] = 'Empleado no encontrado.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/empleado/ver');
            exit;
        }
        
        // Obtener cargos
        $stmt = $this->pdo->query("SELECT * FROM CARGO ORDER BY nombre_cargo");
        $cargos = $stmt->fetchAll();

        $this->loadModel('Turno');
        $turnos = $this->Turno->obtenerTodos();
        
        $this->view('empleado/editar', [
            'empleado' => $empleado,
            'cargos' => $cargos,
            'turnos' => $turnos
        ], 'dashboard');
    }

    // Procesamos la actualizacion de los empleados
    public function actualizar(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/empleado/ver');
            exit;
        }
        
        $id = $_POST['id_empleado'] ?? 0;
        $datos = [
        'nombre'    => $_POST['nombre'] ?? '',
        'apellido'  => $_POST['apellido'] ?? '',
        'dni'       => $_POST['dni'] ?? '',
        'telefono'  => $_POST['telefono'] ?? '',
        'id_cargo'  => $_POST['id_cargo'] ?? 0,
        'id_turno'  => $_POST['id_turno'] ?? 0
        ];

        // Cargamos el modelo 
        $this->loadModel('Empleado');
        $resultado = $this->Empleado->actualizarEmpleado($id, $datos);

        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';

        if($resultado['ok']){
            header('Location: ' .BASE_URL. '/empleado/ver');
        }else{
            header('Location: ' .BASE_URL. '/empleado/editar' );
        }
        exit;
    }

    // ELIMINAR EMPLEADO
    public function eliminar(int $id): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $this->loadModel('Empleado');
        $resultado = $this->Empleado->eliminarEmpleado($id);

        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';

        header('Location: ' .BASE_URL. '/empleado/ver');
        exit;
    }


}