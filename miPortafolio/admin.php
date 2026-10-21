
<?php
require_once "conexion.php";

// Eliminar mensaje
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["borrar_id"])) {
    $id = filter_input(INPUT_POST, "borrar_id", FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $conexion->prepare("DELETE FROM mensajes1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin.php?estado=borrado");
    exit;
}

$resultado = $conexion->query(
    "SELECT * FROM mensajes1 ORDER BY id DESC"
);

function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Mi Portafolio</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .admin-contenedor {
            width: 94%;
            margin: 30px auto;
            overflow-x: auto;
        }
        .admin-tabla {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        .admin-tabla th, .admin-tabla td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .admin-tabla th { background: #e8e8f5; }
        .admin-tabla td { overflow-wrap: anywhere; }
        .accion {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            width: 110px;
            height: 38px;
            padding: 0;
            margin: 3px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
}

         .editar {
            background: #dce8ff;
            color: #173b80;
            border: 1px solid #dce8ff;
}

        .borrar {
            background: #ffe0e0;
            color: #8b1515;
            border: 1px solid #ffe0e0;
}

        .admin-tabla td form {
            display: inline;
            margin: 0;
            padding: 0;
}
    </style>
</head>
<body>

<header>
    <h1>Administración de mensajes</h1>
    <a class="regreso" href="index.html">← Regresar al portafolio</a>
</header>

<main class="admin-contenedor">
    <?php if (isset($_GET["estado"])): ?>
<script>
    <?php if ($_GET["estado"] === "guardado"): ?>
        alert("¡Mensaje guardado correctamente!");
    <?php elseif ($_GET["estado"] === "actualizado"): ?>
        alert("¡Actualización Exitosa!");
    <?php elseif ($_GET["estado"] === "borrado"): ?>
        alert("¡Mensaje eliminado correctamente!");
    <?php endif; ?>
</script>
    <?php endif; ?>

    <h2>Mensajes recibidos</h2>

    <table class="admin-tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Mensaje</th>
                <th>Fecha de recepción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= h($fila["nombre"]) ?></td>
                    <td><?= h($fila["correo"]) ?></td>
                    <td><?= nl2br(h($fila["mensaje"])) ?></td>
                    <td><?= h($fila["timeStaps"]) ?></td>
                    <td>
                        <a class="accion editar"
                           href="editar.php?id=<?= (int)$fila["id"] ?>">
                                Editar ✏️
                        </a>

                        <form method="POST"
                              onsubmit="return confirm('¿Quieres borrar este mensaje?');">
                            <input type="hidden" name="borrar_id"
                                   value="<?= (int)$fila["id"] ?>">
                            <button class="accion borrar" type="submit">
                                Borrar🗑️ 
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>                <td colspan="54">Todavía no hay mensajes recibidos.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
<?php $conexion->close(); ?>