<?php
require_once('../modelo/registrocliente.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registrocliente = new RegistroCliente();
    $registrocliente->setDatos(
        $_POST['cedula'],
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['correo'],
        $_POST['telefono'],
        $_POST['clave']
    );

    $resultado = $registrocliente->registrarCliente();

    if ($resultado['respuesta'] === 1) {
        $_SESSION['message'] = [
            'title' => '¡Registrado!',
            'text' => 'El cliente ha sido registrado exitosamente.',
            'icon' => 'success'
        ];
    } else {
        $_SESSION['message'] = [
            'title' => 'Error',
            'text' => 'No se pudo registrar el cliente.',
            'icon' => 'error'
        ];
    }

    header("Location: index.php?pagina=login");
    exit();
}
