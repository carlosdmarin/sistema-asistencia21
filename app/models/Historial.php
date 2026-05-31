<?php

class Historial extends Model{

    // Obtenemos asistencias por fecha
    public function obtenerPorFecha(string $fecha): array {
    
        $stmt = $this->pdo->prepare("
            SELECT 
            e.nombre, e.apellido, e.dni,
            c.nombre_cargo, t.nombre_turno,
            a.hora_entrada, a.hora_salida, a.estado, a.fecha
        FROM ASISTENCIA a
        INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
        INNER JOIN CARGO c ON e.id_cargo = c.id_cargo
        INNER JOIN TURNO t ON e.id_turno = t.id_turno
        WHERE a.fecha = :fecha
        ORDER BY a.hora_entrada
        ");
        $stmt->execute(['fecha' => $fecha]);
        return $stmt->fetchAll();
    }

    // Obtenemos las estadisticas de del dia
    public function obtenerEstadisticasPorFecha(string $fecha): array{
        $stmt = $this->pdo->prepare("
            SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN estado = 'asistio' THEN 1 END) as asistencias,
            COUNT(CASE WHEN estado = 'tardanza' THEN 1 END) as tardanzas,
            COUNT(CASE WHEN estado = 'falto' THEN 1 END) as faltas
        FROM ASISTENCIA 
        WHERE fecha = :fecha
        ");

        $stmt->execute(['fecha' => $fecha]);
        return $stmt->fetch() ?: ['total' => 0, 'asistencias' => 0, 'tardanzas' => 0, 'faltas' => 0];
    }
}
