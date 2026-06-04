<div class="contenido-principal">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-clock"></i> Registrar Nuevo Turno</h2>
        </div>
        <br>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/turno/guardar" method="POST" class="form-registro">
                
                <div class="form-group">
                    <label for="nombre"><i class="fas fa-tag"></i> Nombre del Turno:</label>
                    <input type="text" id="nombre" name="nombre" required 
                           placeholder="Ej: Manana, Tarde, Noche" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="hora_inicio"><i class="fas fa-sun"></i> Hora de Inicio:</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="hora_salida"><i class="fas fa-moon"></i> Hora de Salida:</label>
                    <input type="time" id="hora_salida" name="hora_salida" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="tolerancia"><i class="fas fa-hourglass-half"></i> Tolerancia (minutos):</label>
                    <input type="number" id="tolerancia" name="tolerancia" value="10" 
                           min="0" max="60" class="form-control">
                    <small>Minutos de tolerancia después de la hora de inicio</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Turno
                    </button>
                    <a href="<?php echo BASE_URL; ?>/turno/ver" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>