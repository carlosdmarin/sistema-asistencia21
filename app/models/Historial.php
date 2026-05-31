<?php

class Historial extends Model{

    public function obtenerTodos(): array {
    
        $stmt = $this->pdo->query("
            SELECT e.*, c.nombre_cargo, t.nombre_turno 
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
        ");
        return $stmt->fetchAll();
    }
}