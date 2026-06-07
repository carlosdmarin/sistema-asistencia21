<?php
// app/controllers/AsistenciaController.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AsistenciaController extends Controller
{
    private $pdo;
    public  $Asistencia;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->loadModel('Asistencia');
    }

    // Mostrar la pantalla del lector (PÚBLICO)
    public function registro(): void
    {
        $this->view('asistencia/registro_asistencia', [], 'lector');
    }

    // Procesar marcación (AJAX)
    public function marcar(): void
{
    // Verificamos que sea una peticion POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido']);
        exit;
    }
    
    $dni = $_POST['dni'] ?? '';
    
    if (empty($dni)) {
        echo json_encode(['ok' => false, 'mensaje' => 'DNI requerido']);
        exit;
    }
    
    // Cargar el modelo
    $this->loadModel('Asistencia');
    
    // Buscar empleado por DNI
    $empleado = $this->Asistencia->buscarEmpleadoPorDni($dni);
    
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
    
    // Buscar asistencia de hoy
    $asistenciaHoy = $this->Asistencia->buscarAsistenciaHoy($empleado['id_empleado'], $hoy);
    
    // Si ya marcó entrada hoy
    if ($asistenciaHoy) {
        // Si no tiene hora de salida, registrar salida
        if (empty($asistenciaHoy['hora_salida'])) {
            $this->Asistencia->registrarSalida($asistenciaHoy['id_asistencia'], $ahora);
            
            echo json_encode([
                'ok' => true,
                'tipo' => 'salida',
                'mensaje' => '¡Hasta luego!',
                'empleado' => [
                    'nombre' => $empleado['nombre'],
                    'apellido' => $empleado['apellido'],
                    'cargo' => $empleado['nombre_cargo'],
                    'hora' => $ahora
                ]
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
    $estado = $this->Asistencia->determinarEstado(
        $empleado['hora_inicio'],
        $empleado['tolerancia_minutos'],
        $ahora
    );
    
    // Registrar entrada
    $this->Asistencia->registrarEntrada($empleado['id_empleado'], $hoy, $ahora, $estado);
    
    $mensaje = $estado === 'tardanza' ? '⚠ Llegaste tarde' : 'Asistencia registrada';
    
    echo json_encode([
        'ok' => true,
        'tipo' => 'entrada',
        'mensaje' => $mensaje,
        'empleado' => [
            'nombre' => $empleado['nombre'],
            'apellido' => $empleado['apellido'],
            'cargo' => $empleado['nombre_cargo'],
            'hora' => $ahora,
            'estado' => $estado
        ]
    ]);
    exit;
    }

    // Ver últimas asistencias (AJAX)

    public function ultimas(): void
{
    $this->loadModel('Asistencia');
    $asistencias = $this->Asistencia->obtenerUltimasAsistencias(10);
    
    foreach ($asistencias as $a) {
        $estadoClass = match ($a['estado']) {
            'asistio' => 'badge-success',
            'tardanza' => 'badge-warning',
            'falto' => 'badge-danger',
            default => ''
        };
        $estadoTexto = match ($a['estado']) {
            'asistio' => 'Asistió',
            'tardanza' => 'Tardanza',
            'falto' => 'Faltó',
            default => $a['estado']
        };

        echo '<div class="asistencia-item">';
        echo '<span>' . htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) . '</span>';
        echo '<span>' . htmlspecialchars($a['nombre_cargo']) . '</span>';
        echo '<span>' . ($a['hora_entrada'] ?? '—') . '</span>';
        echo '<span class="' . $estadoClass . '">' . $estadoTexto . '</span>';
        echo '</div>';
    }
    exit;
    }
    
    // Ver asistencias del día (solo administradores)
    public function ver(): void{
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $hoy = date('Y-m-d');
        $ahora = date('H:i:s');

        // Solo marcar faltos después de las 5 PM
        if ($ahora >= '17:00:00') {
            $this->Asistencia->marcarFaltasAutomaticas($hoy);
        }

        // Solo marcar salidas automáticas después de las 6 PM
        if ($ahora >= '18:00:00') {
            $this->Asistencia->marcarSalidasAutomaticas($hoy);
        }

        $empleados = $this->Asistencia->obtenerEmpleadosConAsistenciaHoy($hoy);

        $this->view('asistencia/ver', [
            'empleados' => $empleados
        ], 'dashboard');
    }

   
     // Obtener datos para AJAX (tabla actualizable)
    public function obtenerDatos(): void{
    
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            exit;
        }

        $hoy = date('Y-m-d');
        $ahora = date('H:i:s');

        if ($ahora >= '17:00:00') {
            $this->Asistencia->marcarFaltasAutomaticas($hoy);
        }

        if ($ahora >= '18:00:00') {
            $this->Asistencia->marcarSalidasAutomaticas($hoy);
        }

        $empleados = $this->Asistencia->obtenerDatosAsistencia($hoy);

        header('Content-Type: application/json');
        echo json_encode($empleados);
        exit;
    }


     // Justificar una falta
    public function justificar(): void{
    
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
            exit;
        }

        $id_empleado = $_POST['id_empleado'] ?? 0;
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $motivo = $_POST['motivo'] ?? '';

        if (empty($motivo)) {
            echo json_encode(['ok' => false, 'mensaje' => 'Motivo es obligatorio']);
            exit;
        }

        $resultado = $this->Asistencia->justificarFalta($id_empleado, $fecha, $motivo, $_SESSION['usuario_id']);
        
        echo json_encode($resultado);
        exit;
    }

        // Obtener justificación de una falta
    public function obtenerJustificacion(): void{
    
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['justificada' => false]);
            exit;
        }
        
        $id_empleado = $_GET['id_empleado'] ?? 0;
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        
        $resultado = $this->Asistencia->obtenerJustificacion($id_empleado, $fecha);
        
        echo json_encode($resultado);
        exit;
    }

}