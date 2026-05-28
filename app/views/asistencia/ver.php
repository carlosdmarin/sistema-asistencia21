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
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    foreach ($empleados as $emp): 

                    ?>
                    <tr>
                        <td data-label="ID"><?php echo $emp['id_empleado']; ?></td>
                        <td data-label="Empleado">
                            <div class="empleado-info">
                                <div class="avatar-inicial">
                                    <?php echo strtoupper(substr($emp['nombre'], 0, 1) . substr($emp['apellido'], 0, 1)); ?>
                                </div>
                                <span><?php echo htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']); ?></span>
                            </div>
                        </td>
                        <td data-label="DNI"><?php echo $emp['dni']; ?></td>
                        <td data-label="Cargo">
                            <span class="badge badge-cargo"><?php echo htmlspecialchars($emp['nombre_cargo']); ?></span>
                        </td>
                        <td data-label="Turno">
                            <span class="badge badge-turno"><?php echo htmlspecialchars($emp['nombre_turno']); ?></span>
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<script>
// Función para actualizar SOLO las filas de la tabla
function actualizarTabla() {
    fetch('<?php echo BASE_URL; ?>/asistencia/obtenerDatos')
        .then(response => response.json())
        .then(empleados => {
            const tbody = document.querySelector('.table-responsive-table tbody');
            if (tbody) {
                let nuevoTbody = '';
                
                empleados.forEach((emp, index) => {
                    let estadoHtml = '';
                    if (emp.estado === 'asistio') {
                        estadoHtml = '<span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Asistió</span>';
                    } else if (emp.estado === 'tardanza') {
                        estadoHtml = '<span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Tardanza</span>';
                    } else if (emp.estado === 'falto') {
                        estadoHtml = '<span class="badge badge-danger"> <i class="fa-solid fa-circle-xmark"></i> Faltó</span>';
                    } else {
                        estadoHtml = '<span class="badge badge-sinmarcar">Sin marcar</span>';
                    }
                    
                    nuevoTbody += `
                        <tr>
                            <td data-label="ID">${emp.id_empleado}</td>
                            <td data-label="Empleado">
                                <div class="empleado-info">
                                    <div class="avatar-inicial">${emp.nombre.charAt(0)}${emp.apellido.charAt(0)}</div>
                                    <span>${emp.nombre} ${emp.apellido}</span>
                                </div>
                            </td>
                            <td data-label="DNI">${emp.dni}</td>
                            <td data-label="Cargo"><span class="badge badge-cargo">${emp.nombre_cargo}</span></td>
                            <td data-label="Turno"><span class="badge badge-turno">${emp.nombre_turno}</span></td>
                            <td data-label="Entrada">${emp.hora_entrada || '—'}</td>
                            <td data-label="Salida">${emp.hora_salida || '—'}</td>
                            <td data-label="Estado">${estadoHtml}</td>
                        </tr>
                    `;
                });
                
                tbody.innerHTML = nuevoTbody;
            }
        })
        .catch(error => console.log('Error:', error));
}

// Actualizar cada 30 segundos
setInterval(actualizarTabla, 30000);

// Actualizar cuando el usuario vuelve a la pestaña
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        actualizarTabla();
    }
});
</script>