<?php
// app/models/Usuario.php

class Usuario extends Model 
{   
    // Obtenemos a todos los usuarios
    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("SELECT id_usuario, nombre, clave FROM USUARIO 
        ORDER BY nombre");

        return $stmt->fetchAll();
    }
    // Obtenemos usuarios por su ID 
    public function obtenerPorId(int $id): ?array {
    
        $stmt = $this->pdo->prepare("SELECT id_usuario, nombre, clave FROM USUARIO WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }


    public function registrar(string $usuario, string $clave): array 
    {
        // Validaciones
        if (empty($usuario) || empty($clave)) {
            return ['ok' => false, 'mensaje' => 'Todos los campos son obligatorios.'];
        }
        
        if (strlen($usuario) <= 3) {
            return ['ok' => false, 'mensaje' => 'El usuario debe tener al menos 3 caracteres.'];
        }
        
        if (strlen($clave) <= 3) {
            return ['ok' => false, 'mensaje' => 'La contraseña debe tener al menos 3 caracteres.'];
        }
        
        // Verificar si existe
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM USUARIO WHERE nombre = :nombre LIMIT 1");
        $stmt->execute(['nombre' => $usuario]);
        
        if ($stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'El usuario ya registrado.'];
        }
        
        // Insertar
        $claveHash = password_hash($clave, PASSWORD_BCxqRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO USUARIO (nombre, clave) VALUES (:nombre, :clave)");
        $stmt->execute(['nombre' => $usuario, 'clave' => $claveHash]);
        
        return ['ok' => true, 'mensaje' => 'Usuario registrado correctamente.'];
    }
    
    public function login(string $usuario, string $clave): array 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM USUARIO WHERE nombre = :nombre LIMIT 1");
        $stmt->execute(['nombre' => $usuario]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['ok' => false, 'mensaje' => 'Usuario no registrado, intenta crear tu cuenta.'];
        }
        
        if (!password_verify($clave, $user['clave'])) {
            return ['ok' => false, 'mensaje' => 'Contraseña o usuario incorrectos.'];
        }
        
        return ['ok' => true, 'mensaje' => 'Inicio de sesión exitoso.', 'usuario' => $user];
    }

    // Eliminamos el usuario 
    public function eliminar(int $id): bool{
        $stmt = $this->pdo->prepare("DELETE FROM USUARIO WHERE id_usuario = :id");
        return $stmt->execute(['id' =>$id]);
    }


}