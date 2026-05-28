<div class="empleados-container">
    <div class="empleados-header">
        <a href="<?php echo BASE_URL; ?>/empleado/ver" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1><i class="fas fa-user-plus"></i> Registrar Usuario</h1>
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
        <form method="POST" action="<?php echo BASE_URL; ?>/usuario/guardar">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre:</label>
                    <input type="text" name="usuario" required placeholder="Ingrese el nombre">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> Clave:</label>
                    <input type="text" name="clave" required placeholder="ingresa una clave">
                </div>
            </div>
        
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Registrar Usuario
            </button>
        </form>
    </div>
</div>