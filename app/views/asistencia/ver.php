<div class="empleados-container">
    <div class="empleados-header">
        <h1><i class="fas fa-clipboard-check"></i> Asistencia de Hoy</h1>
        <span class="fecha-hoy"><?php echo date('d/m/Y'); ?></span>
    </div>

    <?php if (empty($empleados)): ?>
        <div class="empty-state">
            <i class="fas fa-user-slash"></i>
            <p>No hay empleados registrados.</p>
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
                        <th>Turno</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                        <th>Justificacion</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($empleados as $emp): ?>
        <tr>
            <td data-label="ID"><?php echo $emp['id_empleado']; ?></td>
            <td data-label="Empleado">
                <div class="empleado-info">
                    <div class="avatar-inicial">
                        <?php echo strtoupper(substr($emp['nombre'], 0, 1) . substr($emp['apellido'], 0, 1)); ?>
                    </div>
                    <span><?php echo htmlspecialchars(str_replace('\\', '', $emp['nombre'] . ' ' . $emp['apellido'])); ?></span>
                </div>
            </td>
            <td data-label="DNI"><?php echo $emp['dni']; ?></td>
            <td data-label="Cargo">
            <span class="badge badge-cargo"><?php echo htmlspecialchars($emp['nombre_cargo'], ENT_NOQUOTES, 'UTF-8'); ?></span>
            </td>
            <td data-label="Turno">
                <span class="badge badge-turno"><?php echo htmlspecialchars($emp['nombre_turno'], ENT_QUOTES, 'UTF-8'); ?></span>
            </td>
            <td data-label="Entrada"><?php echo $emp['hora_entrada'] ?? '—'; ?></td>
            <td data-label="Salida"><?php echo $emp['hora_salida'] ?? '—'; ?></td>
            <td data-label="Estado">
                <?php if ($emp['estado'] === 'asistio'): ?>
                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Asistió</span>
                <?php elseif ($emp['estado'] === 'tardanza'): ?>
                    <span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Tardanza</span>
                <?php elseif ($emp['estado'] === 'falto'): ?>
                    <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Faltó</span>
                <?php else: ?>
                    <span class="badge badge-sinmarcar">⬜ Sin marcar</span>
                <?php endif; ?>
            </td>
            <td data-label="Justificacion">
                <?php if ($emp['justificado'] == 1): ?>
                    <button class="btn-ver-justificacion" onclick="verJustificacion(<?php echo $emp['id_empleado']; ?>, '<?php echo date('Y-m-d'); ?>')">
                        <i class="fas fa-eye"></i> Ver justificación
                    </button>
                <?php else: ?>
                    <button class="btn-justificar" onclick="justificarFalta(<?php echo $emp['id_empleado']; ?>, '<?php echo date('Y-m-d'); ?>', this)">
                        <i class="fas fa-file-alt"></i> Justificar
                    </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<script src="<?php echo BASE_URL; ?>/public/js/asistencia.js"></script>
