
<?php
require_once "conexion.php";

$mensaje = "";

// REGISTRAR GASTOS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fecha = $_POST['fecha'] ?? '';
    $concepto = trim($_POST['concepto'] ?? '');
    $monto = $_POST['monto'] ?? '';
    $categoria = $_POST['categoria'] ?? 'General';

    if (
        $fecha !== '' &&
        $concepto !== '' &&
        is_numeric($monto) &&
        (float)$monto > 0
    ) {

        $sql = "INSERT INTO gastos
                (fecha, concepto, monto, categoria)
                VALUES (?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if ($stmt) {

            $monto = (float)$monto;

            $stmt->bind_param(
                "ssds",
                $fecha,
                $concepto,
                $monto,
                $categoria
            );

            if ($stmt->execute()) {
                $mensaje = "Gasto registrado correctamente.";
            } else {
                $mensaje = "Error al guardar el gasto.";
            }

            $stmt->close();

        } else {
            $mensaje = "Error al preparar la consulta.";
        }

    } else {
        $mensaje = "Completa todos los campos correctamente.";
    }
}

// CONSULTAR GASTOS
$gastos = [];

$sql = "SELECT * FROM gastos ORDER BY fecha DESC";

$resultado = $conexion->query($sql);

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $gastos[] = $fila;
    }
}

// CALCULAR TOTAL
$total = 0;

foreach ($gastos as $gasto) {
    $total += (float)$gasto['monto'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar gastos</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1, h2 {
            color: #333;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .campo {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 6px;
            font-weight: bold;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #2563eb;
            color: white;
        }

        .total {
            margin-top: 20px;
            padding: 15px;
            background: #e0f2fe;
            font-size: 20px;
            font-weight: bold;
        }

        .mensaje {
            margin: 15px 0;
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 5px;
        }

        @media (max-width: 600px) {
            form {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 15px;
                overflow-x: auto;
            }
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Registrar gastos</h1>

    <?php if ($mensaje !== ''): ?>
        <div class="mensaje">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">

        <div class="campo">
            <label for="fecha">Fecha</label>

            <input
                type="date"
                id="fecha"
                name="fecha"
                value="<?= date('Y-m-d') ?>"
                required
            >
        </div>

        <div class="campo">
            <label for="concepto">Concepto</label>

            <input
                type="text"
                id="concepto"
                name="concepto"
                placeholder="Ej. Compra de comida"
                required
            >
        </div>

        <div class="campo">
            <label for="monto">Monto</label>

            <input
                type="number"
                id="monto"
                name="monto"
                step="0.01"
                min="0.01"
                placeholder="0.00"
                required
            >
        </div>

        <div class="campo">
            <label for="categoria">Categoría</label>

            <select id="categoria" name="categoria">

                <option value="General">General</option>
                <option value="Alimentación">Alimentación</option>
                <option value="Transporte">Transporte</option>
                <option value="Servicios">Servicios</option>
                <option value="Entretenimiento">Entretenimiento</option>
                <option value="Salud">Salud</option>

            </select>
        </div>

        <div class="campo" style="grid-column: 1 / -1;">

            <button type="submit">
                Guardar gasto
            </button>

        </div>

    </form>

    <h2>Gastos registrados</h2>

    <table>

        <thead>

            <tr>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Categoría</th>
                <th>Monto</th>
            </tr>

        </thead>

        <tbody>

            <?php if (!empty($gastos)): ?>

                <?php foreach ($gastos as $gasto): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($gasto['fecha']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($gasto['concepto']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($gasto['categoria']) ?>
                        </td>

                        <td>
                            $<?= number_format(
                                (float)$gasto['monto'],
                                2,
                                '.',
                                ','
                            ) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="4">
                        No hay gastos registrados.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

    <div class="total">

        Total gastado:
        $<?= number_format($total, 2, '.', ',') ?>

    </div>

</div>

</body>
</html>
