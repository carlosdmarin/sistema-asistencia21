<div class="empleados-container">
    <div class="empleados-header">
        <h1><i class="fa-solid fa-briefcase"></i> Lista de Cargos</h1>
        
        <a href="<?php echo BASE_URL; ?>/cargo/registrar" class="btn-agregar">
            <i class="fas fa-plus"></i> Nuevo Cargo
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
    <?php if (empty($cargos)): ?>
        <div class="empty-state">
            <i class="fas fa-briefcase"></i>
            <p>No hay cargos registrados.</p>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="table-responsive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del cargo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cargos as $cargo): ?>
                    <tr>
                        <td data-label="ID"><?php echo $cargo['id_cargo']; ?></td>
                        <td data-label="Cargo"><?php echo htmlspecialchars($cargo['nombre_cargo']); ?></td>
                        <td data-label="Acciones">
                            <div class="acciones">
                                <a href="<?php echo BASE_URL; ?>/cargo/editar/<?php echo $cargo['id_cargo']; ?>" 
                                   class="btn-accion btn-editar" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmarEliminarCargo(<?php echo $cargo['id_cargo']; ?>, '<?php echo htmlspecialchars($cargo['nombre_cargo']); ?>')" 
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
            <span>Total: <strong><?php echo count($cargos); ?></strong> cargo(s)</span>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmarEliminarCargo(id, nombre) {
    Swal.fire({
        title: '¿Eliminar cargo?',
        text: 'Estás por eliminar el cargo: ' + nombre,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?php echo BASE_URL; ?>/cargo/eliminar/' + id;
        }
    });
}
</script>