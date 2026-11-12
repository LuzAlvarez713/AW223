
<?php
require_once "conexion.php";

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");

    if ($nombre === "" || $correo === "") {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo = "error";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Escribe un correo electrónico válido.";
        $tipo = "error";
    } else {
        try {
            $sql = "INSERT INTO usuarios (nombre, correo)
                    VALUES (:nombre, :correo)";

            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":nombre" => $nombre,
                ":correo" => $correo
            ]);

            $mensaje = "Usuario registrado correctamente.";
            $tipo = "exito";

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $mensaje = "Ese correo ya está registrado.";
            } else {
                error_log($e->getMessage());
                $mensaje = "No fue posible registrar el usuario.";
            }
            $tipo = "error";
        }
    }
}

$consulta = $conexion->query(
    "SELECT id, nombre, correo FROM usuarios ORDER BY id DESC"
);
$usuarios = $consulta->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/validaciones.js" defer></script>
</head>
<body>

<header class="encabezado">
    <h1>Módulo de usuarios</h1>
</header>

<main class="contenedor">
    <a class="volver" href="index.php">Volver al menú</a>

    <h2>Registrar usuario</h2>

    <?php if ($mensaje !== ""): ?>
        <p class="mensaje <?= $tipo ?>">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" id="formUsuario">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre"
               maxlength="100" required>

        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo"
               maxlength="150" required>

        <button type="submit">Registrar usuario</button>
    </form>

    <h2>Usuarios registrados</h2>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= (int)$usuario["id"] ?></td>
                    <td><?= htmlspecialchars(
                        $usuario["nombre"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                    <td><?= htmlspecialchars(
                        $usuario["correo"], ENT_QUOTES, 'UTF-8'
                    ) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($usuarios) === 0): ?>
                <tr>
                    <td colspan="3">Todavía no hay usuarios registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
