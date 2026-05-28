<?php
// app/models/Empleado.php

class Empleado extends Model 
{
    public function registrar(array $datos): array 
    {
        // Validaciones
        if (empty($datos['nombre']) || empty($datos['apellido']) || empty($datos['dni'])) {
            return ['ok' => false, 'mensaje' => 'Nombre, apellido y DNI son obligatorios.'];
        }
        
        if (strlen($datos['dni']) !== 8) {
            return ['ok' => false, 'mensaje' => 'El DNI debe tener 8 dígitos.'];
        }
        
        // Verificar DNI único
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE dni = :dni LIMIT 1");
        $stmt->execute(['dni' => $datos['dni']]);
        if ($stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'Ya existe un empleado con ese DNI.'];
        }
        
        // Insertar
        $stmt = $this->pdo->prepare("
            INSERT INTO EMPLEADO (nombre, apellido, dni, telefono, id_cargo, id_turno) 
            VALUES (:nombre, :apellido, :dni, :telefono, :id_cargo, :id_turno)
        ");
        
        $stmt->execute([
            'nombre'    => $datos['nombre'],
            'apellido'  => $datos['apellido'],
            'dni'       => $datos['dni'],
            'telefono'  => $datos['telefono'] ?? '',
            'id_cargo'  => $datos['id_cargo'],
            'id_turno'  => $datos['id_turno']
        ]);
        
        return ['ok' => true, 'mensaje' => 'Empleado registrado correctamente.'];
    }
    
    public function obtenerTodos(): array 
    {
        $stmt = $this->pdo->query("
            SELECT e.*, c.nombre_cargo, t.nombre_turno 
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
        ");
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId(int $id): ?array 
    {
        $stmt = $this->pdo->prepare("
            SELECT e.*, c.nombre_cargo, t.nombre_turno 
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            WHERE e.id_empleado = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
    // metodo 
    public function contarTodos(): int 
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM EMPLEADO");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
}