# 🚀 SISTEMA DE GESTIÓN DE ASISTENCIAS - MOTO-CARS INVERSIONES

[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 📌 Descripción del Negocio

| Campo | Valor |
|-------|-------|
| **Nombre** | MOTO-CARS INVERSIONES |
| **Giro** | Venta de motos lineales y motokares de la marca Honda |
| **Ubicación** | Local comercial con sucursal física |
| **Personal** | 20 empleados (ventas, mecánicos, administrativos, limpieza) |

---

## 🚨 El Problema Real

| # | Problema | Consecuencia |
|---|----------|--------------|
| 1 | ❌ Registros manuales | Empleados anotaban entrada/salida en hojas de papel → errores y falsificaciones |
| 2 | ❌ Falta de control | Administrador no sabía en tiempo real quién llegó tarde o faltó |
| 3 | ❌ Cálculo laborioso | Dueño perdía horas sumando tardanzas, faltas y horas extras |
| 4 | ❌ Pérdida de información | Hojas de papel se extraviaban o dañaban |
| 5 | ❌ Sin justificaciones | No quedaba registro cuando un empleado faltaba por motivos de salud |
| 6 | ❌ Reportes manuales | Generar reportes tomaba horas y siempre tenían errores |

### 💬 La necesidad del dueño

> *"Un sistema sencillo donde los empleados marquen su entrada y salida con su DNI, que me avise quién llega tarde, que me calcule las faltas solo, y que me genere reportes sin que yo tenga que hacer cuentas a mano."*

---

## ✅ La Solución

| Módulo | Función |
|--------|---------|
| 🔐 **Autenticación** | Login seguro con contraseñas encriptadas (password_hash) |
| 👥 **Empleados** | CRUD completo: registrar, editar, eliminar, buscar por nombre/DNI |
| 💼 **Cargos** | Gestión de puestos laborales (vendedor, mecánico, administrativo) |
| ⏰ **Turnos** | Configuración de horarios (entrada, salida, tolerancia de tardanza) |
| 📅 **Asistencia con Lector** | Marcación con DNI mediante lector de código de barras. Detección automática de tardanza |
| 📋 **Historial** | Consulta de asistencias pasadas por fecha específica |
| ✏️ **Justificaciones** | Administrador puede justificar faltas con motivo. Queda registro permanente |
| 📊 **Reportes** | Asistencia por fecha (Excel/PDF), Resumen Mensual, Ranking de Puntualidad |
| 🤖 **Automatización (CRON)** | Marca faltas y salidas automáticamente según horario de cada turno |
| 🖥️ **Dashboard** | Estadísticas en tiempo real, gráficos, últimos registros (AJAX cada 30 seg) |

---

## 🛠️ Stack Tecnológico

| Herramienta | Versión | Uso |
|-------------|---------|-----|
| **Visual Studio Code** | Última | Editor de código |
| **XAMPP** | 8.2 | Entorno local (Apache + MySQL + PHP) |
| **Apache** | 2.4 | Servidor web |
| **PHP** | 8.2 | Backend, POO, MVC desde cero |
| **MySQL / MariaDB** | 10.4 | Base de datos relacional |
| **phpMyAdmin** | 5.2 | Administración visual de BD |
| **HTML5** | - | Estructura de páginas |
| **CSS3** | - | Estilos y diseño responsive |
| **JavaScript** | ES6 | Interactividad, AJAX, actualizaciones en tiempo real |
| **SweetAlert2** | 11 | Modales y alertas personalizadas |
| **FontAwesome** | 6.5 | Iconos y tipografía |
| **Git** | 2.4 | Control de versiones |
| **GitHub** | - | Repositorio remoto |
| **Trello** | - | Gestión de tareas (metodología ágil) |
| **Figma** | - | Diseño de UI/UX (prototipo) |
| **Draw.io** | - | Diagrama de base de datos (DER) |

---

## 🧱 Arquitectura MVC
┌─────────────────────────────────────────────────────────────────┐
│ SISTEMA MVC │
├─────────────────────────────────────────────────────────────────┤
│ │
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ │
│ │ MODELO │◄───►│ CONTROLADOR │◄───►│ VISTA │ │
│ │ (Base de │ │ (Lógica) │ │ (HTML) │ │
│ │ Datos) │ │ │ │ │ │
│ └─────────────┘ └─────────────┘ └─────────────┘ │
│ ▲ ▲ ▲ │
│ │ │ │ │
│ ▼ ▼ ▼ │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ FLUJO DE PETICIÓN │ │
│ │ 1. Usuario escribe URL │ │
│ │ 2. .htaccess redirige a index.php │ │
│ │ 3. Router determina qué controlador ejecutar │ │
│ │ 4. Controlador llama al modelo (consulta BD) │ │
│ │ 5. Controlador carga la vista con los datos │ │
│ │ 6. Se muestra HTML al usuario │ │
│ └─────────────────────────────────────────────────────────┘ │
│ │
└─────────────────────────────────────────────────────────────────┘

text

### Estructura de Carpetas
SISTEMA-ASISTENCIA/
├── app/
│ ├── config/ # Configuración (BD, constantes)
│ ├── controllers/ # Controladores (lógica de negocio)
│ ├── core/ # Clases base (Router, Controller, Model, Database)
│ ├── models/ # Modelos (consultas SQL)
│ └── views/ # Vistas HTML/CSS/JS
│ ├── layouts/ # Headers y footers
│ └── [módulos]/ # Vistas específicas
├── public/
│ ├── css/ # Estilos del sistema
│ ├── js/ # Scripts JavaScript
│ └── images/ # Recursos gráficos
├── .env # Variables de entorno
├── .htaccess # Reescritura de URLs
└── cron_asistencia.php # Tareas automáticas (CRON)

text

---

## ⚙️ Instalación

### Requisitos Previos

| Requisito | Versión |
|-----------|---------|
| XAMPP | 8.2 o superior |
| PHP | 8.0 o superior |
| MySQL / MariaDB | 10.4 o superior |
| Git | Opcional |
| Navegador | Chrome, Firefox, Edge, Safari |

### Pasos

```bash
# 1. Clonar repositorio
git clone https://github.com/carlosdmarin/SISTEMA-ASISTENCIA.git
cd SISTEMA-ASISTENCIA

# 2. Copiar configuración de entorno
cp .env.example .env

# 3. Editar .env con tus credenciales
DB_HOST=localhost
DB_NAME=sistema_de_asistencia
DB_USER=root
DB_PASS=

# 4. Iniciar XAMPP (Apache y MySQL)

# 5. Crear base de datos
# Abrir phpMyAdmin → ejecutar script SQL (ver sección Base de Datos)

# 6. Copiar proyecto a htdocs
# Windows: C:\xampp\htdocs\SISTEMA-ASISTENCIA
# Mac: /Applications/XAMPP/htdocs/SISTEMA-ASISTENCIA

# 7. Acceder al sistema
http://localhost/SISTEMA-ASISTENCIA

# 8. Usuario por defecto
# Usuario: admin
# Contraseña: admin123
Configuración del CRON (Tareas Automáticas)
🍎 Mac / Linux
bash
crontab -e
# Agregar (ejecuta a las 6:00 PM)
0 18 * * * /Applications/XAMPP/bin/php /ruta/a/SISTEMA-ASISTENCIA/cron_asistencia.php
🪟 Windows (Programador de tareas)
powershell
# Abrir Programador de tareas (taskschd.msc)
# Crear tarea básica:
#   - Nombre: "Sistema Asistencias"
#   - Disparador: Diario, 6:00 PM
#   - Acción: Iniciar programa
#     Programa: C:\xampp\php\php.exe
#     Argumentos: -f "C:\xampp\htdocs\SISTEMA-ASISTENCIA\cron_asistencia.php"
📊 Base de Datos
Script SQL Completo
sql
-- =============================================
-- CREACIÓN DE LA BASE DE DATOS
-- =============================================

CREATE DATABASE IF NOT EXISTS sistema_de_asistencia
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_general_ci;

USE sistema_de_asistencia;

-- TABLA: TURNO
CREATE TABLE TURNO (
    id_turno INT PRIMARY KEY AUTO_INCREMENT,
    nombre_turno VARCHAR(100) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_salida TIME NOT NULL,
    tolerancia_minutos INT DEFAULT 10
);

-- TABLA: CARGO
CREATE TABLE CARGO (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cargo VARCHAR(50) NOT NULL
);

-- TABLA: USUARIO
CREATE TABLE USUARIO (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    clave VARCHAR(250) NOT NULL
);

-- TABLA: EMPLEADO
CREATE TABLE EMPLEADO (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(8) UNIQUE NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    fecha_registro DATE DEFAULT (CURRENT_DATE) NOT NULL,
    id_cargo INT NOT NULL,
    id_turno INT NOT NULL,
    FOREIGN KEY (id_turno) REFERENCES TURNO(id_turno),
    FOREIGN KEY (id_cargo) REFERENCES CARGO(id_cargo)
);

-- TABLA: ASISTENCIA
CREATE TABLE ASISTENCIA (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    fecha DATE DEFAULT (CURRENT_DATE) NOT NULL,
    hora_entrada TIME DEFAULT NULL,
    hora_salida TIME NULL,
    estado ENUM('asistio', 'tardanza', 'falto') NOT NULL,
    FOREIGN KEY (id_empleado) REFERENCES EMPLEADO(id_empleado) ON DELETE CASCADE,
    UNIQUE KEY unique_asistencia_dia (id_empleado, fecha)
);

-- TABLA: JUSTIFICACION
CREATE TABLE JUSTIFICACION (
    id_justificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_asistencia INT NOT NULL,
    motivo TEXT NOT NULL,
    documento VARCHAR(255) NULL,
    fecha_justificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    justificado_por INT NOT NULL,
    FOREIGN KEY (id_asistencia) REFERENCES ASISTENCIA(id_asistencia) ON DELETE CASCADE
);
Diagrama Entidad-Relación (DER)
https://recursos/imagenes/DER.png

Modelo Relacional
https://recursos/imagenes/DIAGRAMA_DB.png

Cardinalidades
Relación	Tipo	Explicación
CARGO → EMPLEADO	1 : N	Un cargo tiene muchos empleados
TURNO → EMPLEADO	1 : N	Un turno tiene muchos empleados
EMPLEADO → ASISTENCIA	1 : N	Un empleado tiene muchas asistencias
ASISTENCIA → JUSTIFICACION	1 : 1	Una asistencia puede tener una justificación
🗂️ Gestión del Proyecto
📋 Trello - Metodología Ágil
Lista	Descripción
📋 Backlog	Tareas pendientes (reportes futuros, mejoras)
🚧 En Progreso	Tareas en desarrollo
✅ Terminado	Módulos completados (13 módulos)
🐛 Bugs	Errores detectados y corregidos
🔗 Ver tablero de Trello

🎨 UI/UX - Figma
Se diseñó un prototipo interactivo antes de la implementación.

🔗 Ver diseño en Figma

📸 Capturas de Pantalla
🖥️ Dashboard Principal
https://recursos/imagenes/dashboard.png

📅 Asistencia con Lector de Código de Barras
https://recursos/imagenes/lector.png

📊 Resumen Mensual
https://recursos/imagenes/reporte_mensual.png

🏆 Ranking de Puntualidad
https://recursos/imagenes/ranking.png

👥 Gestión de Empleados
https://recursos/imagenes/empleados.png

📈 Logros del Proyecto
Logro	Descripción
✅ 100% funcional	Todos los módulos operativos
✅ MVC desde cero	Sin frameworks, código limpio y ordenado
✅ AJAX en tiempo real	Tablas se actualizan cada 30 segundos sin recargar
✅ CRON automático	Faltas y salidas se marcan solas según horario de cada turno
✅ Reportes profesionales	Exportación a Excel y PDF con diseño corporativo
✅ Justificaciones	Registro de motivos de faltas
✅ Responsive	Funciona en móvil, tablet y desktop
✅ Seguridad	Contraseñas encriptadas, prepared statements contra SQL injection
👥 Autores
Nombre	Rol	Contacto
Carlos Marín	Desarrollo completo	GitHub
MOTO-CARS INVERSIONES	Cliente / Caso de estudio	-
📄 Licencia
Este proyecto está bajo la licencia MIT.
Puedes usarlo, modificarlo y distribuirlo libremente.

🙏 Agradecimientos
A los dueños de MOTO-CARS INVERSIONES por confiar en el proyecto.

A los empleados que participaron en las pruebas del sistema.

A la comunidad de open source por las herramientas gratuitas.

📧 Contacto
Medio	Enlace
📩 Email	carlosdmarin@email.com
🔗 GitHub	github.com/carlosdmarin
💼 LinkedIn	linkedin.com/in/carlosdmarin
⭐ ¿Te gustó el proyecto?
Si este proyecto te fue útil, dale una ⭐ en GitHub y compártelo.

© 2026 - Sistema de Gestión de Asistencias para MOTO-CARS INVERSIONES

text

---

## 📌 Instrucciones finales

1. **Copia TODO el código** (desde `# 🚀 SISTEMA...` hasta el final)
2. **Pégalo en tu archivo `README.md`** en GitHub
3. **Crea la carpeta** `recursos/imagenes/` y agrega tus capturas de pantalla
4. **Reemplaza los enlaces** de Trello y Figma con los tuyos
5. **Sube los cambios**

```bash
git add README.md
git commit -m "README profesional completo con stack tecnológico"
git push
