// =============================================
// REPORTE MENSUAL (RESUMEN)
// =============================================

function generarResumenMensual() {
    const mes = document.getElementById('mes_resumen').value;
    const anio = document.getElementById('anio_resumen').value;
    
    const tbody = document.getElementById('resultado-tbody');
    tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;"><i class="fa-solid fa-spinner fa-spin"></i> Cargando...<\/td><\/tr>';
    document.getElementById('resultado-container').style.display = 'block';
    
    fetch(`${BASE_URL}/reporte/resumenMensual?mes=${mes}&anio=${anio}`)
        .then(response => response.json())
        .then(data => {
            actualizarTablaResumen(data);
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">Error al cargar los datos<\/td><\/tr>';
        });
}

function actualizarTablaResumen(data) {
    const resultados = data.datos;
    const tbody = document.getElementById('resultado-tbody');
    const fechaMostrada = document.getElementById('fecha-mostrada');
    
    fechaMostrada.innerHTML = `${data.nombre_mes} - ${data.anio} (${data.dias_laborales} días laborales)`;
    
    // Cambiar cabeceras de la tabla para reporte mensual
    const thead = document.querySelector('#tabla-resultados thead');
    thead.innerHTML = `
        <tr>
            <th>ID</th>
            <th>Empleado</th>
            <th>DNI</th>
            <th>Cargo</th>
            <th>Turno</th>
            <th>Teléfono</th>
            <th>Asistió</th>
            <th>Tardanzas</th>
            <th>Faltas</th>
            <th>Justificadas</th>
            <th>% Asistencia</th>
        </tr>
    `;
    
    if (resultados.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" style="text-align: center;">No hay datos para este período<\/td><\/tr>';
        return;
    }
    
    let html = '';
    resultados.forEach(emp => {
        let clasePorcentaje = '';
        if (emp.porcentaje >= 90) clasePorcentaje = 'text-success';
        else if (emp.porcentaje >= 70) clasePorcentaje = 'text-warning';
        else clasePorcentaje = 'text-danger';
        
        html += `
            <tr>
                <td>${emp.id_empleado}<\/td>
                <td>${emp.nombre} ${emp.apellido}<\/td>
                <td>${emp.dni}<\/td>
                <td>${emp.nombre_cargo}<\/td>
                <td>${emp.nombre_turno}<\/td>
                <td>${emp.telefono || '—'}<\/td>
                <td class="text-success">${emp.asistio}<\/td>
                <td class="text-warning">${emp.tardanzas}<\/td>
                <td class="text-danger">${emp.faltas}<\/td>
                <td class="text-success">${emp.justificadas}</td>
                <td class="${clasePorcentaje}"><strong>${emp.porcentaje}%<\/strong><\/td>
             \\
        `;
    });
    
    tbody.innerHTML = html;
}

function exportarExcelResumenMensual() {
    const mes = document.getElementById('mes_resumen').value;
    const anio = document.getElementById('anio_resumen').value;
    window.open(`${BASE_URL}/reporte/exportarExcelResumenMensual?mes=${mes}&anio=${anio}`, '_blank');
}

function exportarPDFResumenMensual() {
    const mes = document.getElementById('mes_resumen').value;
    const anio = document.getElementById('anio_resumen').value;
    window.open(`${BASE_URL}/reporte/exportarPDFResumenMensual?mes=${mes}&anio=${anio}`, '_blank');
}