<?php
// app/views/reporte/pdf_asistencia.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia - <?php echo date('d/m/Y', strtotime($fecha)); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #172535;
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .fecha {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th {
            background: #172535;
            color: white;
            padding: 8px;
            text-align: left;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }
        button {
            margin-bottom: 20px;
            padding: 8px 16px;
            background: #172535;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        @media print {
            button { display: none; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()">🖨️ Imprimir / Guardar PDF</button>
    
    <h1>Reporte de Asistencia</h1>
    <div class="fecha">Fecha: <?php echo date('d/m/Y', strtotime($fecha)); ?></div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Empleado</th>
                <th>DNI</th>
                <th>Cargo</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $row): ?>
            <tr>
                <td><?php echo $row['id_empleado']; ?></td>
                <td><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                <td><?php echo $row['dni']; ?></td>
                <td><?php echo htmlspecialchars($row['nombre_cargo']); ?></td>
                <td><?php echo $row['hora_entrada'] ?? '—'; ?></td>
                <td><?php echo $row['hora_salida'] ?? '—'; ?></td>
                <td><?php echo $row['estado']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>