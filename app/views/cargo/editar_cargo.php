<div class="empleados-container">
    <div class="empleados-header">
        <a href="<?php echo BASE_URL; ?>/cargo/ver" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1><i class="fas fa-edit"></i> Editar Cargo</h1>
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

    <div class="form-wrapper">
        <form method="POST" action="<?php echo BASE_URL; ?>/cargo/actualizar">
            <input type="hidden" name="id_cargo" value="<?php echo $cargo['id_cargo']; ?>">
            
            <div class="form-group">
                <label><i class="fas fa-briefcase"></i> Nombre del Cargo:</label>
                <input type="text" name="nombre" required value="<?php echo htmlspecialchars($cargo['nombre_cargo']); ?>">
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Actualizar Cargo
            </button>
        </form>
    </div>
</div>