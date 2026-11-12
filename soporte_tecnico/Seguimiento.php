
<?php
require_once "conexion.php";

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $solicitud_id = filter_var(
        $_POST["solicitud_id"] ?? "",
        FILTER_VALIDATE_INT
    );

    $estado = $_POST["estado"] ?? "";
    $observacion = trim($_POST["observacion"] ?? "");

    $estadosPermitidos = ["En proceso", "Resuelta"];

    if (
        !$solicitud_id ||
        !in_array($estado, $estadosPermitidos, true)
    ) {
        $mensaje = "Selecciona una solicitud y un estado válido.";
        $tipo = "error";
    } else {
        try {
            $sql = "UPDATE solicitudes
                    SET estado = :estado,
                        observacion = :observacion
                    WHERE id = :id";

            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":estado" => $estado,
                ":observacion" => $observacion,
                ":id" => $solicitud_id
            ]);

            if ($stmt->rowCount() > 0) {
                $mensaje = "Seguimiento actualizado correctamente.";
                $tipo = "exito";
            } else {
                $verificar = $conexion->prepare(
                    "SELECT id FROM solicitudes WHERE id = ?"
                );
                $verificar->execute([$solicitud_id]);

                if ($verificar->fetch()) {
                    $mensaje = "La solicitud ya tenía esos datos.";
                    $tipo = "exito";
                } else {
                    $mensaje = "La solicitud seleccionada no existe.";
                    $tipo = "error";
                }
            }

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $mensaje = "No fue posible actualizar la solicitud.";
            $tipo = "error";
        }
    }
}

$sql = "SELECT s.id, s.descripcion, s.estado, s.observacion,
               s.fecha_creacion, s.fecha_actualizacion,
               u.nombre AS usuario,
               e.nombre AS equipo
        FROM solicitudes s
        INNER JOIN usuarios u ON s.usuario_id = u.id
        INNER JOIN equipos e ON s.equipo_id = e.id
        ORDER BY s.id DESC";

$solicitudes = $conexion->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de solicitudes</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/validaciones.js" defer></script>
</head>
<body>

<header class="encabezado">
    <h1>Módulo de seguimiento</h1>
</header>

<main class="contenedor">
    <a class="volver" href="index.php">Volver al menú</a>

    <h2>Actualizar solicitud</h2>

    <?php if ($mensaje !== ""): ?>
        <p class="mensaje <?= $tipo ?>">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if (count($solicitudes) > 0): ?>
        <form method="POST" id="formSeguimiento">

            <label for="solicitud_id">Solicitud:</label>
            <select id="solicitud_id" name="solicitud_id" required>
                <option value="">Selecciona una solicitud</option>

                <?php foreach ($solicitudes as $s): ?>
                    <option value="<?= (int)$s["id"] ?>">
                        <?= "#" . (int)$s["id"] . " - " .
                            htmlspecialchars(
                                $s["usuario"], ENT_QUOTES, 'UTF-8'
                            ) . " - " .
                            htmlspecialchars(
                                $s["equipo"], ENT_QUOTES, 'UTF-8'
                            ) . " (" .
                            htmlspecialchars(
                                $s["estado"], ENT_QUOTES, 'UTF-8'
                            ) . ")" ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="estado">Nuevo estado:</label>
            <select id="estado" name="estado" required>
                <option value="">Selecciona un estado</option>
                <option value="En proceso">En proceso</option>
                <option value="Resuelta">Resuelta</option>
            </select>

            <label for="observacion">Observación de atención:</label>
            <textarea id="observacion" name="observacion"
                      rows="4"
                      placeholder="Describe las acciones realizadas"></textarea>

            <button type="submit">Guardar seguimiento</button>
        </form>
    <?php else: ?>
        <p class="mensaje error">
            No existen solicitudes para actualizar.
            Registra una desde el módulo de solicitudes.
        </p>
    <?php endif; ?>

    <h2>Historial de solicitudes</h2>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Equipo</th>
                    <th>Problema</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Última actualización</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($solicitudes as $s): ?>
                <tr>
                    <td><?= (int)$s["id"] ?></td>
                    <td><?= htmlspecialchars(
                        $s["usuario"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $s["equipo"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $s["descripcion"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $s["estado"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $s["observacion"] ?? "", ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $s["fecha_actualizacion"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($solicitudes) === 0): ?>
                <tr>
                    <td colspan="7">No hay solicitudes registradas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
