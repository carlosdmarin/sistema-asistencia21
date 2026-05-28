<div class="empleados-container">
    <div class="empleados-header">
        <a href="<?php echo BASE_URL; ?>/empleado/ver" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h1><i class="fas fa-edit"></i> Editar Empleado</h1>
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
        <form method="POST" action="<?php echo BASE_URL; ?>/empleado/actualizar">
            <input type="hidden" name="id_empleado" value="<?php echo $empleado['id_empleado']; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre:</label>
                    <input type="text" name="nombre" required value="<?php echo htmlspecialchars($empleado['nombre']); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Apellido:</label>
                    <input type="text" name="apellido" required value="<?php echo htmlspecialchars($empleado['apellido']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> DNI:</label>
                    <input type="text" name="dni" required value="<?php echo $empleado['dni']; ?>" maxlength="8" pattern="[0-9]{8}">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Teléfono:</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($empleado['telefono']); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-briefcase"></i> Cargo:</label>
                <select name="id_cargo" required>
                    <option value="">Seleccione un cargo</option>
                    <?php foreach ($cargos as $cargo): ?>
                        <option value="<?php echo $cargo['id_cargo']; ?>" 
                            <?php echo $cargo['id_cargo'] == $empleado['id_cargo'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cargo['nombre_cargo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Actualizar Empleado
            </button>
        </form>
    </div>
</div>