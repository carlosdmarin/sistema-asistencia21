document.addEventListener('DOMContentLoaded', function() {
    
    // ========== RELOJ ==========
    function updateClock() {
        const now = new Date(); // obtenemos la fecha y hora de la computadora
        const relojElement = document.getElementById('clock'); // es para la hora
        const fechaElement = document.getElementById('date');  // es para la fecha
        
        if (relojElement) {
            relojElement.textContent = 
                `${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}:${now.getSeconds().toString().padStart(2,'0')}`;
        }
        
        if (fechaElement) {
            fechaElement.textContent = now.toLocaleDateString('es-PE', {
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric'
            });
        }
    }
    updateClock(); 
    setInterval(updateClock, 1000);
    
    // ========== FOCO SIEMPRE EN EL INPUT ==========
    const lectorInput = document.getElementById('lectorDni');
    if (lectorInput) {
        lectorInput.focus();
        document.addEventListener('click', function() {
            lectorInput.focus();
        });
    }
    
    // ========== SONIDOS ==========
    function crearSonido(tipo) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            
            if (tipo === 'exito') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(523, ctx.currentTime);
                osc.frequency.setValueAtTime(659, ctx.currentTime + 0.1);
                osc.frequency.setValueAtTime(784, ctx.currentTime + 0.2);
            } else {
                osc.type = 'square';
                osc.frequency.setValueAtTime(200, ctx.currentTime);
                osc.frequency.setValueAtTime(150, ctx.currentTime + 0.15);
            }
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.4);
        } catch(e) {
            console.log('Error con el audio:', e);
        }
    }
    
    // ========== MARCAR ASISTENCIA ==========
    function marcarAsistencia(dni) {
        const card = document.getElementById('resultCard');
        if (!card) return;
        
        card.classList.remove('hidden');
        card.innerHTML = `
            <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 text-center border border-gray-700/50">
                <div class="flex items-center justify-center gap-3 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-blue-400"></i>
                    <span>Procesando...</span>
                </div>
            </div>`;
        
        fetch(`${BASE_URL}/asistencia/marcar`, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'dni=' + encodeURIComponent(dni)
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                crearSonido('exito');
                
                if (data.tipo === 'salida') {
                    card.innerHTML = `
                        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 text-center border border-blue-500/30">
                            <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-door-open text-blue-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-xs uppercase tracking-widest mb-2">Salida registrada</p>
                            <h2 class="text-white text-2xl font-bold">${data.empleado.nombre} ${data.empleado.apellido}</h2>
                            <p class="text-gray-400 text-sm mt-1">${data.empleado.cargo}</p>
                            <p class="text-blue-400 text-3xl font-bold mt-4" style="font-family: 'SF Mono', 'Courier New', monospace;">${data.empleado.hora}</p>
                        </div>`;
                } else {
                    const isTarde = data.empleado.estado === 'tardanza';
                    card.innerHTML = `
                        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 text-center border ${isTarde ? 'border-amber-500/30' : 'border-emerald-500/30'}">
                            <div class="w-16 h-16 ${isTarde ? 'bg-amber-500/20' : 'bg-emerald-500/20'} rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas ${isTarde ? 'fa-exclamation-triangle text-amber-400' : 'fa-check-circle text-emerald-400'} text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-xs uppercase tracking-widest mb-2">Entrada registrada</p>
                            <h2 class="text-white text-2xl font-bold">${data.empleado.nombre} ${data.empleado.apellido}</h2>
                            <p class="text-gray-400 text-sm mt-1">${data.empleado.cargo}</p>
                            <p class="${isTarde ? 'text-amber-400' : 'text-emerald-400'} text-3xl font-bold mt-4" style="font-family: 'SF Mono', 'Courier New', monospace;">${data.empleado.hora}</p>
                            <span class="inline-block mt-4 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider ${isTarde ? 'bg-amber-500/20 text-amber-400' : 'bg-emerald-500/20 text-emerald-400'}">
                                ${isTarde ? 'Tardanza' : 'A tiempo'}
                            </span>
                        </div>`;
                }
            } else {
                crearSonido('error');
                card.innerHTML = `
                    <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 text-center border border-red-500/30">
                        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-400 text-xs uppercase tracking-widest mb-2">Error</p>
                        <h3 class="text-white text-lg font-bold">${data.mensaje}</h3>
                    </div>`;
            }
            setTimeout(() => card.classList.add('hidden'), 5000);
            if (lectorInput) lectorInput.focus();
        })
        .catch(error => {
            console.error('Error:', error);
            crearSonido('error');
        });
    }
    
    // ========== LECTOR - DETECTAR ENTER ==========
    if (lectorInput) {
        lectorInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const dni = this.value.trim();
                if (dni.length === 8) {
                    marcarAsistencia(dni);
                    this.value = '';
                }
            }
        });
    }
    
    // ========== CARGAR ÚLTIMAS ASISTENCIAS ==========
    function actualizarRegistros(){
        fetch(`${BASE_URL}/asistencia/ultimas`)
        .then(r => r.text())
        .then(html => {
            const listaElement = document.getElementById('listaAsistencias');
            if(listaElement){
                listaElement.innerHTML = html;
            }
        })
        .catch(error => console.error('Error cargando las ultmimas asitencias:', error));
    }

    // cargamos al incio 
    actualizarRegistros();
    // actualizamos cada 3 segundos
    setInterval(actualizarRegistros,  3000);
});
