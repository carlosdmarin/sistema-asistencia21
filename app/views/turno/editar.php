<div class="contenido-principal">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-edit"></i> Editar Turno</h2>
        </div>
        
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/turno/actualizar" method="POST">
                <input type="hidden" name="id_turno" value="<?php echo $turno['id_turno']; ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre del Turno:</label>
                    <input type="text" id="nombre" name="nombre" required 
                           value="<?php echo htmlspecialchars($turno['nombre_turno']); ?>" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="hora_inicio">Hora de Inicio:</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required 
                           value="<?php echo $turno['hora_inicio']; ?>" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="hora_salida">Hora de Salida:</label>
                    <input type="time" id="hora_salida" name="hora_salida" required 
                           value="<?php echo $turno['hora_salida']; ?>" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="tolerancia">Tolerancia (minutos):</label>
                    <input type="number" id="tolerancia" name="tolerancia" 
                           value="<?php echo $turno['tolerancia_minutos']; ?>" 
                           min="0" max="60" class="form-control">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Turno
                    </button>
                    <a href="<?php echo BASE_URL; ?>/turno/ver" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>