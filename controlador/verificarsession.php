<?php
$tiempo_limite = 60; 

if (isset($_SESSION['ultimo_movimiento'])) {
    $inactivo = time() - $_SESSION['ultimo_movimiento'];
    if ($inactivo > $tiempo_limite) {
        session_unset();
        session_destroy();
        header("Location: ?pagina=login&mensaje=sesion_expirada");
        exit;
    }
}

$_SESSION['ultimo_movimiento'] = time(); 



?>
