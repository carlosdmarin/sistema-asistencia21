<?php
// app/controllers/EmpleadoController.php

class EmpleadoController extends Controller 
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
        
        // Obtener cargos directo
        $stmt = $this->pdo->query("SELECT * FROM CARGO ORDER BY nombre_cargo");
        $cargos = $stmt->fetchAll();
        
        $this->view('empleado/registrar', [
            'cargos' => $cargos
        ], 'dashboard');
    }
    
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
        
        $nombre   = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $dni      = $_POST['dni'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $id_cargo = $_POST['id_cargo'] ?? 0;
        
        // Validar
        if (empty($nombre) || empty($apellido) || empty($dni)) {
            $_SESSION['mensaje'] = 'Nombre, apellido y DNI son obligatorios.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/empleado/registrar');
            exit;
        }
        
        // Verificar DNI único
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE dni = :dni LIMIT 1");
        $stmt->execute(['dni' => $dni]);
        if ($stmt->fetch()) {
            $_SESSION['mensaje'] = 'Ya existe un empleado con ese DNI.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/empleado/registrar');
            exit;
        }
        
        // Insertar
        $stmt = $this->pdo->prepare("
            INSERT INTO EMPLEADO (nombre, apellido, dni, telefono, id_cargo, id_turno) 
            VALUES (:nombre, :apellido, :dni, :telefono, :id_cargo, 1)
        ");
        $stmt->execute([
            'nombre'    => $nombre,
            'apellido'  => $apellido,
            'dni'       => $dni,
            'telefono'  => $telefono,
            'id_cargo'  => $id_cargo
        ]);
        
        $_SESSION['mensaje'] = 'Empleado registrado correctamente.';
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . '/empleado/ver');
        exit;
    }
    
   public function ver(): void 
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
    
    $busqueda = $_GET['buscar'] ?? '';
    
    if (!empty($busqueda)) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, c.nombre_cargo, t.nombre_turno 
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            WHERE e.nombre LIKE :b 
               OR e.apellido LIKE :b2 
               OR e.dni LIKE :b3 
               OR e.telefono LIKE :b4
               OR c.nombre_cargo LIKE :b5
            ORDER BY e.apellido, e.nombre
        ");
        $stmt->execute([
            'b'  => "%$busqueda%",
            'b2' => "%$busqueda%",
            'b3' => "%$busqueda%",
            'b4' => "%$busqueda%",
            'b5' => "%$busqueda%"
        ]);
    } else {
        $stmt = $this->pdo->query("
            SELECT e.*, c.nombre_cargo, t.nombre_turno 
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            ORDER BY e.apellido, e.nombre
        ");
    }
    
    $empleados = $stmt->fetchAll();
    
    // Si es AJAX, devolver JSON
    if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
        header('Content-Type: application/json');
        echo json_encode($empleados);
        exit;
    }
    
    $this->view('empleado/ver_empleado', [
        'empleados' => $empleados,
        'busqueda' => $busqueda
    ], 'dashboard');
}

      
    // MOSTRAR FORMULARIO DE EDICIÓN
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
    
    // PROCESAR ACTUALIZACIÓN
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
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $dni = $_POST['dni'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $id_cargo = $_POST['id_cargo'] ?? 0;
        
        // Validar
        if (empty($nombre) || empty($apellido) || empty($dni)) {
            $_SESSION['mensaje'] = 'Nombre, apellido y DNI son obligatorios.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/empleado/editar/' . $id);
            exit;
        }
        
        // Verificar DNI único (excepto el actual)
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE dni = :dni AND id_empleado != :id LIMIT 1");
        $stmt->execute(['dni' => $dni, 'id' => $id]);
        if ($stmt->fetch()) {
            $_SESSION['mensaje'] = 'Ya existe otro empleado con ese DNI.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/empleado/editar/' . $id);
            exit;
        }
        
        // Actualizar
        $stmt = $this->pdo->prepare("
            UPDATE EMPLEADO 
            SET nombre = :nombre, apellido = :apellido, dni = :dni, 
                telefono = :telefono, id_cargo = :id_cargo 
            WHERE id_empleado = :id
        ");
        $stmt->execute([
            'nombre'    => $nombre,
            'apellido'  => $apellido,
            'dni'       => $dni,
            'telefono'  => $telefono,
            'id_cargo'  => $id_cargo,
            'id'        => $id
        ]);
        
        $_SESSION['mensaje'] = 'Empleado actualizado correctamente.';
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . '/empleado/ver');
        exit;
    }
    
    // ELIMINAR EMPLEADO
    public function eliminar(int $id): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Verificar que existe
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE id_empleado = :id");
        $stmt->execute(['id' => $id]);
        
        if (!$stmt->fetch()) {
            $_SESSION['mensaje'] = 'Empleado no encontrado.';
            $_SESSION['tipo'] = 'error';
            header('Location: ' . BASE_URL . '/empleado/ver');
            exit;
        }
        
        // Eliminar
        $stmt = $this->pdo->prepare("DELETE FROM EMPLEADO WHERE id_empleado = :id");
        $stmt->execute(['id' => $id]);
        
        $_SESSION['mensaje'] = 'Empleado eliminado correctamente.';
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . '/empleado/ver');
        exit;
    }

    // Registrar asis     .,m nbtencia con lector de código de barras
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