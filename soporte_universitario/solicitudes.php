<?php
require_once "conexion.php";

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = filter_var(
        $_POST["usuario_id"] ?? "",
        FILTER_VALIDATE_INT
    );

    $equipo_id = filter_var(
        $_POST["equipo_id"] ?? "",
        FILTER_VALIDATE_INT
    );

    $descripcion = trim($_POST["descripcion"] ?? "");

    if (!$usuario_id || !$equipo_id || $descripcion === "") {
        $mensaje = "Selecciona un usuario, un equipo y describe el problema.";
        $tipo = "error";
    } else {
        try {
            $sql = "INSERT INTO solicitudes
                    (id_usuario, id_equipo, problema, estado)
                    VALUES (:usuario_id, :equipo_id, :descripcion, 'No resuelto')";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":usuario_id" => $usuario_id,
                ":equipo_id" => $equipo_id,
                ":descripcion" => $descripcion
            ]);

            $mensaje = "Solicitud registrada con estado Pendiente.";
            $tipo = "exito";

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $mensaje = "No fue posible registrar la solicitud.";
            $tipo = "error";
        }
    }
}

$usuarios = $conexion->query(
    "SELECT id_usuario, nombre FROM usuarios ORDER BY nombre"
)->fetchAll();

$equipos = $conexion->query(
    "SELECT id_equipo, nombre FROM equipos ORDER BY nombre"
)->fetchAll();

$sql = "SELECT s.id_solicitud, s.problema, s.estado,
               u.nombre AS usuario,
               e.nombre AS equipo
        FROM solicitudes s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        INNER JOIN equipos e ON s.id_equipo = e.id_equipo
        ORDER BY s.id_solicitud DESC";

$solicitudes = $conexion->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
    <link rel="stylesheet" href="bonito.css"> 
    <script src="js/validaciones.js" defer></script>
</head>
<body>

<header class="encabezado">
    <h1>Módulo de solicitudes</h1>
</header>

<main class="contenedor">
    <a class="volver" href="index.php">Volver al menú</a>

    <h2>Registrar solicitud de soporte</h2>

    <?php if ($mensaje !== ""): ?>
        <p class="mensaje <?= $tipo ?>">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if (count($usuarios) === 0 || count($equipos) === 0): ?>
        <p class="mensaje error">
            Primero registra al menos un usuario y un equipo.
        </p>
    <?php else: ?>

        <form method="POST" action="mandarsol.php" id="formSolicitud">
            <label for="usuario_id">Usuario solicitante:</label>
            <select id="usuario_id" name="usuario_id" required>
                <option value="">Selecciona un usuario</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= (int)$usuario["id_usuario"] ?>">
                        <?= htmlspecialchars(
                            $usuario["nombre"], ENT_QUOTES, 'UTF-8'
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="equipo_id">Equipo:</label>
            <select id="equipo_id" name="equipo_id" required>
                <option value="">Selecciona un equipo</option>
                <?php foreach ($equipos as $equipo): ?>
                    <option value="<?= (int)$equipo["id_equipo"] ?>">
                        <?= htmlspecialchars(
                            $equipo["nombre"], ENT_QUOTES, 'UTF-8'
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="descripcion">Descripción del problema:</label>
            <textarea id="descripcion" name="descripcion"
                      rows="4" required></textarea>

            <button type="submit">Crear solicitud</button>
        </form>

    <?php endif; ?>

    <h2>Solicitudes registradas</h2>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Equipo</th>
                    <th>Problema</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($solicitudes as $solicitud): ?>
                <tr>
                    <td><?= (int)$solicitud["id_solicitud"] ?></td>
                    <td><?= htmlspecialchars(
                        $solicitud["usuario"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $solicitud["equipo"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $solicitud["problema"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $solicitud["estado"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($solicitudes) === 0): ?>
                <tr>
                    <td colspan="5">Todavía no hay solicitudes.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>