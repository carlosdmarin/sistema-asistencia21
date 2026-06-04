<?php
class HistorialController extends Controller 
{
    private $pdo;
    public $Historial;
    
    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }
    
    public function ver(): void {
    
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        
        // Cargamos el modelo 
        $this->loadModel('Historial');
        $asistencias = $this->Historial->obtenerPorFecha($fecha);
        $stats = $this->Historial->obtenerEstadisticasPorFecha($fecha);
        
        
        $this->view('historial/ver', [
            'asistencias' => $asistencias,
            'stats' => $stats,
            'fecha' => $fecha
        ], 'dashboard');
    }
}