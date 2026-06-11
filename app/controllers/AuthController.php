<?php
// app/controllers/AuthController.php

class AuthController extends Controller
{
    private $usuarioModel;
    public $Usuario;

    public function __construct()
    {
        $this->loadModel('Usuario');
        $this->usuarioModel = $this->Usuario;
    }

    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $clave = $_POST['clave'] ?? '';

            $resultado = $this->usuarioModel->registrar($usuario, $clave);

            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';

            header('Location: ' . BASE_URL);
            exit;
        }
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $clave = $_POST['clave'] ?? '';

            $resultado = $this->usuarioModel->login($usuario, $clave);

            if ($resultado['ok']) {
                $_SESSION['usuario_id'] = $resultado['usuario']['id_usuario'];
                $_SESSION['usuario_nombre'] = $resultado['usuario']['nombre'];
            }

            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo'] = $resultado['ok'] ? 'success' : 'error';

            $destino = $resultado['ok'] ? BASE_URL . '/dashboard' : BASE_URL;
            header('Location: ' . $destino);
            exit;
        }
    }

    public function loginAjax(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido']);
            exit;
        }

        $usuario = $_POST['usuario'] ?? '';
        $clave = $_POST['clave'] ?? '';

        if (empty($usuario) || empty($clave)) {
            echo json_encode(['ok' => false, 'mensaje' => 'Usuario y contraseña son obligatorios']);
            exit;
        }

        $resultado = $this->usuarioModel->login($usuario, $clave);

        if ($resultado['ok']) {
            $_SESSION['usuario_id'] = $resultado['usuario']['id_usuario'];
            $_SESSION['usuario_nombre'] = $resultado['usuario']['nombre'];
        }

        echo json_encode([
            'ok' => $resultado['ok'],
            'mensaje' => $resultado['mensaje']
        ]);
        exit;
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
}