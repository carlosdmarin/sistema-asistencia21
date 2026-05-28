<?php
// app/models/Asistencia.php

class Asistencia extends Model 
{
    // Contar asistencias de hoy (asistio + tardanza)
    public function contarAsistenciasHoy(): int 
    {
        $fecha = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total FROM ASISTENCIA 
                WHERE fecha = :fecha AND estado IN ('asistio', 'tardanza')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
    
    // Contar ausentes de hoy (empleados que no han registrado asistencia)
    public function contarAusentesHoy(): int 
    {
        $empleadoModel = new Empleado();
        $totalEmpleados = $empleadoModel->contarTodos();
        $asistieron = $this->contarAsistenciasHoy();
        return $totalEmpleados - $asistieron;
    }
    
    // Contar tardanzas de hoy
    public function contarTardanzasHoy(): int 
    {
        $fecha = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total FROM ASISTENCIA 
                WHERE fecha = :fecha AND estado = 'tardanza'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
    
    // Calcular porcentaje de asistencia hoy
    public function calcularPorcentajeAsistenciaHoy(): int 
    {
        $empleadoModel = new Empleado();
        $totalEmpleados = $empleadoModel->contarTodos();
        $asistieron = $this->contarAsistenciasHoy();
        
        if ($totalEmpleados == 0) {
            return 0;
        }
        
        return (int) round(($asistieron / $totalEmpleados) * 100);
    }
    
    // Obtener asistencias por semana (últimos 7 días)
    public function obtenerAsistenciasPorSemana(): array 
    {
        $asistencias = [];
        
        // Obtener fecha del lunes de esta semana
        $lunes = date('Y-m-d', strtotime('monday this week'));
        
        for ($i = 0; $i < 6; $i++) {
            $fecha = date('Y-m-d', strtotime($lunes . ' +' . $i . ' days'));
            
            $sql = "SELECT COUNT(*) as total FROM ASISTENCIA 
                    WHERE fecha = :fecha AND estado IN ('asistio', 'tardanza')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':fecha' => $fecha]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $asistencias[] = (int) ($result['total'] ?? 0);
        }
        
        return $asistencias;
    }
    
    // Obtener últimos registros de asistencia
    public function obtenerUltimosRegistros(int $limite = 5): array 
    {
        $sql = "SELECT a.*, e.nombre, e.apellido 
                FROM ASISTENCIA a
                JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
                ORDER BY a.fecha DESC, a.hora_entrada DESC
                LIMIT :limite";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        $registros = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Convertir estado a español para mostrar
            $estado = match($row['estado']) {
                'asistio' => 'Presente',
                'tardanza' => 'Tardanza',
                'falto' => 'Ausente',
                default => ucfirst($row['estado'])
            };
            
            $registros[] = [
                'empleado' => $row['nombre'] . ' ' . $row['apellido'],
                'empleado_nombre' => $row['nombre'] . ' ' . $row['apellido'],
                'fecha' => $row['fecha'],
                'hora' => date('h:i A', strtotime($row['hora_entrada'])),
                'estado' => $estado
            ];
        }
        
        return $registros;
    }
}