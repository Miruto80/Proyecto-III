<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito Vacío</title>
    <?php include 'head.php'; ?> 
    <?php include 'head_catalogo.php' ?>
</head>
<body>
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Carrito vacío',
        text: 'No puedes acceder a esta página porque tu carrito está vacío.',
        confirmButtonText: 'Volver al catálogo'
    }).then(() => {
        window.location.href = '?pagina=catalogo';
    });
</script>
</body>
</html>