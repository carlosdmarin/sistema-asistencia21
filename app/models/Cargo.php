<?php
// app/models/Cargo.php

class Cargo extends Model {

    //  Funcion para obtner todos los cargos
    public function obtenerTodos(): array 
    {
        $stmt = $this->pdo->query("SELECT * FROM CARGO ORDER BY nombre_cargo");
        return $stmt->fetchAll();
    }
    // Guardar un nuevo cargo
    public function registrarCargo(string $nombre): array {
    
    // Validar que no esté vacío
    if (empty($nombre)) {
        return ['ok' => false, 'mensaje' => 'Nombre del cargo es obligatorio.'];
    }
    
    // Verificar si ya existe
    $stmt = $this->pdo->prepare("SELECT id_cargo FROM CARGO WHERE nombre_cargo = :nombre LIMIT 1");
    $stmt->execute(['nombre' => $nombre]);
    
    if ($stmt->fetch()) {
        return ['ok' => false, 'mensaje' => 'Ya existe un cargo con ese nombre.'];
    }
    
    // Insertar el cargo
    $stmt = $this->pdo->prepare("INSERT INTO CARGO (nombre_cargo) VALUES (:nombre)");
    $stmt->execute(['nombre' => $nombre]);
    
    return ['ok' => true, 'mensaje' => 'Cargo registrado correctamente.'];
    }

    public function eliminarCargo(int $id): array{

        // Verificamos si existe 
        $stmt = $this->pdo->prepare("SELECT id_cargo FROM  CARGO WHERE id_cargo = :id");
        $stmt->execute(['id' => $id]);

        if(!$stmt->fetch()){
            return ['ok' => false, 'mensaje' => 'Cargo no encontrado.'];
        }
        // Verificamos si hay empleados en ese cargo
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE id_cargo = :id
        LIMIT 1 ");

        $stmt->execute(['id' => $id]);

        if($stmt->fetch()){
            return ['ok' =>false, 'mensaje' => 'No puedes eliminar este cargo por que tiene empleados asociados.'];
        }

        // Eliminar
        $stmt = $this->pdo->prepare("DELETE FROM CARGO WHERE id_cargo = :id");
        $stmt->execute(['id' => $id]);

        return['ok' => true, 'mensaje' => 'Cargo eliminado correctamente'];
    } 

    public function obtenerPorId(int $id): ?array 
{
    $stmt = $this->pdo->prepare("SELECT * FROM CARGO WHERE id_cargo = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
    }   
    
    public function actualizarCargo(int $id, string $nombre): array {

    if (empty($nombre)) {
        return ['ok' => false, 'mensaje' => 'El nombre del cargo es obligatorio.'];
    }
    
    // Verificar si ya existe otro cargo con ese nombre
    $stmt = $this->pdo->prepare("SELECT id_cargo FROM CARGO WHERE nombre_cargo = :nombre AND id_cargo != :id");
    $stmt->execute(['nombre' => $nombre, 'id' => $id]);
    
    if ($stmt->fetch()) {
        return ['ok' => false, 'mensaje' => 'Ya existe otro cargo con ese nombre.'];
    }
    
    $stmt = $this->pdo->prepare("UPDATE CARGO SET nombre_cargo = :nombre WHERE id_cargo = :id");
    $stmt->execute(['nombre' => $nombre, 'id' => $id]);
    
    return ['ok' => true, 'mensaje' => 'Cargo actualizado correctamente.'];
    }

}