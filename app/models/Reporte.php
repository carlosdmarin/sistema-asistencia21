<?php
// app/models/Reporte.php

class Reporte extends Model 
{
    // Obtener asistencias por fecha específica
    public function getAsistenciaPorFecha(string $fecha): array 
    {
        $sql = "SELECT 
                    e.id_empleado, e.nombre, e.apellido, e.dni,
                    c.nombre_cargo,
                    a.hora_entrada, a.hora_salida,
                    CASE a.estado
                        WHEN 'asistio' THEN 'Asistió'
                        WHEN 'tardanza' THEN 'Tardanza'
                        WHEN 'falto' THEN 'Faltó'
                        ELSE 'Sin marcar'
                    END as estado
                FROM EMPLEADO e 
                INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
                LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado AND a.fecha = :fecha
                ORDER BY e.apellido, e.nombre";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }
}