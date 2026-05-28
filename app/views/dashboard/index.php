<?php
// app/views/dashboard/index.php
echo "<!-- EL DASHBOARD INDEX SE ESTA CARGANDO -->";

?>
<!-- Mensaje de INICIO DE SESION EXITOSO -->
<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'error'; ?>">
        <i class="fas fa-<?php echo $_SESSION['tipo'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo']);
        ?>
    </div>
<?php endif; ?>

<div class="dashboard-container">
    <!-- Tarjetas de estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalEmpleados ?? 128; ?></h3>
                <p>Total de empleados</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $asistenciasHoy ?? 78; ?></h3>
                <p>Asistencias Hoy</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $ausentesHoy ?? 15; ?></h3>
                <p>Ausentes Hoy</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $tardanzasHoy ?? 12; ?></h3>
                <p>Tardanzas Hoy</p>
            </div>
        </div>
    </div>

    <!-- Gráfico de Asistencias por semana y Porcentaje -->
    <div class="charts-row">
        <div class="chart-box">
            <h3><i class="fas fa-chart-bar"></i> Asistencias por semana</h3>
            <div class="week-bars">
                <?php
                $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                $asistenciasSemana = $asistenciasPorSemana ?? [0, 0, 0, 0, 0, 0];
                $maxAsistencia = max($asistenciasSemana);
                if ($maxAsistencia == 0) {
                    $maxAsistencia = 1;
                }
                ?>
                <?php foreach ($diasSemana as $index => $dia): ?>
                    <div class="bar-item">
                        <div class="bar"
                            style="height: <?php echo ($asistenciasSemana[$index] / $maxAsistencia) * 180; ?>px;"></div>
                        <span><?php echo $dia; ?></span>
                        <small><?php echo $asistenciasSemana[$index]; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="percentage-box">
            <h3><i class="fas fa-chart-pie"></i> Porcentaje de Asistencias Hoy</h3>
            <div class="circle-progress">
                <svg viewBox="0 0 100 100">
                    <circle class="bg" cx="50" cy="50" r="45"></circle>
                    <circle class="progress" cx="50" cy="50" r="45"
                        style="stroke-dasharray: 283, 283; 
                                   stroke-dashoffset: <?php echo 283 - (283 * (($porcentajeAsistencia ?? 76) / 100)); ?>;">
                    </circle>
                </svg>
                <div class="percentage-text">
                    <span class="number"><?php echo $porcentajeAsistencia ?? 76; ?>%</span>
                    <span class="label">Asistencias</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de últimos registros -->
    <div class="recent-table">
        <div class="table-header">
            <h3><i class="fas fa-clock"></i> Últimos registros</h3>
            <a href="<?php echo BASE_URL; ?>/asistencia/ver" class="ver-todos">Ver todos <i
                    class="fas fa-arrow-right"></i></a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ultimosRegistros)): ?>
                        <?php foreach ($ultimosRegistros as $registro): ?>
                            <tr>
                                <td>
                                    <div class="empleado-info">
                                        <i class="fas fa-user-circle"></i>
                                        <?php echo htmlspecialchars($registro['empleado_nombre'] ?? $registro['empleado']); ?>
                                    </div>


                                <td><?php echo date('d M Y', strtotime($registro['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($registro['hora']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($registro['estado']); ?>">
                                        <?php echo htmlspecialchars($registro['estado']); ?>
                                    </span>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay registros recientes</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>