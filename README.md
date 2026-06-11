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
