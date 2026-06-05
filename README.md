# SISTEMA DE REGISTROS DE EMPLEADOS Y ASISTENCIAS

## Descripcion del negocio
### Nombre: (Buscando empresa...)

### Giro: 
Software de gestión de recursos humanos para pequeñas y medianas empresas (pymes), restaurantes, tiendas, consultorías y equipos remotos.

### Propósito:
Centralizar el control de asistencia, horarios, y datos de empleados en un solo lugar, reemplazando hojas de Excel, papel o relojes biométricos costosos

## Identificar el problema y solución
| Problema | Descripción |
|----------|-------------|
| Registros manuales | Los empleados anotan su entrada/salida en hojas de papel o Excel, generando errores, olvidos o falsificaciones. |
| Falta de transparencia | Los trabajadores no ven su historial de asistencias ni acumulado de retardos. |
| Cálculo laborioso | RR.HH. o el dueño pierde horas sumando horas extra, tardanzas y deducciones. |
| Dificultad con equipos remotos | Sin forma confiable de saber si un empleado trabajó desde casa. |
| Pérdida de información | Los registros en papel se extravían o dañan. |
# Preanálisis
Llevar un control diario de la asistencia de cada trabajador (entrada, salida, faltas y tardanzas).
Registrar los horarios y turnos asignados a cada empleado.
Calcular de forma automática las horas extras trabajadas.
Calcular los pagos de salarios de manera rápida y sin errores.
Generar reportes claros sobre la situación del personal.
Tener toda la información de los empleados en un solo lugar, accesible para la administración.

# Estudio de viabilidad

Viabilidad técnica:
 El sistema se puede desarrollar con herramientas tecnológicas disponibles y accesibles. No se requiere equipos demasiado avanzados, solo computadoras con conexión básica y un servidor o una base de datos local. Es factible de implementar.

Viabilidad económica:
 El costo de desarrollo e implementación del sistema es razonable y se recuperaría a mediano plazo al reducir los errores en pagos y el tiempo perdido en tareas administrativas. Además, evita posibles multas o problemas legales por falta de control del personal.

Viabilidad operativa:
El personal administrativo de Norky's puede aprender a usar el sistema con una capacitación básica. El sistema será sencillo e intuitivo, por lo que no debería generar resistencia al cambio. Al contrario, facilitará su trabajo diario.

## Alcance del sistema

El sistema se encargará de:

Registrar, modificar y eliminar datos de los empleados (nombre, cargo, turno, etc.).
Controlar la asistencia diaria con registro de horarios de entrada y salida.
Calcular automáticamente las horas trabajadas, horas extras, tardanzas y faltas.
Calcular el pago mensual de cada empleado según sus horas trabajadas y beneficios.
Generar reportes como: lista de empleados, asistencias mensuales, empleados faltistas, etc.

El sistema no incluirá:

El manejo de nóminas contables avanzadas o integración con sistemas contables externos.
El control de inventarios o ventas del restaurante (solo gestión de personal).
Una aplicación móvil (inicialmente solo versión para computadora).

 # Análisis

 ## Requisitos funcionales:

- El sistema debe permitir registrar un nuevo empleado ingresando su DNI, nombres, apellidos, salario, celular, cargo y  turno asignado.
- El sistema debe leer el código del DNI del empleado mediante un sensor o lector de código de barras.
- Al pasar el DNI por el sensor, el sistema debe registrar automáticamente la hora de entrada o salida del empleado.
- El sistema debe identificar si el empleado ya registró su entrada o salida para evitar duplicados.
- El sistema debe calcular automáticamente las horas trabajadas, horas extras, tardanzas y faltas de cada empleado.
- El sistema debe calcular el pago mensual de cada empleado en base a sus horas trabajadas.
- El sistema debe generar reportes como: asistencia diaria, asistencia mensual, empleados faltistas, y lista de empleados.
- El sistema debe permitir buscar empleados por su nombre o por su número de DNI.
- El sistema debe permitir modificar o eliminar datos de empleados cuando sea necesario.

 ## Requisitos no funcionales:

- El sistema debe ser fácil de usar, con una interfaz sencilla e intuitiva para el personal administrativo.
- El sistema debe registrar la asistencia en menos de 3 segundos después de pasar el DNI por el sensor.
- El sistema debe ser confiable, evitando pérdida de datos o registros duplicados.
- El sistema debe funcionar en computadoras con recursos básicos (Windows 7 o superior, 4GB de RAM).
- El sistema debe tener un respaldo automático o manual de los datos para evitar pérdidas.
- El sistema debe ser seguro, permitiendo el acceso solo al personal autorizado (por ejemplo, con contraseña de administrador).
- El sistema debe funcionar correctamente incluso sin conexión a internet (local).

  # IMAGEN DEL NEGOCIO
![imagen](imagenes/)

## Stack Tecnológico

| Capa | Tecnología |
|---|---|
| **Backend** | PHP 8+ — POO (Programación Orientada a Objetos) — MVC desde cero |
| **Base de datos** | MariaDB — PDO (PHP Data Objects) con prepared statements |
| **Frontend** | HTML5, CSS3, JavaScript — Vistas PHP con layouts reutilizables |
| **Servidor web** | Apache — Reescritura de URLs vía `.htaccess` |
| **Control de versiones** | Git + GitHub |
| **Configuración** | Variables de entorno (`.env`) para credenciales |
---

## Arquitectura del Proyecto

El sistema aplica **POO** y **MVC** implementado desde cero. Los 4 pilares de POO en el proyecto:

### Flujo de una Petición


### Estructura del Proyecto

## Instalación

### Requisitos previos
- PHP 8+
- Apache con `mod_rewrite` habilitado (XAMPP recomendado)
- MariaDB / MySQL

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/carlosdmarin/SISTEMA-ASISTENCIA.git
cd SISTEMA-ASISTENCIA

# 2. Configurar variables de entorno
cp .env.example .env
# Editar .env con tus credenciales de base de datos

# 3. Crear la base de datos


# 4. Apuntar el servidor web a la carpeta public/

```

## TRELLO

### DIAGRAMA DE FIGMA UI/UX

## Base de datos
```sql

-- =============================================
-- BASE DE DATOS COMPLETA - SISTEMA DE ASISTENCIAS

-- =============================================
-- 1. CREACIÓN DE LA BASE DE DATOS
-- =============================================

CREATE DATABASE IF NOT EXISTS sistema_de_asistencia
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_general_ci;

USE sistema_de_asistencia;

-- =============================================
-- 2. TABLAS ESTRUCTURALES
-- =============================================

-- TABLA: TURNO
CREATE TABLE TURNO (
    id_turno INT PRIMARY KEY AUTO_INCREMENT,
    nombre_turno VARCHAR(100) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_salida TIME NOT NULL,
    tolerancia_minutos INT DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLA: CARGO
CREATE TABLE CARGO (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cargo VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLA: USUARIO
CREATE TABLE USUARIO (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    clave VARCHAR(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLA: ASISTENCIA (actualizada con hora_entrada NULL permitido)
CREATE TABLE ASISTENCIA (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    fecha DATE DEFAULT (CURRENT_DATE) NOT NULL,
    hora_entrada TIME NULL DEFAULT NULL,
    hora_salida TIME NULL,
    estado ENUM('asistio', 'tardanza', 'falto') NOT NULL,
    FOREIGN KEY (id_empleado) REFERENCES EMPLEADO(id_empleado) ON DELETE CASCADE,
    UNIQUE KEY unique_asistencia_dia (id_empleado, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



```

### Diagrama Entidad-Relacion (DER)

 
### Modelo Relacional (MR)
![MODELO_RELACIONAL]()

### Cardinalidades
