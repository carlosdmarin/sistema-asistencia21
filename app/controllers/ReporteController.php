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

    // API: Obtener datos para AJAX (asistencia por fecha)
    public function apiAsistenciaPorFecha(): void
    {
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

    // Exportar Excel (Asistencia por fecha)
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
        echo "ID\tEmpleado\tDNI\tCargo\tEntrada\tSalida\tEstado\n";
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

    // Exportar PDF (Asistencia por fecha)
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

    // =============================================
    // REPORTE MENSUAL (RESUMEN)
    // =============================================

    // Devuelve JSON para el reporte mensual
    public function resumenMensual(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            exit;
        }
        $mes = $_GET['mes'] ?? date('m');
        $anio = $_GET['anio'] ?? date('Y');
        $this->loadModel('Reporte');
        $data = $this->Reporte->obtenerResumenMensual((int)$mes, (int)$anio);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Exportar Excel - Resumen Mensual (con justificadas)
    public function exportarExcelResumenMensual(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $mes = $_GET['mes'] ?? date('m');
        $anio = $_GET['anio'] ?? date('Y');
        $this->loadModel('Reporte');
        $data = $this->Reporte->obtenerResumenMensual((int)$mes, (int)$anio);
        $datos = $data['datos'];
        $nombreMes = $data['nombre_mes'];
        $diasLaborales = $data['dias_laborales'];

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="resumen_' . $nombreMes . '_' . $anio . '.xls"');

        echo "ID\tEmpleado\tDNI\tCargo\tTurno\tTeléfono\tAsistió\tTardanzas\tFaltas\tJustificadas\t% Asistencia\n";
        echo "Días laborales del mes:\t$diasLaborales\n\n";

        foreach ($datos as $row) {
            echo $row['id_empleado'] . "\t";
            echo $row['nombre'] . ' ' . $row['apellido'] . "\t";
            echo $row['dni'] . "\t";
            echo $row['nombre_cargo'] . "\t";
            echo $row['nombre_turno'] . "\t";
            echo ($row['telefono'] ?? '—') . "\t";
            echo $row['asistio'] . "\t";
            echo $row['tardanzas'] . "\t";
            echo $row['faltas'] . "\t";
            echo $row['justificadas'] . "\t";
            echo $row['porcentaje'] . "%\n";
        }
        exit;
    }

    // Exportar PDF - Resumen Mensual (con justificadas)
    public function exportarPDFResumenMensual(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $mes = $_GET['mes'] ?? date('m');
        $anio = $_GET['anio'] ?? date('Y');
        $this->loadModel('Reporte');
        $data = $this->Reporte->obtenerResumenMensual((int)$mes, (int)$anio);
        $datos = $data['datos'];
        $nombreMes = $data['nombre_mes'];
        $diasLaborales = $data['dias_laborales'];

        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Resumen Mensual - ' . $nombreMes . ' ' . $anio . '</title>
            <style>
                body { font-family: Arial; margin: 20px; }
                h1 { color: #172535; text-align: center; }
                .fecha { text-align: center; margin-bottom: 20px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background: #172535; color: white; padding: 10px; text-align: left; }
                td { padding: 8px; border-bottom: 1px solid #ddd; }
                .text-success { color: #059669; font-weight: bold; }
                .text-warning { color: #d97706; font-weight: bold; }
                .text-danger { color: #dc2626; font-weight: bold; }
                .resumen { margin-top: 20px; padding: 10px; background: #f3f4f6; }
                @media print { button { display: none; } }
            </style>
        </head>
        <body>
            <button onclick="window.print()">🖨️ Imprimir</button>
            <h1>Reporte de Asistencia Mensual</h1>
            <div class="fecha">' . $nombreMes . ' - ' . $anio . ' (' . $diasLaborales . ' días laborales)</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>DNI</th>
                        <th>Cargo</th>
                        <th>Turno</th>
                        <th>Asistió</th>
                        <th>Tardanzas</th>
                        <th>Faltas</th>
                        <th>Justificadas</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($datos as $row) {
            $clase = '';
            if ($row['porcentaje'] >= 90) $clase = 'text-success';
            elseif ($row['porcentaje'] >= 70) $clase = 'text-warning';
            else $clase = 'text-danger';
            echo '<tr>
                <td>' . $row['id_empleado'] . '</td>
                <td>' . htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) . '</td>
                <td>' . $row['dni'] . '</td>
                <td>' . htmlspecialchars($row['nombre_cargo']) . '</td>
                <td>' . htmlspecialchars($row['nombre_turno']) . '</td>
                <td class="text-success">' . $row['asistio'] . '</td>
                <td class="text-warning">' . $row['tardanzas'] . '</td>
                <td class="text-danger">' . $row['faltas'] . '</td>
                <td class="text-success">' . $row['justificadas'] . '</td>
                <td class="' . $clase . '">' . $row['porcentaje'] . '%</td>
            </tr>';
        }
        echo '</tbody>
            </table>
            <div class="resumen">
                <strong>Total días laborales del mes:</strong> ' . $diasLaborales . '
            </div>
        </body>
        </html>';
        exit;
    }
}
?>