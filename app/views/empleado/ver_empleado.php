<div class="empleados-container">
    <div class="empleados-header">
        <h1><i class="fas fa-users"></i> Lista de Empleados</h1>
        
        <a href="<?php echo BASE_URL; ?>/empleado/registrar" class="btn-agregar">
            <i class="fas fa-plus"></i> Nuevo Empleado
        </a>
    </div>
    
    <!-- BUSCADOR -->
    <div class="buscador-container">
        <div class="buscador-input">
            <i class="fas fa-search"></i>
            <input type="text" id="buscadorEmpleados" 
                   placeholder="Buscar por nombre, DNI, teléfono o cargo..." 
                   value="<?php echo htmlspecialchars($busqueda ?? ''); ?>">
            <button id="btnLimpiar" class="btn-limpiar" style="display: <?php echo !empty($busqueda) ? 'flex' : 'none'; ?>">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
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

    <!-- RESULTADOS -->
    <div id="resultadosBusqueda">
        <?php if (empty($empleados)): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <p>No se encontraron empleados.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="table-responsive-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Cargo</th>
                            <th>Turno</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados as $emp): ?>
                        <tr>
                            <td data-label="ID"><?php echo $emp['id_empleado']; ?></td>
                            <td data-label="Nombre Completo">
                                <div class="empleado-info">
                                    <div class="avatar-inicial">
                                        <?php echo strtoupper(substr($emp['nombre'], 0, 1) . substr($emp['apellido'], 0, 1)); ?>
                                    </div>
                                    <span><?php echo htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']); ?></span>
                                </div>
                            </td>
                            <td data-label="DNI"><?php echo $emp['dni']; ?></td>
                            <td data-label="Teléfono"><?php echo $emp['telefono'] ?: '—'; ?></td>
                            <td data-label="Cargo">
                                <span class="badge badge-cargo"><?php echo htmlspecialchars($emp['nombre_cargo']); ?></span>
                            </td>
                            <td data-label="Turno">
                                <span class="badge badge-turno"><?php echo htmlspecialchars($emp['nombre_turno']); ?></span>
                            </td>
                            <td data-label="Registro"><?php echo date('d/m/Y', strtotime($emp['fecha_registro'])); ?></td>
                            <td data-label="Acciones">
                                <div class="acciones">
                                    <a href="<?php echo BASE_URL; ?>/empleado/editar/<?php echo $emp['id_empleado']; ?>" 
                                       class="btn-accion btn-editar" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmarEliminar(<?php echo $emp['id_empleado']; ?>, '<?php echo htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']); ?>')" 
                                            class="btn-accion btn-eliminar" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <span>Total: <strong id="totalEmpleados"><?php echo count($empleados); ?></strong> empleado(s)</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- SCRIPT DEL BUSCADOR -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorEmpleados');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const resultados = document.getElementById('resultadosBusqueda');
    let timeout;
    
    // Buscar mientras escribe
    buscador.addEventListener('input', function() {
        clearTimeout(timeout);
        const valor = this.value.trim();
        
        // Mostrar/ocultar botón limpiar
        btnLimpiar.style.display = valor ? 'flex' : 'none';
        
        // Esperar 300ms después de escribir
        timeout = setTimeout(() => {
            buscarEmpleados(valor);
        }, 300);
    });
    
    // Limpiar búsqueda
    btnLimpiar.addEventListener('click', function() {
        buscador.value = '';
        btnLimpiar.style.display = 'none';
        buscarEmpleados('');
        buscador.focus();
    });
    
    function buscarEmpleados(busqueda) {
        // Mostrar loader
        resultados.innerHTML = '<div class="loader-busqueda"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
        
        fetch('<?php echo BASE_URL; ?>/empleado/ver?ajax=1&buscar=' + encodeURIComponent(busqueda))
            .then(response => response.json())
            .then(empleados => {
                if (empleados.length === 0) {
                    resultados.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <p>No se encontraron empleados.</p>
                        </div>`;
                } else {
                    renderizarTabla(empleados);
                }
            })
            .catch(error => {
                resultados.innerHTML = '<p style="color:red;">Error al buscar</p>';
            });
    }
    
    function renderizarTabla(empleados) {
        let html = `
            <div class="table-wrapper">
                <table class="table-responsive-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Cargo</th>
                            <th>Turno</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        empleados.forEach(emp => {
            html += `
                <tr>
                    <td data-label="ID">${emp.id_empleado}</td>
                    <td data-label="Nombre Completo">
                        <div class="empleado-info">
                            <div class="avatar-inicial">${emp.nombre.charAt(0)}${emp.apellido.charAt(0)}</div>
                            <span>${emp.nombre} ${emp.apellido}</span>
                        </div>
                    </td>
                    <td data-label="DNI">${emp.dni}</td>
                    <td data-label="Teléfono">${emp.telefono || '—'}</td>
                    <td data-label="Cargo"><span class="badge badge-cargo">${emp.nombre_cargo}</span></td>
                    <td data-label="Turno"><span class="badge badge-turno">${emp.nombre_turno}</span></td>
                    <td data-label="Registro">${formatearFecha(emp.fecha_registro)}</td>
                    <td data-label="Acciones">
                        <div class="acciones">
                            <a href="<?php echo BASE_URL; ?>/empleado/editar/${emp.id_empleado}" class="btn-accion btn-editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmarEliminar(${emp.id_empleado}, '${emp.nombre} ${emp.apellido}')" class="btn-accion btn-eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
        });
        
        html += '</tbody></table></div>';
        html += `<div class="table-footer"><span>Total: <strong>${empleados.length}</strong> empleado(s)</span></div>`;
        
        resultados.innerHTML = html;
    }
    
    function formatearFecha(fecha) {
        const d = new Date(fecha);
        return d.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }
});
</script>