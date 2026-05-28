<?php
// app/controllers/DashboardController.php

class DashboardController extends Controller 
{
    public function index(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Instanciar modelos
        require_once __DIR__ . '/../models/Empleado.php';
        require_once __DIR__ . '/../models/Asistencia.php';
        
        $empleadoModel = new Empleado();
        $asistenciaModel = new Asistencia();
        
        // Obtener datos
        $totalEmpleados = $empleadoModel->contarTodos();
        $asistenciasHoy = $asistenciaModel->contarAsistenciasHoy();
        $ausentesHoy = $asistenciaModel->contarAusentesHoy();
        $tardanzasHoy = $asistenciaModel->contarTardanzasHoy();
        $porcentajeAsistencia = $asistenciaModel->calcularPorcentajeAsistenciaHoy();
        $asistenciasPorSemana = $asistenciaModel->obtenerAsistenciasPorSemana();
        $ultimosRegistros = $asistenciaModel->obtenerUltimosRegistros(5);
        
        // Pasar datos a la vista
        $this->view('dashboard/index', [
            'totalEmpleados' => $totalEmpleados,
            'asistenciasHoy' => $asistenciasHoy,
            'ausentesHoy' => $ausentesHoy,
            'tardanzasHoy' => $tardanzasHoy,
            'porcentajeAsistencia' => $porcentajeAsistencia,
            'asistenciasPorSemana' => $asistenciasPorSemana,
            'ultimosRegistros' => $ultimosRegistros
        ], 'dashboard');
    }

    // API : Devuelve los datos en JSON (PARA ACUTLIZAR SIN RECARGAR AL PAGINA)
    public function obtenerDatos(): void{
        if(!isset($_SESSION['usuario_id'])){
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        require_once __DIR__ . '/../models/Empleado.php';
        require_once __DIR__ . '/../models/Asistencia.php';

        $empleadoModel = new Empleado();
        $asistenciaModel = new Asistencia();

        $data = [
            'totalEmpleados' => $empleadoModel->contarTodos(),
            'asistenciasHoy' => $asistenciaModel->contarAsistenciasHoy(),
            'ausentesHoy' => $asistenciaModel->contarAusentesHoy(),
            'tardanzasHoy' => $asistenciaModel->contarTardanzasHoy(),
            'porcentajeAsistencia' => $asistenciaModel->calcularPorcentajeAsistenciaHoy(),
            'asistenciasPorSemana' => $asistenciaModel->obtenerAsistenciasPorSemana(),
            'ultimosRegistros' => $asistenciaModel->obtenerUltimosRegistros(5)
        ];

        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }

}