<div class="empleados-container">
    <div class="empleados-header">
        <h1><i class="fas fa-history"></i> Historial de Asistencias</h1>
    </div>

    <!-- FILTRO POR DÍA -->
    <div class="filtro-fechas">
        <form method="GET" action="<?php echo BASE_URL; ?>/historial/ver" class="filtro-form">
            <div class="filtro-grupo">
                <label><i class="fas fa-calendar"></i> Seleccionar fecha:</label>
                <input type="date" name="fecha" value="<?php echo $fecha; ?>" onchange="this.form.submit()">
            </div>
            <a href="<?php echo BASE_URL; ?>/historial/ver" class="btn-limpiar-filtro">
                <i class="fas fa-calendar-day"></i> Hoy
            </a>
        </form>
    </div>

    <?php if (empty($asistencias)): ?>
        <div class="empty-state">
            <i class="fas fa-calendar-xmark"></i>
            <p>No hay registros de asistencia para el <strong><?php echo date('d/m/Y', strtotime($fecha)); ?></strong></p>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="table-responsive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>DNI</th>
                        <th>Cargo</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                        <th>Justificacion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asistencias as $index => $a): ?>
                        <tr>
                            <td data-label="ID"><?php echo $index + 1; ?></td>
                            <td data-label="Empleado">
                                <div class="empleado-info">
                                    <div class="avatar-inicial">
                                        <?php echo strtoupper(substr($a['nombre'], 0, 1) . substr($a['apellido'], 0, 1)); ?>
                                    </div>
                                    <span><?php echo htmlspecialchars($a['nombre'] . ' ' . $a['apellido']); ?></span>
                                </div>
                            </td>
                            <td data-label="DNI"><?php echo $a['dni']; ?></td>
                            <td data-label="Cargo">
                                <span class="badge badge-cargo"><?php echo htmlspecialchars($a['nombre_cargo']); ?></span>
                            </td>
                            <td data-label="Entrada"><?php echo $a['hora_entrada'] ?? '—'; ?></td>
                            <td data-label="Salida"><?php echo $a['hora_salida'] ?? '—'; ?></td>
                            <td data-label="Estado">
                                <?php if ($a['estado'] === 'asistio'): ?>
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Asistió</span>
                                <?php elseif ($a['estado'] === 'tardanza'): ?>
                                    <span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Tardanza</span>
                                <?php elseif ($a['estado'] === 'falto'): ?>
                                    <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Faltó</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Justificacion">
                                <?php if ($a['justificado'] == 1): ?>
                                    <button class="btn-ver-justificacion"
                                        onclick="verJustificacionHistorial(<?php echo $a['id_empleado']; ?>, '<?php echo $a['fecha']; ?>')">
                                        <i class="fas fa-eye"></i> Ver justificación
                                    </button>
                                <?php else: ?>
                                    <span class="sin-accion">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Función para ver justificación en historial
function verJustificacionHistorial(idEmpleado, fecha) {
    console.log('Función llamada con ID:', idEmpleado, 'Fecha:', fecha);
    
    fetch('<?php echo BASE_URL; ?>/asistencia/obtenerJustificacion?id_empleado=' + idEmpleado + '&fecha=' + fecha)
        .then(response => response.json())
        .then(data => {
            if (data.justificada) {
                Swal.fire({
                    title: 'Justificación',
                    html: '<p><strong>Motivo:</strong> ' + data.motivo + '</p>',
                    icon: 'info',
                    confirmButtonText: 'Cerrar'
                });
            } else {
                Swal.fire('Sin justificación', 'No hay justificación registrada', 'info');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo cargar la justificación', 'error');
        });
}
</script>