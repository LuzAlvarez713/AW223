
<?php
require_once "conexion.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $mensaje = trim($_POST["mensaje"] ?? "");

    if (!$id || $nombre === "" ||
        !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
        $mensaje === "") {
        exit("Verifica los datos. Todos los campos son obligatorios y el correo debe ser válido.");
    }

    $stmt = $conexion->prepare(
        "UPDATE mensajes SET nombre = ?, correo = ?, mensaje = ? WHERE id = ?"
    );
    $stmt->bind_param("sssi", $nombre, $correo, $mensaje, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin.php?estado=actualizado");
        exit;
    }

    exit("No se pudieron actualizar los datos.");
}

if (!$id || $id < 1) {
    header("Location: admin.php");
    exit;
}

$stmt = $conexion->prepare("SELECT * FROM mensajes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$registro = $resultado->fetch_assoc();

$stmt->close();

if (!$registro) {
    exit("No se encontró el mensaje. <a href='admin.php'>Regresar</a>");
}

function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar mensaje</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .editar-contenedor {
            max-width: 650px;
            width: 92%;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }
        .editar-contenedor label {
            display: block;
            margin-top: 15px;
        }
        .editar-contenedor input,
        .editar-contenedor textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        .editar-contenedor button {
            margin-top: 20px;
            padding: 10px 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h1>Editar mensaje</h1>
    <p>
        <a href="index.html">← Volver al portafolio</a> |
        <a href="admin.php">← Volver a administración</a>
    </p>
</header>

<main class="editar-contenedor">
    <h2>Actualizar mensaje #<?= (int)$registro["id"] ?></h2>

    <form method="POST" action="editar.php">
        <input type="hidden" name="id" value="<?= (int)$registro["id"] ?>">

        <label for="nombre">Nombre:</label>
        <input id="nombre" name="nombre" type="text" required
               value="<?= h($registro["nombre"]) ?>">

        <label for="correo">Correo electrónico:</label>
        <input id="correo" name="correo" type="email" required
               value="<?= h($registro["correo"]) ?>">

        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" rows="5"
                  required><?= h($registro["mensaje"]) ?></textarea>

        <button type="submit">Guardar cambios</button>
        <a href="admin.php">Cancelar</a>
    </form>
</main>

</body>
</html>
<?php $conexion->close(); ?>