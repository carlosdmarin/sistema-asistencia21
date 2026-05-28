// public/js/dashboard.js

document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    
    // Abrir/cerrar sidebar en móvil
    if (menuToggle && sidebar && overlay) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
    
    // Submenús (funciona en todas las pantallas)
    const submenuHeaders = document.querySelectorAll('.menu-header');
    
    submenuHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            e.stopPropagation();
            const parent = this.parentElement;
            
            // Cerrar otros submenús abiertos
            document.querySelectorAll('.menu-item.has-submenu').forEach(item => {
                if (item !== parent) {
                    item.classList.remove('active');
                }
            });
            
            // Toggle el actual
            parent.classList.toggle('active');
        });
    });
    
    // Cerrar sidebar al hacer clic en un enlace (solo en móvil)
    document.querySelectorAll('.menu a, .submenu a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    });
    
    // Cerrar sidebar al redimensionar a desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
    
    console.log('Dashboard JS cargado correctamente');
});

// =============================================
// VARIABLES
// =============================================
let intervaloActualizacion;

// =============================================
// FUNCIÓN 1: PEDIR DATOS AL SERVIDOR
// =============================================
function obtenerDatos() {
    fetch(BASE_URL + '/dashboard/obtenerDatos')
        .then(respuesta => respuesta.json())
        .then(datos => {
            actualizarNumeros(datos);
        })
        .catch(error => console.log('Error al actualizar:', error));
}

// =============================================
// FUNCIÓN 2: ACTUALIZAR LOS NÚMEROS EN PANTALLA
// =============================================
function actualizarNumeros(datos) {
    // Buscar las 4 tarjetas de estadísticas
    const tarjetas = document.querySelectorAll('.stat-info h3');
    
    if (tarjetas.length >= 4) {
        tarjetas[0].textContent = datos.totalEmpleados;
        tarjetas[1].textContent = datos.asistenciasHoy;
        tarjetas[2].textContent = datos.ausentesHoy;
        tarjetas[3].textContent = datos.tardanzasHoy;
    }
    
    // Actualizar el porcentaje del círculo
    const porcentajeElemento = document.querySelector('.percentage-text .number');
    if (porcentajeElemento) {
        porcentajeElemento.textContent = datos.porcentajeAsistencia + '%';
    }
    
    // Actualizar el círculo de progreso (la barra azul)
    const circulo = document.querySelector('.circle-progress .progress');
    if (circulo) {
        const circunferencia = 283;
        const offset = circunferencia - (circunferencia * datos.porcentajeAsistencia / 100);
        circulo.style.strokeDashoffset = offset;
    }
    
    // Actualizar las barras del gráfico
    const barras = document.querySelectorAll('.bar');
    const valores = datos.asistenciasPorSemana;
    const maxValor = Math.max(...valores, 1);
    
    barras.forEach((barra, indice) => {
        if (indice < valores.length) {
            const altura = (valores[indice] / maxValor) * 180;
            barra.style.height = altura + 'px';
            
            // Actualizar el número debajo de la barra
            const numero = barra.parentElement.querySelector('small');
            if (numero) numero.textContent = valores[indice];
        }
    });
    
    // Actualizar la tabla de últimos registros
    const tbody = document.querySelector('.recent-table tbody');
    if (tbody && datos.ultimosRegistros) {
        let html = '';
        datos.ultimosRegistros.forEach(reg => {
            let claseEstado = '';
            if (reg.estado === 'Presente') claseEstado = 'presente';
            else if (reg.estado === 'Tardanza') claseEstado = 'tardanza';
            else claseEstado = 'ausente';
            
            html += `
                <tr>
                    <td>
                        <div class="empleado-info">
                            <i class="fas fa-user-circle"></i>
                            ${reg.empleado}
                        </div>
                    </td>
                    <td>${reg.fecha}</td>
                    <td>${reg.hora}</td>
                    <td><span class="status-badge ${claseEstado}">${reg.estado}</span></td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }
}

// =============================================
// FUNCIÓN 3: INICIAR ACTUALIZACIÓN AUTOMÁTICA
// =============================================
function iniciarActualizacion() {
    obtenerDatos();                              // Ejecuta una vez al entrar
    intervaloActualizacion = setInterval(obtenerDatos, 10000); // Cada 10 segundos
}

// =============================================
// FUNCIÓN 4: DETENER ACTUALIZACIÓN
// =============================================
function detenerActualizacion() {
    if (intervaloActualizacion) {
        clearInterval(intervaloActualizacion);
        intervaloActualizacion = null;
    }
}

// =============================================
// INICIAR CUANDO LA PÁGINA CARGA
// =============================================
document.addEventListener('DOMContentLoaded', iniciarActualizacion);

// =============================================
// DETENER CUANDO EL USUARIO CAMBIA DE PESTAÑA
// =============================================
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        detenerActualizacion();  // Usuario se fue a otra pestaña
    } else {
        iniciarActualizacion();  // Usuario volvió
    }
});
