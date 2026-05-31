<div class="empleados-container">
    <div class="empleados-header">
        <h1><i class="fas fa-users"></i> Lista de Usuarios del Sistema</h1>
        <a href="<?php echo BASE_URL; ?>/usuario/registrar" class="btn-agregar">
            <i class="fas fa-plus"></i> Nuevo Usuario
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
    
    <div class="table-wrapper">
        <table class="table-responsive-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Clave</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No hay usuarios registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td data-label="ID"><?php echo $user['id_usuario']; ?></td>
                            <td data-label="Usuario">
                                <div class="empleado-info">
                                    <div class="avatar-inicial">
                                        <?php echo strtoupper(substr($user['nombre'], 0, 1)); ?>
                                    </div>
                                    <span><?php echo htmlspecialchars($user['nombre']); ?></span>
                                </div>
                            </td>
                            <td data-label="ID"><?php echo $user['clave']; ?></td>
                            <td data-label="Acciones">
                                <div class="acciones">
                                    <button onclick="confirmarEliminarUsuario(<?php echo $user['id_usuario']; ?>, '<?php echo htmlspecialchars($user['nombre']); ?>')" 
                                            class="btn-accion btn-eliminar" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="table-footer">
        <span>Total: <strong><?php echo count($usuarios); ?></strong> usuario(s)</span>
    </div>
</div>

<script>
function confirmarEliminarUsuario(id, nombre) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: 'Estás por eliminar al usuario: ' + nombre,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?php echo BASE_URL; ?>/usuario/eliminar/' + id;
        }
    });
}
</script>