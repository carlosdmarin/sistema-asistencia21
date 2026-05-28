<?php
// app/models/Cargo.php

class Cargo extends Model 
{
    public function obtenerTodos(): array 
    {
        $stmt = $this->pdo->query("SELECT * FROM CARGO ORDER BY nombre_cargo");
        return $stmt->fetchAll();
    }
}