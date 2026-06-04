<div class="empleados-container">
    <div class="empleados-header">
        <h1><i class="fa-solid fa-clock"></i> Lista de Turnos</h1>  <!-- ← CAMBIADO -->
        
        <a href="<?php echo BASE_URL; ?>/turno/registrar" class="btn-agregar">
            <i class="fas fa-plus"></i> Nuevo Turno
        </a>
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
    <?php if (empty($turnos)): ?>
        <div class="empty-state">
            <i class="fas fa-clock"></i>  <!-- ← CAMBIADO -->
            <p>No hay turnos registrados.</p>  <!-- ← CAMBIADO -->
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="table-responsive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Turno</th>
                        <th>Hora Inicio</th>
                        <th>Hora Salida</th>
                        <th>Tolerancia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turnos as $turno): ?>
                    <tr>
                        <td data-label="ID"><?php echo $turno['id_turno']; ?></td>
                        <td data-label="Turno">
                            <strong><?php echo htmlspecialchars($turno['nombre_turno']); ?></strong>
                        </td>
                        <td data-label="Hora Inicio"><?php echo date('h:i A', strtotime($turno['hora_inicio'])); ?></td>
                        <td data-label="Hora Salida"><?php echo date('h:i A', strtotime($turno['hora_salida'])); ?></td>
                        <td data-label="Tolerancia"><?php echo $turno['tolerancia_minutos']; ?> min</td>
                        <td data-label="Acciones" class="acciones">
                            <a href="<?php echo BASE_URL; ?>/turno/editar/<?php echo $turno['id_turno']; ?>" 
                               class="btn-accion btn-editar" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmarEliminarTurno(<?php echo $turno['id_turno']; ?>, '<?php echo htmlspecialchars($turno['nombre_turno']); ?>')" 
                                    class="btn-accion btn-eliminar" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <span>Total: <strong><?php echo count($turnos); ?></strong> turno(s)</span>  <!-- ← CAMBIADO -->
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarEliminarTurno(id, nombre) {
    Swal.fire({
        title: '¿Eliminar turno?',
        text: 'Estás por eliminar el turno: ' + nombre,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?php echo BASE_URL; ?>/turno/eliminar/' + id;
        }
    });
}
</script>