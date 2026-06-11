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

        <!-- CARD: Ranking de Puntualidad -->
        <div class="report-card">
            <div class="card-icon">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <h3>Ranking de Puntualidad</h3>
            <p>Empleados más puntuales (considera tolerancia por turno).</p>
            <div class="card-filtro">
                <label>Fecha Inicio</label>
                <input type="date" id="ranking_fecha_inicio" value="<?php echo date('Y-m-01'); ?>">
            </div>
            <div class="card-filtro">
                <label>Fecha Fin</label>
                <input type="date" id="ranking_fecha_fin" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="card-buttons">
                <button class="btn-generar" onclick="generarRankingPuntualidad()">
                    <i class="fa-solid fa-chart-line"></i> Generar
                </button>
                <button class="btn-excel" onclick="exportarExcelRankingPuntualidad()">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </button>
                <button class="btn-pdf" onclick="exportarPDFRankingPuntualidad()">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>

        <!-- CARD 4: Resumen Mensual -->
        <div class="report-card">
            <div class="card-icon">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
            <h3>Resumen Mensual</h3>
            <p>Estadísticas por empleado por mes (asistencias, tardanzas, faltas)</p>

            <div class="card-filtro">
                <label>Seleccionar Mes</label>
                <select id="mes_resumen">
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6" selected>Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="card-filtro">
                <label>Seleccionar Año</label>
                <select id="anio_resumen">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026" selected>2026</option>
                </select>
            </div>

            <div class="card-buttons">
                <button class="btn-generar" onclick="generarResumenMensual()">
                    <i class="fa-solid fa-chart-simple"></i> Generar
                </button>
                <button class="btn-excel" onclick="exportarExcelResumenMensual()">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </button>
                <button class="btn-pdf" onclick="exportarPDFResumenMensual()">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            </div>
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