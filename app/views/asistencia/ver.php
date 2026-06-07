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
<script>


// Función para obtener fecha actual
function fechaActual() {
    const hoy = new Date();
    const año = hoy.getFullYear();
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const dia = String(hoy.getDate()).padStart(2, '0');
    return `${año}-${mes}-${dia}`;
}

// FUNCIÓN PRINCIPAL: Justificar falta
function justificarFalta(idEmpleado, fecha, boton) {
    Swal.fire({
        title: 'Justificar ausencia',
        input: 'textarea',
        inputPlaceholder: 'Escribe el motivo (ej: Descanso médico, permiso personal, salió temprano...)',
        showCancelButton: true,
        confirmButtonText: 'Guardar justificación',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debe ingresar un motivo';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(BASE_URL + '/asistencia/justificar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_empleado=${idEmpleado}&fecha=${fecha}&motivo=${encodeURIComponent(result.value)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok) {
                    if (boton) {
                        boton.innerHTML = '<i class="fas fa-eye"></i> Ver justificación';
                        boton.classList.remove('btn-justificar');
                        boton.classList.add('btn-ver-justificacion');
                        boton.setAttribute('onclick', `verJustificacion(${idEmpleado}, '${fecha}')`);
                    }
                    Swal.fire('¡Justificado!', data.mensaje, 'success');
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error de conexión', 'error');
            });
        }
    });
}

// Función para ver justificación
function verJustificacion(idEmpleado, fecha) {
    fetch(BASE_URL + `/asistencia/obtenerJustificacion?id_empleado=${idEmpleado}&fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            if (data.justificada) {
                Swal.fire({
                    title: 'Justificación',
                    html: `<p><strong>Motivo:</strong> ${data.motivo}</p>`,
                    icon: 'info',
                    confirmButtonText: 'Cerrar'
                });
            } else {
                Swal.fire('Sin justificación', 'No hay justificación registrada', 'info');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'No se pudo cargar la justificación', 'error');
        });
}

// Función para actualizar tabla
function actualizarTabla() {
    fetch(BASE_URL + '/asistencia/obtenerDatos')
        .then(response => response.json())
        .then(empleados => {
            const tbody = document.querySelector('.table-responsive-table tbody');
            if (tbody) {
                let nuevoTbody = '';
                empleados.forEach((emp) => {
                    let estadoHtml = '';
                    if (emp.estado === 'asistio') {
                        estadoHtml = '<span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Asistió</span>';
                    } else if (emp.estado === 'tardanza') {
                        estadoHtml = '<span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i>  Tardanza</span>';
                    } else if (emp.estado === 'falto') {
                        estadoHtml = '<span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i>  Faltó</span>';
                    } else {
                        estadoHtml = '<span class="badge badge-sinmarcar">⬜ Sin marcar</span>';
                    }
                    
                    let justificacionHtml = '';
                    if (emp.justificado == 1) {
                        justificacionHtml = `<button class="btn-ver-justificacion" onclick="verJustificacion(${emp.id_empleado}, '${fechaActual()}')">
                            <i class="fas fa-eye"></i> Ver justificación
                        </button>`;
                    } else {
                        justificacionHtml = `<button class="btn-justificar" onclick="justificarFalta(${emp.id_empleado}, '${fechaActual()}', this)">
                            <i class="fas fa-file-alt"></i> Justificar
                        </button>`;
                    }
                    
                    nuevoTbody += `
                        <tr>
                            <td>${emp.id_empleado}</td>
                            <td>${emp.nombre} ${emp.apellido}</td>
                            <td>${emp.dni}</td>
                            <td>${emp.nombre_cargo}</td>
                            <td>${emp.nombre_turno}</td>
                            <td>${emp.hora_entrada || '—'}</td>
                            <td>${emp.hora_salida || '—'}</td>
                            <td>${estadoHtml}</td>
                            <td>${justificacionHtml}</td>
                        </tr>
                    `;
                });
                tbody.innerHTML = nuevoTbody;
            }
        })
        .catch(error => console.log('Error:', error));
}

// Iniciar actualización automática
let intervalo;
function iniciarActualizacion() {
    actualizarTabla();
    if (intervalo) clearInterval(intervalo);
    intervalo = setInterval(actualizarTabla, 30000);
}
document.addEventListener('DOMContentLoaded', iniciarActualizacion);
</script>

