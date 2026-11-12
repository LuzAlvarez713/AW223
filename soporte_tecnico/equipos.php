
<?php
require_once "conexion.php";

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");

    if ($nombre === "" || $descripcion === "") {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo = "error";
    } else {
        try {
            $sql = "INSERT INTO equipos (nombre, descripcion)
                    VALUES (:nombre, :descripcion)";

            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":nombre" => $nombre,
                ":descripcion" => $descripcion
            ]);

            $mensaje = "Equipo registrado correctamente.";
            $tipo = "exito";

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $mensaje = "No fue posible registrar el equipo.";
            $tipo = "error";
        }
    }
}

$consulta = $conexion->query(
    "SELECT id, nombre, descripcion
     FROM equipos ORDER BY id DESC"
);
$equipos = $consulta->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/validaciones.js" defer></script>
</head>
<body>

<header class="encabezado">
    <h1>Módulo de equipos</h1>
</header>

<main class="contenedor">
    <a class="volver" href="index.php">Volver al menú</a>

    <h2>Registrar equipo</h2>

    <?php if ($mensaje !== ""): ?>
        <p class="mensaje <?= $tipo ?>">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" id="formEquipo">
        <label for="nombre">Nombre del equipo:</label>
        <input type="text" id="nombre" name="nombre"
               maxlength="100" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"
                  rows="4" required></textarea>

        <button type="submit">Registrar equipo</button>
    </form>

    <h2>Equipos registrados</h2>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($equipos as $equipo): ?>
                <tr>
                    <td><?= (int)$equipo["id"] ?></td>
                    <td><?= htmlspecialchars(
                        $equipo["nombre"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $equipo["descripcion"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($equipos) === 0): ?>
                <tr>
                    <td colspan="3">Todavía no hay equipos registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
