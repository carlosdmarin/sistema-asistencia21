<?php

class Asistencia extends Model
{

    // Buscar empleado por DNI (con sus datos de cargo y turno)
    public function buscarEmpleadoPorDni(string $dni): ?array
    {
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
        return $stmt->fetch() ?: null;
    }

    // Buscar asistencias del dia para un empleado
    public function buscarAsistenciaHoy(int $idEmpleado, string $fecha): ?array
    {

        $stmt = $this->pdo->prepare("
        SELECT * FROM ASISTENCIA 
        WHERE id_empleado = :id AND fecha = :fecha 
        LIMIT 1
        ");
        $stmt->execute(['id' => $idEmpleado, 'fecha' => $fecha]);
        return $stmt->fetch() ?: null;
    }

    // Registrar entrada
    public function registrarEntrada(int $idEmpleado, string $fecha, string $hora, string $estado): int
    {

        $stmt = $this->pdo->prepare("
        INSERT INTO ASISTENCIA (id_empleado, fecha, hora_entrada, estado)
        VALUES(:id, :fecha, :hora, :estado)
        ");

        $stmt->execute([
            'id' => $idEmpleado,
            'fecha' => $fecha,
            'hora' => $hora,
            'estado' => $estado
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    // Registrar salida 
    public function registrarSalida(int $idAsistencia, string $hora): bool
    {

        $stmt = $this->pdo->prepare("
        UPDATE ASISTENCIA 
        SET hora_salida = :hora
        WHERE id_asistencia = :id
        ");
        return $stmt->execute(['hora' => $hora, 'id' => $idAsistencia]);
    }

    // Determinar estado (asistio o tardanza)
    public function determinarEstado(string $horaInicio, int $toleranciaMinutos, string $horaLlegada): string
    {

        $horaLimite = date('H:i:s', strtotime($horaInicio . ' + ' . $toleranciaMinutos . 'minutes'));
        return $horaLlegada <= $horaLimite ? 'asistio' : 'tardanza';
    }

    // Obtener las 10 ultimas asistencias
    public function obtenerUltimasAsistencias(int $limite = 10): array
    {

        $hoy = date('Y-m-d');
        $stmt = $this->pdo->prepare("
        SELECT a.*, e.nombre, e.apellido, c.nombre_cargo
        FROM ASISTENCIA a
        INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
        INNER JOIN CARGO c ON e.id_cargo = c.id_cargo
        WHERE a.fecha = :hoy
        ORDER BY a.id_asistencia DESC
        LIMIT :limite
        ");

        $stmt->bindParam(':hoy', $hoy);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener todos los empleados con su asistencia de hoy (CON JUSTIFICACIÓN)
    public function obtenerEmpleadosConAsistenciaHoy(string $fecha): array
    {
        $sql = "SELECT 
                e.id_empleado, e.nombre, e.apellido, e.dni, e.telefono,
                c.nombre_cargo, t.nombre_turno,
                a.id_asistencia, a.hora_entrada, a.hora_salida, a.estado, a.fecha,
                CASE WHEN j.id_justificacion IS NOT NULL THEN 1 ELSE 0 END as justificado
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado AND a.fecha = :fecha
            LEFT JOIN JUSTIFICACION j ON a.id_asistencia = j.id_asistencia
            ORDER BY e.apellido, e.nombre";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['fecha' => $fecha]);
        $result = $stmt->fetchAll();

        // DEPURACIÓN
        error_log("=== obtenerEmpleadosConAsistenciaHoy ===");
        foreach ($result as $row) {
            error_log("ID: {$row['id_empleado']} - justificado: " . ($row['justificado'] ?? 'NULL'));
        }

        return $result;
    }
    // Obtener empleados con asistencia y justificacion para ajax 
    public function obtenerDatosAsistencia(string $fecha): array
    {

        $stmt = $this->pdo->prepare("
        SELECT 
                e.id_empleado, e.nombre, e.apellido, e.dni,
                c.nombre_cargo, t.nombre_turno,
                a.hora_entrada, a.hora_salida, 
                COALESCE(a.estado, 'sin_marcar') as estado,
                CASE WHEN j.id_justificacion IS NOT NULL THEN 1 ELSE 0 END as justificado
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado AND a.fecha = :fecha
            LEFT JOIN JUSTIFICACION j ON a.id_asistencia = j.id_asistencia
            ORDER BY e.apellido, e.nombre
        ");

        $stmt->execute(['fecha' => $fecha]);
        $empleados = $stmt->fetchAll();

        // LIMPIAR BARRAS DE LOS NOMBRES
        foreach ($empleados as &$emp) {
            $emp['nombre'] = str_replace('\\', '', $emp['nombre']);
            $emp['apellido'] = str_replace('\\', '', $emp['apellido']);
            $emp['nombre_cargo'] = str_replace('\\', '', $emp['nombre_cargo']);
            $emp['nombre_turno'] = str_replace('\\', '', $emp['nombre_turno']);
        }
        return $empleados;
    }

    // Marcar falta a los empleados sin registro (DESPUÉS de que terminó el turno)
    public function marcarFaltasAutomaticas(string $fecha): int{ 
    
    $stmt = $this->pdo->prepare("
        INSERT INTO ASISTENCIA (id_empleado, fecha, estado)
        SELECT e.id_empleado, :fecha, 'falto'
        FROM EMPLEADO e 
        INNER JOIN TURNO t ON e.id_turno = t.id_turno
        WHERE e.id_empleado NOT IN (
            SELECT id_empleado FROM ASISTENCIA WHERE fecha = :fecha
        )
        AND NOW() > CONCAT(:fecha, ' ', t.hora_salida)
    ");
    
    $stmt->execute(['fecha' => $fecha]);
    $contador = $stmt->rowCount();
    
    if ($contador > 0) {
        error_log("Faltas automáticas marcadas: $contador para fecha $fecha");
    }
    
    return $contador;
}

    // Marcar salidas automaticamente para los que olvidaron marcar salida (DESPUÉS del turno)
    public function marcarSalidasAutomaticas(string $fecha): int {

    $stmt = $this->pdo->prepare("
        UPDATE ASISTENCIA a
        INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
        INNER JOIN TURNO t ON e.id_turno = t.id_turno
        SET a.hora_salida = t.hora_salida
        WHERE a.fecha = :fecha 
          AND a.hora_entrada IS NOT NULL 
          AND a.hora_salida IS NULL
          AND a.estado IN ('asistio', 'tardanza')
          AND NOW() > CONCAT(:fecha, ' ', t.hora_salida)
    ");
    
    $stmt->execute(['fecha' => $fecha]);
    $contador = $stmt->rowCount();
    
    if ($contador > 0) {
        error_log("Salidas automáticas marcadas: $contador para fecha $fecha");
    }
    
    return $contador;
}

    // Justificar una falta 
    public function justificarFalta(int $idEmpleado, string $fecha, string $motivo, int $idUsuario): array
    {

        // buscar si existe una asistencia
        $stmt = $this->pdo->prepare("
        SELECT id_asistencia FROM ASISTENCIA WHERE id_empleado = :id AND fecha = :fecha
        ");

        $stmt->execute(['id' => $idEmpleado, 'fecha' => $fecha]);
        $asistencia = $stmt->fetch();

        // Si no existe lo ponemos como 'falto'
        if (!$asistencia) {
            $stmt = $this->pdo->prepare("
            INSERT INTO ASISTENCIA (id_empleado, fecha, estado)
            VALUES (:id, :fecha, 'falto')
            ");
            $stmt->execute(['id' => $idEmpleado, 'fecha' => $fecha]);
            $asistencia_id = $this->pdo->lastInsertId();
        } else {
            $asistencia_id = $asistencia['id_asistencia'];
        }

        // Verificar si ya existe una justificación
        $stmt = $this->pdo->prepare("SELECT id_justificacion FROM JUSTIFICACION WHERE id_asistencia = :id");
        $stmt->execute(['id' => $asistencia_id]);

        if ($stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'Esta asistencia ya está justificada'];
        }

        // Insertar justificación
        $stmt = $this->pdo->prepare("
            INSERT INTO JUSTIFICACION (id_asistencia, motivo, justificado_por) 
            VALUES (:id, :motivo, :user)
        ");
        $stmt->execute([
            'id' => $asistencia_id,
            'motivo' => $motivo,
            'user' => $idUsuario
        ]);

        return ['ok' => true, 'mensaje' => 'Justificación guardada correctamente'];
    }

    // Obtener justificación de una falta
    public function obtenerJustificacion(int $idEmpleado, string $fecha): array
    {
        $stmt = $this->pdo->prepare("
            SELECT j.motivo, j.fecha_justificacion 
            FROM JUSTIFICACION j
            INNER JOIN ASISTENCIA a ON j.id_asistencia = a.id_asistencia
            WHERE a.id_empleado = :id_empleado AND a.fecha = :fecha
        ");
        $stmt->execute(['id_empleado' => $idEmpleado, 'fecha' => $fecha]);
        $justificacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($justificacion) {
            return [
                'justificada' => true,
                'motivo' => $justificacion['motivo'],
                'fecha_justificacion' => $justificacion['fecha_justificacion']
            ];
        }

        return ['justificada' => false];
    }









}