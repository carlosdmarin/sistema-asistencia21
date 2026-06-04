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
    public function buscarEmpleados(string $busqueda = ''): array{
        if(!empty($busqueda)){
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
       return $stmt->fetchAll();
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
    public function contarTodos(): int 
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM EMPLEADO");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
    public function actualizarEmpleado(int $id, array $datos): array{

        // Validaciones
        if(empty($datos['nombre']) || empty($datos['apellido']) || empty($datos['dni'])){
            return ['ok' => false, 'mensaje' => 'Nombre, apellido y DNI son obligatorios'];
        }

        //  Verificamos si se escribio bien el DNI 
        if(strlen($datos['dni']) !==8){
            return ['ok' => false, 'mensaje' => 'El DNI debe tener 8 digitos'];
        }
        //  Verificamos DNI unico exepto el actual
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE dni = :dni 
        AND id_empleado != :id LIMIT 1");

        $stmt->execute(['dni' => $datos['dni'], 'id' => $id]);
        if($stmt->fetch()){
            return ['ok' => false, 'mensaje' => 'Ya existe otro empleado con ese DNI.'];
        }

        // Actualizamos 
        $stmt = $this->pdo->prepare("UPDATE EMPLEADO
        SET nombre = :nombre, apellido = :apellido, dni = :dni, 
        telefono = :telefono, id_cargo = :id_cargo, id_turno = :id_turno
        WHERE id_empleado = :id"
       );

       $stmt->execute([
        'nombre'    => $datos['nombre'],
        'apellido'  => $datos['apellido'],
        'dni'       => $datos['dni'],
        'telefono'  => $datos['telefono'],
        'id_cargo'  => $datos['id_cargo'],
        'id_turno'  => $datos['id_turno'],
        'id'        => $id
        
       ]);

       return ['ok' => true, 'mensaje' => 'Empleado Actualizado correctamente'];

    }
    public function eliminarEmpleado(int $id): array{
        
        // Vereficamos que existe 
        $stmt = $this->pdo->prepare("SELECT id_empleado FROM EMPLEADO WHERE id_empleado = :id");
        $stmt->execute(['id' => $id]);

        if(!$stmt->fetch()){
            return ['ok' => false, 'mensaje' => 'Empleado no encontrado'];
        }

        $stmt = $this->pdo->prepare("DELETE FROM EMPLEADO WHERE id_empleado = :id");
        $stmt->execute(['id' => $id]);

        return ['ok' => true,  'mensaje' => 'Empleado eliminado correctamente'];
    }
}