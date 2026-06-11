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
                        estadoHtml = '<span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Tardanza</span>';
                    } else if (emp.estado === 'falto') {
                        estadoHtml = '<span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Faltó</span>';
                    } else {
                        estadoHtml = '<span class="badge badge-sinmarcar">⬜ Sin marcar</span>';
                    }

                    let justificacionHtml = '';
                    if (emp.justificado == 1) {
                        justificacionHtml = `<button class="btn-ver-justificacion" onclick="verJustificacion(${emp.id_empleado}, '${FECHA_SERVIDOR}')">
                            <i class="fas fa-eye"></i> Ver justificación
                        </button>`;
                    } else {
                        justificacionHtml = `<button class="btn-justificar" onclick="justificarFalta(${emp.id_empleado}, '${FECHA_SERVIDOR}', this)">
                            <i class="fas fa-file-alt"></i> Justificar
                        </button>`;
                    }

                    nuevoTbody += `
                        <tr>
                            <td data-label="ID">${emp.id_empleado}</td>
                            <td data-label="Empleado">
                                <div class="empleado-info">
                                    <div class="avatar-inicial">${emp.nombre.charAt(0)}${emp.apellido.charAt(0)}</div>
                                    <span>${emp.nombre} ${emp.apellido}</span>
                                </div>
                              </div>
                            </td>
                            <td data-label="DNI">${emp.dni}</td>
                            <td data-label="Cargo"><span class="badge badge-cargo">${emp.nombre_cargo}</span></td>
                            <td data-label="Turno"><span class="badge badge-turno">${emp.nombre_turno}</span></td>
                            <td data-label="Entrada">${emp.hora_entrada || '—'}</td>
                            <td data-label="Salida">${emp.hora_salida || '—'}</td>
                            <td data-label="Estado">${estadoHtml}</td>
                            <td data-label="Justificacion">${justificacionHtml}</td>
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