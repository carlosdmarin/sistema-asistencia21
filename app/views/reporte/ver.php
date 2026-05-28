<div class="reportes-container">
    <h1><i class="fa-solid fa-chart-line"></i> Reportes del Sistema</h1>

    <!-- Grid de Cards -->
    <div class="cards-grid">

        <!-- CARD 1: Asistencia por Fecha -->
        <div class="report-card">
            <div class="card-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <h3>Asistencia por Fecha</h3>
            <p>Consulta la asistencia de todos los empleados en una fecha específica</p>

            <div class="card-filtro">
                <label>Seleccionar Fecha</label>
                <input type="date" id="fecha_asistencia" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="card-buttons">
                <button class="btn-generar" onclick="generarReporteAsistencia()">
                    <i class="fa-solid fa-magnifying-glass"></i> Generar
                </button>
                <button class="btn-excel" onclick="exportarExcelAsistencia()">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </button>
                <button class="btn-pdf" onclick="exportarPDFAsistencia()">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>

        <!-- CARD 2: Reporte de Tardanzas -->
        <div class="report-card">
            <div class="card-icon">
                <i class="fa-solid fa-clock"></i>
            </div>
            <h3>Reporte de Tardanzas</h3>
            <p>Ranking de empleados que más llegan tarde. Detalle por día y minutos acumulados.</p>

            <div class="card-filtro">
                <label>Tipo de Reporte</label>
                <select id="tipo_reporte">
                    <option value="diario">📅 Diario</option>
                    <option value="semanal" selected>📆 Semanal</option>
                    <option value="mensual">📊 Mensual</option>
                </select>
            </div>

            <div class="card-filtro" id="filtro-fecha">
                <label>Seleccionar Fecha</label>
                <input type="date" id="fecha_tardanza" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="card-filtro" id="filtro-semana" style="display: none;">
                <label>Seleccionar Semana</label>
                <input type="week" id="semana_tardanza" value="<?php echo date('Y-\WW'); ?>">
            </div>

            <div class="card-filtro" id="filtro-mes" style="display: none;">
                <label>Seleccionar Mes</label>
                <input type="month" id="mes_tardanza" value="<?php echo date('Y-m'); ?>">
            </div>

            <div class="card-buttons">
                <button class="btn-generar" onclick="generarReporteTardanzas()">
                    <i class="fa-solid fa-magnifying-glass"></i> Generar
                </button>
                <button class="btn-excel" onclick="exportarExcelTardanzas()">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </button>
                <button class="btn-pdf" onclick="exportarPDFTardanzas()">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>
        <!-- CARD 3: Faltas (próximamente) -->
        <div class="report-card coming-soon">
            <div class="card-icon">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <h3>Reporte de Faltas</h3>
            <p>Empleados que faltaron en un período</p>
            <div class="badge-proximamente">Próximamente</div>
        </div>

        <!-- CARD 4: Resumen Mensual (próximamente) -->
        <div class="report-card coming-soon">
            <div class="card-icon">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
            <h3>Resumen Mensual</h3>
            <p>Estadísticas por empleado por mes</p>
            <div class="badge-proximamente">Próximamente</div>
        </div>
    </div>

    <!-- Tabla de Resultados (se muestra después de generar) -->
    <div id="resultado-container" style="display: none;">
        <div class="resultado-header">
            <h3><i class="fa-solid fa-table"></i> Resultados</h3>
            <span id="fecha-mostrada"></span>
        </div>
        <div class="table-responsive">
            <table class="table-reportes" id="tabla-resultados">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>DNI</th>
                        <th>Cargo</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="resultado-tbody">
                    <tr>
                        <td colspan="7" style="text-align: center;">Seleccione una fecha y presione Generar</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
