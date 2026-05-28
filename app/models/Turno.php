<?php
// app/models/Turno.php

class Turno extends Model 
{
    public function obtenerTodos(): array 
    {
        $stmt = $this->pdo->query("SELECT * FROM TURNO ORDER BY nombre_turno");
        return $stmt->fetchAll();
    }
}