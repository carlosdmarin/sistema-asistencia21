<div class="empleados-container">
    <div class="empleados-header">
        <a href="<?php echo BASE_URL; ?>/empleado/ver" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1><i class="fas fa-user-plus"></i> Registrar Empleado</h1>
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
        <form method="POST" action="<?php echo BASE_URL; ?>/empleado/guardar">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre:</label>
                    <input type="text" name="nombre" required placeholder="Ingrese el nombre">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Apellido:</label>
                    <input type="text" name="apellido" required placeholder="Ingrese el apellido">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> DNI:</label>
                    <input type="text" name="dni" required placeholder="8 dígitos" maxlength="8" pattern="[0-9]{8}">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Teléfono:</label>
                    <input type="text" name="telefono" placeholder="Ingrese el teléfono">
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-briefcase"></i> Cargo:</label>
                <select name="id_cargo" required>
                    <option value="">Seleccione un cargo</option>
                    <?php foreach ($cargos as $cargo): ?>
                        <option value="<?php echo $cargo['id_cargo']; ?>">
                            <?php echo htmlspecialchars($cargo['nombre_cargo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Registrar Empleado
            </button>
        </form>
    </div>
</div>