<?php
function obtenerListaDeseos($id_persona, $conexion) {
    $stmt = $conexion->prepare("SELECT l.id_lista, p.nombre AS producto, l.fecha_agregado
                                FROM lista_deseos l
                                JOIN productos p ON l.id_producto = p.id_producto
                                WHERE l.id_persona = ?");
    $stmt->execute([$id_persona]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function eliminarDeListaDeseos($id_lista, $conexion) {
    $stmt = $conexion->prepare("DELETE FROM lista_deseos WHERE id_lista = ?");
    return $stmt->execute([$id_lista]);
}

function agregarAPedido($id_persona, $id_producto, $conexion) {
    $stmt = $conexion->prepare("INSERT INTO pedidos (id_persona, id_producto, fecha_pedido) VALUES (?, ?, NOW())");
    return $stmt->execute([$id_persona, $id_producto]);
}
?>
