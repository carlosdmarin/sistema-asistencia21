<?php
// app/controllers/ReporteController.php
class ReporteController extends Controller
{
    private $reporteModel;
    
    public function __construct() 
    {
        require_once __DIR__ . '/../models/Reporte.php';
        $this->reporteModel = new Reporte();
    }

    // Mostrar la página de reportes
    public function ver(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->view('reporte/ver', [], 'dashboard');
    }

    // API: Obtener datos para AJAX
    public function apiAsistenciaPorFecha(): void
    {
        // Verificar que el modelo existe
        if (!$this->reporteModel) {
            echo json_encode(['error' => 'Modelo no cargado']);
            exit;
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            exit;
        }

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $datos = $this->reporteModel->getAsistenciaPorFecha($fecha);

        header('Content-Type: application/json');
        echo json_encode($datos);
        exit;
    }

    // Exportar Excel
    public function exportarExcelAsistencia(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $datos = $this->reporteModel->getAsistenciaPorFecha($fecha);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="asistencia_' . $fecha . '.xls"');

        // Cabeceras
        echo "ID\tEmpleado\tDNI\tCargo\tEntrada\tSalida\tEstado\n";

        // Datos
        foreach ($datos as $row) {
            echo $row['id_empleado'] . "\t";
            echo $row['nombre'] . ' ' . $row['apellido'] . "\t";
            echo $row['dni'] . "\t";
            echo $row['nombre_cargo'] . "\t";
            echo ($row['hora_entrada'] ?? '—') . "\t";
            echo ($row['hora_salida'] ?? '—') . "\t";
            echo $row['estado'] . "\n";
        }
        exit;
    }

    // Exportar PDF (usando vista)
    public function exportarPDFAsistencia(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $datos = $this->reporteModel->getAsistenciaPorFecha($fecha);

        $this->view('reporte/pdf_asistencia', [
            'datos' => $datos,
            'fecha' => $fecha
        ], 'pdf');
    }
}