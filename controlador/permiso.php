<?php
function tieneAcceso($moduloId, $accion) {
    if (empty($_SESSION['permisos'])) return false;

    foreach ($_SESSION['permisos'] as $permiso) {
        if (
            $permiso['id_modulo'] == $moduloId &&
            $permiso['accion'] === $accion &&
            $permiso['estado'] == 1
        ) {
            return true;
        }
    }
    return false;
}