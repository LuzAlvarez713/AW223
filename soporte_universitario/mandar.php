<?php

require_once ("conexion_soporte.php");

$id_usuario = $_POST['usuario_id'];
$id_equipo = $_POST['equipo_id'];
$problema = $_POST['descripcion'];
$estado = "No resuelto";

$sqlInsert = "INSERT INTO solicitudes (id_usuario, id_equipo, problema, estado) VALUES (?, ?, ?, ?)";;

$stmt = $conexion->prepare($sqlInsert);
$stmt->bind_param("ssss", $id_usuario, $id_equipo, $problema, $estado); 

if ($stmt->execute()) {
    header("Location:solicitudes.php?estadomod=exito");
} else {
    header("Location:solicitudes.php?estadomod=error");
}