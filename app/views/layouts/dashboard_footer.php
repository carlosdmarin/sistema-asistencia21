<?php
// app/views/layouts/dashboard_footer.php
?>
    </main>  <!-- Esto cierra el <main> que abrió el header -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarCerrarSesion(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Vas a cerrar tu sesión actual.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo BASE_URL; ?>/auth/logout';
                }
            });
        }

        function confirmarEliminar(id, nombre) {
            Swal.fire({
                title: '¿Eliminar empleado?',
                text: 'Estás por eliminar a ' + nombre,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo BASE_URL; ?>/empleado/eliminar/' + id;
                }
            });
        }
    </script>
<script>
    // Reporte de Asistencia por Fecha
function generarReporteAsistencia() {
    const fecha = document.getElementById('fecha_asistencia').value;
    
    if (!fecha) {
        alert('Seleccione una fecha');
        return;
    }
    
    const tbody = document.getElementById('resultado-tbody');
    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;"><i class="fa-solid fa-spinner fa-spin"></i> Cargando...<\/td><\/tr>';
    document.getElementById('resultado-container').style.display = 'block';
    document.getElementById('fecha-mostrada').innerHTML = '📅 Fecha: ' + formatFecha(fecha);
    
    fetch(`<?php echo BASE_URL; ?>/reporte/apiAsistenciaPorFecha?fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No hay registros para esta fecha<\/td><\/tr>';
                return;
            }
            
            let html = '';
            data.forEach(reg => {
                let claseEstado = '';
                if (reg.estado === 'Asistió') claseEstado = 'badge-success';
                else if (reg.estado === 'Tardanza') claseEstado = 'badge-warning';
                else if (reg.estado === 'Faltó') claseEstado = 'badge-danger';
                else claseEstado = 'badge-sinmarcar';
                
                html += `
                    <tr>
                        <td>${reg.id_empleado}<\/td>
                        <td>${reg.nombre} ${reg.apellido}<\/td>
                        <td>${reg.dni}<\/td>
                        <td>${reg.nombre_cargo}<\/td>
                        <td>${reg.hora_entrada || '—'}<\/td>
                        <td>${reg.hora_salida || '—'}<\/td>
                        <td><span class="${claseEstado}">${reg.estado}<\/span><\/td>
                    <\/tr>
                `;
            });
            tbody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Error al cargar los datos<\/td><\/tr>';
        });
}

function exportarExcelAsistencia() {
    const fecha = document.getElementById('fecha_asistencia').value;
    if (!fecha) {
        alert('Seleccione una fecha primero');
        return;
    }
    window.open(`<?php echo BASE_URL; ?>/reporte/exportarExcelAsistencia?fecha=${fecha}`, '_blank');
}

function exportarPDFAsistencia() {
    const fecha = document.getElementById('fecha_asistencia').value;
    if (!fecha) {
        alert('Seleccione una fecha primero');
        return;
    }
    window.open(`<?php echo BASE_URL; ?>/reporte/exportarPDFAsistencia?fecha=${fecha}`, '_blank');
}

function formatFecha(fecha) {
    const partes = fecha.split('-');
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}
</script>
<script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
</script>
    <script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
    
</body>
</html>