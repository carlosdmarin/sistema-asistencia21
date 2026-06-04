<?php
// app/models/Turno.php

class Turno extends Model 
{
    public function obtenerTodos(): array 
    {
        $stmt = $this->pdo->query("SELECT * FROM TURNO ORDER BY nombre_turno");
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId(int $id): ?array 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM TURNO WHERE id_turno = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
    
    public function registrar(string $nombre, string $horaInicio, string $horaSalida, int $tolerancia): array 
    {
        if (empty($nombre)) {
            return ['ok' => false, 'mensaje' => 'El nombre del turno es obligatorio'];
        }
        
        $stmt = $this->pdo->prepare("
            INSERT INTO TURNO (nombre_turno, hora_inicio, hora_salida, tolerancia_minutos) 
            VALUES (:nombre, :hora_inicio, :hora_salida, :tolerancia)
        ");
        $stmt->execute([
            'nombre' => $nombre,
            'hora_inicio' => $horaInicio,
            'hora_salida' => $horaSalida,
            'tolerancia' => $tolerancia
        ]);
        
        return ['ok' => true, 'mensaje' => 'Turno registrado correctamente'];
    }
    
    public function actualizar(int $id, string $nombre, string $horaInicio, string $horaSalida, int $tolerancia): array 
    {
        if (empty($nombre)) {
            return ['ok' => false, 'mensaje' => 'El nombre del turno es obligatorio'];
        }
        
        $stmt = $this->pdo->prepare("
            UPDATE TURNO 
            SET nombre_turno = :nombre, hora_inicio = :hora_inicio, 
                hora_salida = :hora_salida, tolerancia_minutos = :tolerancia
            WHERE id_turno = :id
        ");
        $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'hora_inicio' => $horaInicio,
            'hora_salida' => $horaSalida,
            'tolerancia' => $tolerancia
        ]);
        
        return ['ok' => true, 'mensaje' => 'Turno actualizado correctamente'];
    }
    
    public function eliminar(int $id): array 
    {
        // Verificar si hay empleados con este turno
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE id_turno = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        
        if ($stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'No puedes eliminar este turno porque tiene empleados asociados'];
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM TURNO WHERE id_turno = :id");
        $stmt->execute(['id' => $id]);
        
        return ['ok' => true, 'mensaje' => 'Turno eliminado correctamente'];
    }
}