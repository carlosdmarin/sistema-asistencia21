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
        public function obtenerResumenMensual(int $mes, int $anio): array
{
    $diasLaborales = $this->calcularDiasLaborales($mes, $anio);
    
    $stmt = $this->pdo->prepare("
        SELECT 
        e.id_empleado, e.nombre, e.apellido, e.dni, e.telefono,
        c.nombre_cargo, t.nombre_turno,
        COUNT(CASE WHEN a.estado = 'asistio' THEN 1 END) as asistio,
        COUNT(CASE WHEN a.estado = 'tardanza' THEN 1 END) as tardanzas,
        COUNT(CASE WHEN a.estado = 'falto' THEN 1 END) as faltas,
        COUNT(CASE WHEN a.estado = 'falto' AND j.id_justificacion IS NOT NULL THEN 1 END) as justificadas
    FROM EMPLEADO e 
    INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
    INNER JOIN TURNO t ON e.id_turno = t.id_turno
    LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado 
        AND MONTH(a.fecha) = :mes AND YEAR(a.fecha) = :anio
    LEFT JOIN JUSTIFICACION j ON a.id_asistencia = j.id_asistencia
    GROUP BY e.id_empleado
    ORDER BY e.apellido, e.nombre
    ");
    $stmt->execute(['mes' => $mes, 'anio' => $anio]);
    $datos = $stmt->fetchAll();
    
    foreach ($datos as &$row) {
        $presente = $row['asistio'] + $row['tardanzas'];
        $row['porcentaje'] = $diasLaborales > 0 ? round(($presente / $diasLaborales) * 100) : 0;
    }
    
    return [
        'datos' => $datos,
        'dias_laborales' => $diasLaborales,
        'mes' => $mes,
        'anio' => $anio,
        'nombre_mes' => $this->getNombreMes($mes)
    ];
}

private function calcularDiasLaborales(int $mes, int $anio): int
{
    $fecha = new DateTime("$anio-$mes-01");
    $totalDias = $fecha->format('t');
    $diasLaborales = 0;
    for ($i = 1; $i <= $totalDias; $i++) {
        $diaSemana = (int)(new DateTime("$anio-$mes-$i"))->format('N');
        if ($diaSemana <= 6) $diasLaborales++;
    }
    return $diasLaborales;
}

private function getNombreMes(int $mes): string
{
    $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
    return $meses[$mes];
}

}