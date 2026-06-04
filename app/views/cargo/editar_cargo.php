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

    <div class="form-group">
    <label><i class="fas fa-briefcase"></i> Turno:</label>
    <select name="id_turno" required>
        <option value="">Seleccione un turno</option>
        <?php foreach ($turnos as $turno): ?>
            <option value="<?php echo $turno['id_turno']; ?>"
                <?php echo $turno['id_turno'] == $empleado['id_turno'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($turno['nombre_turno']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
</div>