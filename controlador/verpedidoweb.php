<?php  
     session_start();
     require_once 'modelo/verpedidoweb.php';
     $nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
     $apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 
     $nombreCompleto = trim($nombre . " " . $apellido);
     
     $sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);
     
       
    if (empty($_SESSION["id"])){
        header("location:?pagina=login");
        exit;
    }
    
   
    
    $salida = new VentaWeb();
    $metodos_pago = $salida->obtenerMetodosPago();
    $metodos_entrega = $salida->obtenerMetodosEntrega();
    $total = 0;
    $carrito = $_SESSION['carrito'] ?? [];
    
    
if ($sesion_activa) {
     if($_SESSION["nivel_rol"] == 1) { 
      require_once 'vista/tienda/verpedidoweb.php';
    } else{
        header('Location: ?pagina=catalogo');
    } 
} else {
    header('Location: ?pagina=catalogo');
    exit;
}
?>