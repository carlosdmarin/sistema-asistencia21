<?php
// app/controllers/AsistenciaController.php

class AsistenciaController extends Controller
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
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
        // Aqui recibimso el dni del empleado que enviamos desde el frontend
        $dni = $_POST['dni'] ?? '';
        // Si nos enviaron datos vacios del dni - Botar error
        if (empty($dni)) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'DNI requerido']);
            exit;
        }
        // aca buscamos al empleado en la DB 
        $stmt = $this->pdo->prepare("
            SELECT e.*, c.nombre_cargo,
                t.nombre_turno,
                t.hora_inicio,
                t.hora_salida,
                t.tolerancia_minutos
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            WHERE e.dni = :dni 
            LIMIT 1
        ");
        $stmt->execute(['dni' => $dni]);
        $empleado = $stmt->fetch();

        // Verificamos si existe el empleado 
        if (!$empleado) {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Empleado no encontrado',
                'tipo' => 'no_encontrado'
            ]);
            exit;
        }

        // Verificamos si marco entrada
        // Es la variable donde almacenamos la fecha del servidor
        $hoy = date('Y-m-d'); // Ejemplo: "2026-05-24"
        // Es la varibale donde almacenamos la hora del servidor
        $ahora = date('H:i:s');  // Ejemplo: "08:15:30"

        // Consultamos si ha marco entrada hoy 
        $stmt = $this->pdo->prepare("
            SELECT * FROM ASISTENCIA 
            WHERE id_empleado = :id AND fecha = :fecha 
            LIMIT 1
        ");
        $stmt->execute(['id' => $empleado['id_empleado'], 'fecha' => $hoy]);
        $asistenciaHoy = $stmt->fetch();

        // SI marco entrada, registrar salida
        if ($asistenciaHoy) {
            if (empty($asistenciaHoy['hora_salida'])) {
                // registramos la salida del empleado 
                $stmt = $this->pdo->prepare("
                    UPDATE ASISTENCIA 
                    SET hora_salida = :hora 
                    WHERE id_asistencia = :id
                ");
                $stmt->execute(['hora' => $ahora, 'id' => $asistenciaHoy['id_asistencia']]);

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
                // Enviamos mensaje si marco entrada y salida hoy
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'Ya registraste entrada y salida hoy',
                    'tipo' => 'duplicado'
                ]);
                exit;
            }
        }
        // Determinamos si es llego tarde o no 
        $estado = $this->determinarEstado(
            $empleado['hora_inicio'],
            $empleado['tolerancia_minutos'],
            $ahora
            );

        // Registramos la entrada en la BASE DE DATOS con su estado
        $stmt = $this->pdo->prepare("
            INSERT INTO ASISTENCIA (id_empleado, fecha, hora_entrada, estado) 
            VALUES (:id, :fecha, :hora, :estado)
        ");
        $stmt->execute([
            'id' => $empleado['id_empleado'],
            'fecha' => $hoy,
            'hora' => $ahora,
            'estado' => $estado
        ]);

        $mensaje = $estado === 'tardanza' ? '⚠ Llegaste tarde' : 'Asistencia registrada';
        // Enviamos la respuesta al javascript para mostrar en el frontend
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
        $hoy = date('Y-m-d');
        $stmt = $this->pdo->prepare("
            SELECT a.*, e.nombre, e.apellido, c.nombre_cargo 
            FROM ASISTENCIA a 
            INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            WHERE a.fecha = :hoy
            ORDER BY a.id_asistencia DESC 
            LIMIT 10
        ");
        $stmt->execute(['hoy' => $hoy]);
        $asistencias = $stmt->fetchAll();

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

    // Determinar estado (asistio o tardanza)
    private function determinarEstado(string $horaInicio, int $toleranciaMinutos, string $horaLlegada): string
    {
        $horaLimite = date('H:i:s', strtotime($horaInicio . ' + ' . $toleranciaMinutos . ' minutes'));
        return $horaLlegada <= $horaLimite ? 'asistio' : 'tardanza';
    }

    // Ver asistencias del día (solo administradores)
    public function ver(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $hoy = date('Y-m-d');
        $ahora = date('H:i:s');

        // Solo marcar faltos después de las 5 PM
        if ($ahora >= '17:00:00') {
            $this->marcarFaltasSilencioso();
        }

        // Solo marcar salidas automáticas después de las 6 PM
        if ($ahora >= '18:00:00') {
            $this->marcarSalidasAutomaticasSilencioso();
        }

        $stmt = $this->pdo->prepare("
            SELECT 
                e.id_empleado, e.nombre, e.apellido, e.dni, e.telefono,
                c.nombre_cargo, t.nombre_turno,
                a.id_asistencia, a.hora_entrada, a.hora_salida, a.estado, a.fecha
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado AND a.fecha = :hoy
            ORDER BY e.apellido, e.nombre
        ");
        $stmt->execute(['hoy' => $hoy]);
        $empleados = $stmt->fetchAll();

        $this->view('asistencia/ver', [
            'empleados' => $empleados
        ], 'dashboard');
    }

    // Marcar faltos automáticamente (solo para empleados sin registro)
    public function marcarFaltasSilencioso(): void
    {
        $hoy = date('Y-m-d');

        // Solo ejecutar si es después de las 5 PM
        if (date('H:i:s') < '17:00:00') {
            return;
        }

        // Empleados sin registro hoy
        $stmt = $this->pdo->prepare("
            SELECT e.id_empleado 
            FROM EMPLEADO e 
            WHERE e.id_empleado NOT IN (
                SELECT id_empleado FROM ASISTENCIA WHERE fecha = :hoy
            )
        ");
        $stmt->execute(['hoy' => $hoy]);
        $faltantes = $stmt->fetchAll();

        foreach ($faltantes as $emp) {
            // Verificar que no exista ya un registro (por si acaso)
            $check = $this->pdo->prepare("SELECT 1 FROM ASISTENCIA WHERE id_empleado = :id AND fecha = :fecha");
            $check->execute(['id' => $emp['id_empleado'], 'fecha' => $hoy]);
            if (!$check->fetch()) {
                $insert = $this->pdo->prepare("
                    INSERT INTO ASISTENCIA (id_empleado, fecha, hora_entrada, estado) 
                    VALUES (:id, :fecha, NULL, 'falto')
                ");
                $insert->execute([
                    'id' => $emp['id_empleado'],
                    'fecha' => $hoy
                ]);
            }
        }
    }

    // Marcar salidas automáticas (para los que olvidaron marcar salida)
    public function marcarSalidasAutomaticasSilencioso(): void
    {
        $hoy = date('Y-m-d');

        // Solo ejecutar si es después de las 6 PM
        if (date('H:i:s') < '18:00:00') {
            return;
        }

        $stmt = $this->pdo->prepare("
            UPDATE ASISTENCIA a
            INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
            INNER JOIN TURNO t ON e.id_turno = t.id_turno
            SET a.hora_salida = t.hora_salida
            WHERE a.fecha = :fecha 
              AND a.hora_entrada IS NOT NULL 
              AND a.hora_salida IS NULL
              AND a.estado IN ('asistio', 'tardanza')
        ");
        $stmt->execute([':fecha' => $hoy]);
    }

    // Obtener datos para AJAX (tabla actualizable)
    public function obtenerDatos(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            exit;
        }

        $hoy = date('Y-m-d');
        $ahora = date('H:i:s');

        if ($ahora >= '17:00:00') {
            $this->marcarFaltasSilencioso();
        }

        if ($ahora >= '18:00:00') {
            $this->marcarSalidasAutomaticasSilencioso();
        }

        $stmt = $this->pdo->prepare("
            SELECT 
                e.id_empleado, e.nombre, e.apellido, e.dni,
                c.nombre_cargo, t.nombre_turno,
                a.hora_entrada, a.hora_salida, 
                COALESCE(a.estado, 'sin_marcar') as estado
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado AND a.fecha = :hoy
            ORDER BY e.apellido, e.nombre
        ");

        $stmt->execute(['hoy' => $hoy]);
        $empleados = $stmt->fetchAll();

        header('Content-Type: application/json');
        echo json_encode($empleados);
        exit;
    }
}