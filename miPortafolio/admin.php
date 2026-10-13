
<?php
require_once "conexion.php";

// Eliminar mensaje
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["borrar_id"])) {
    $id = filter_input(INPUT_POST, "borrar_id", FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $conexion->prepare("DELETE FROM mensajes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin.php?estado=borrado");
    exit;
}

$resultado = $conexion->query(
    "SELECT * FROM mensajes ORDER BY fecha DESC"
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
            display: inline-block;
            padding: 7px;
            margin: 3px;
            text-decoration: none;
            border-radius: 5px;
        }
        .editar { background: #dce8ff; color: #173b80; }
        .borrar { background: #ffe0e0; color: #8b1515; border: 0; cursor: pointer; }
        .regreso { display: inline-block; margin: 20px 0; }
    </style>
</head>
<body>

<header>
    <h1>Administración de mensajes</h1>
    <a class="regreso" href="index.html">← Regresar al portafolio</a>
</header>

<main class="admin-contenedor">
    <?php if (isset($_GET["estado"])): ?>
        <p>
            <?php
            if ($_GET["estado"] === "guardado") echo "Mensaje guardado correctamente.";
            if ($_GET["estado"] === "borrado") echo "Mensaje eliminado.";
            if ($_GET["estado"] === "actualizado") echo "Mensaje actualizado correctamente.";
            ?>
        </p>
    <?php endif; ?>

    <h2>Mensajes recibidos</h2>

    <table class="admin-tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$fila["id"] ?></td>
                    <td><?= h($fila["nombre"]) ?></td>
                    <td><?= h($fila["correo"]) ?></td>
                    <td><?= nl2br(h($fila["mensaje"])) ?></td>
                    <td><?= h($fila["fecha"]) ?></td>
                    <td>
                        <a class="accion editar"
                           href="editar.php?id=<?= (int)$fila["id"] ?>">
                            ✏️ Editar
                        </a>

                        <form method="POST"
                              onsubmit="return confirm('¿Quieres borrar este mensaje?');">
                            <input type="hidden" name="borrar_id"
                                   value="<?= (int)$fila["id"] ?>">
                            <button class="accion borrar" type="submit">
                                🗑️ Borrar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Todavía no hay mensajes recibidos.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
<?php $conexion->close(); ?>