<?php
// app/controllers/EmpleadoController.php

class EmpleadoController extends Controller 
{
    private $pdo;

    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }  

    // Medoto o funcion donde mostramos los cargos en el formulario
    public function registrar(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->loadModel('Cargo');
        $cargos = $this->Cargo->obtenerTodos();
        
        $this->view('empleado/registrar', [
            'cargos' => $cargos
        ], 'dashboard');
    }

    // Metodo o funcion donde registramos los empleados
    public function guardar(): void 
    {
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
            'id_turno'  => 1  // Turno por defecto
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
    public function editar(int $id): void 
    {
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
        
        $this->view('empleado/editar', [
            'empleado' => $empleado,
            'cargos' => $cargos
        ], 'dashboard');
    }

    // Procesamos la actualizacion de los empleados
    public function actualizar(): void 
    {
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
        'id_cargo'  => $_POST['id_cargo'] ?? 0
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
    public function eliminar(int $id): void 
    {
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

    // Registrar asis.,m nbtencia con lector de código de barras
    public function marcarAsistencia(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido']);
        exit;
    }
    
    $dni = $_POST['dni'] ?? '';
    
    if (empty($dni)) {
        echo json_encode(['ok' => false, 'mensaje' => 'DNI requerido']);
        exit;
    }
    
    // Buscar empleado por DNI
    $stmt = $this->pdo->prepare("
        SELECT e.*, c.nombre_cargo, t.nombre_turno, t.hora_inicio, t.tolerancia_minutos
        FROM EMPLEADO e 
        INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
        INNER JOIN TURNO t ON e.id_turno = t.id_turno 
        WHERE e.dni = :dni 
        LIMIT 1
    ");
    $stmt->execute(['dni' => $dni]);
    $empleado = $stmt->fetch();
    
    if (!$empleado) {
        echo json_encode([
            'ok' => false, 
            'mensaje' => 'Empleado no encontrado',
            'tipo' => 'no_encontrado'
        ]);
        exit;
    }
    
    $hoy = date('Y-m-d');
    $ahora = date('H:i:s');
    
    // Verificar si ya registró hoy
    $stmt = $this->pdo->prepare("
        SELECT * FROM ASISTENCIA 
        WHERE id_empleado = :id AND fecha = :fecha 
        LIMIT 1
    ");
    $stmt->execute(['id' => $empleado['id_empleado'], 'fecha' => $hoy]);
    $asistenciaHoy = $stmt->fetch();
    
    if ($asistenciaHoy) {
        // Ya registró entrada, verificar si falta salida
        if (empty($asistenciaHoy['hora_salida'])) {
            // Registrar salida
            $stmt = $this->pdo->prepare("
                UPDATE ASISTENCIA 
                SET hora_salida = :hora_salida 
                WHERE id_asistencia = :id
            ");
            $stmt->execute([
                'hora_salida' => $ahora,
                'id' => $asistenciaHoy['id_asistencia']
            ]);
            
            echo json_encode([
                'ok' => true,
                'mensaje' => '¡Hasta luego ' . $empleado['nombre'] . '!',
                'empleado' => [
                    'nombre' => $empleado['nombre'],
                    'apellido' => $empleado['apellido'],
                    'cargo' => $empleado['nombre_cargo'],
                    'hora' => $ahora,
                    'tipo_marcacion' => 'SALIDA'
                ],
                'tipo' => 'salida'
            ]);
            exit;
        } else {
            // Ya tiene entrada y salida
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Ya registraste entrada y salida hoy',
                'tipo' => 'duplicado'
            ]);
            exit;
        }
    }
    
    // Determinar estado (asistio o tardanza)
    $estado = 'asistio';
    $horaLimite = $empleado['hora_inicio'];
    $tolerancia = $empleado['tolerancia_minutos'];
    
    // Calcular límite con tolerancia
    $horaLimiteConTolerancia = date('H:i:s', strtotime($horaLimite . ' + ' . $tolerancia . ' minutes'));
    
    if ($ahora > $horaLimiteConTolerancia) {
        $estado = 'tardanza';
    }
    
    // Registrar entrada
    $stmt = $this->pdo->prepare("
        INSERT INTO ASISTENCIA (id_empleado, fecha, hora_entrada, estado) 
        VALUES (:id, :fecha, :hora, :estado) 
    ");
    $stmt->execute([
        'id'     => $empleado['id_empleado'],
        'fecha'  => $hoy,
        'hora'   => $ahora,
        'estado' => $estado
    ]);
    
    $mensaje = $estado === 'tardanza' 
        ? '⚠ Llegaste tarde, ' . $empleado['nombre'] 
        : '¡Bienvenido ' . $empleado['nombre'] . '!';
    
    echo json_encode([
        'ok' => true,
        'mensaje' => $mensaje,
        'empleado' => [
            'nombre' => $empleado['nombre'],
            'apellido' => $empleado['apellido'],
            'cargo' => $empleado['nombre_cargo'],
            'hora' => $ahora,
            'estado' => $estado,
            'tipo_marcacion' => 'ENTRADA'
        ],
        'tipo' => 'exito'
    ]);
    exit;
    }
    // Ver últimas asistencias (para AJAX)
    public function ultimas(): void
{
    $stmt = $this->pdo->query("
        SELECT a.*, e.nombre, e.apellido, c.nombre_cargo 
        FROM ASISTENCIA a 
        INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado 
        INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
        ORDER BY a.id_asistencia DESC 
        LIMIT 10
    ");
    $asistencias = $stmt->fetchAll();
    
    foreach ($asistencias as $asistencia) {
        $estadoClass = match($asistencia['estado']) {
            'asistio' => 'badge-success',
            'tardanza' => 'badge-warning',
            'falto' => 'badge-danger',
            default => ''
        };
        
        echo '<div class="asistencia-item">';
        echo '<span>' . $asistencia['nombre'] . ' ' . $asistencia['apellido'] . '</span>';
        echo '<span>' . $asistencia['nombre_cargo'] . '</span>';
        echo '<span>' . $asistencia['hora_entrada'] . '</span>';
        echo '<span class="badge ' . $estadoClass . '">' . ucfirst($asistencia['estado']) . '</span>';
        echo '</div>';
    }
    exit;
    }


}