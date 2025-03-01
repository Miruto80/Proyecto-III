<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}
else{
	require_once('modelo/reportes.php');
// Verifica si el usuario es un administrador
	if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
	header("Location: ?pagina=principal"); // Redirige si no es administrador
	exit;
}
}
if(is_file("vista/".$pagina.".php")){
	if(isset($_POST['reporte_categorias'])){
		$o = new reportes();
		$o->set_nombre_categoria($_POST['nombre_categoria']);
		$o->set_descripcion_categoria($_POST['descripcion_categoria']);
		$o->reporte_categorias();
	}
	else if(isset($_POST['reporte_ubicaciones'])){
		$o = new reportes();
		$o->set_nombre($_POST['nombre']);
		$o->set_descripcion($_POST['descripcion']);
		$o->reporte_ubicaciones();
	}	
	else if(isset($_POST['reporte_proveedores'])){
		$o = new reportes();
		$o->set_numero_documento($_POST['numero_documento']);
		$o->set_nombre($_POST['nombre']);		  
		$o->set_direccion($_POST['direccion']);
		$o->set_correo($_POST['correo']);
		$o->set_telefono($_POST['telefono']);	
		$o->set_catalogo($_POST['catalogo']);		  	  		  		  		  
		$o->reporte_proveedores();
	}
	else if(isset($_POST['reporte_productos'])){
		$o = new reportes();
		$o->set_codigo($_POST['codigo']);
		$o->set_nombre($_POST['nombre']);		  
		$o->set_precio_compra($_POST['precio_compra']);
		$o->set_precio_venta($_POST['precio_venta']);
		$o->set_stock_total($_POST['stock_total']);	
		$o->set_stock_minimo($_POST['stock_minimo']);
		$o->set_marca($_POST['marca']);	
		$o->set_modelo($_POST['modelo']);
		$o->set_tipo_unidad($_POST['tipo_unidad']);			  				  		  	  		  		  		  
		$o->reporte_productos();
	}
	else if(isset($_POST['reporte_clientes'])){
		$o = new reportes();
		$o->set_numero_documento($_POST['numero_documento']);
		$o->set_nombre($_POST['nombre']);		  
		$o->set_apellido($_POST['apellido']);
		$o->set_correo($_POST['correo']);
		$o->set_telefono($_POST['telefono']);	
		$o->set_direccion($_POST['direccion']);		  	  		  		  		  
		$o->reporte_clientes();
	}
	else if(isset($_POST['reporte_usuarios'])){
		$o = new reportes();
		$o->set_cedula($_POST['cedula']);
		$o->set_nombre($_POST['nombre']);
		$o->set_apellido($_POST['apellido']);
		$o->set_correo($_POST['correo']);
		$o->set_telefono($_POST['telefono']);
		$o->set_nombre_usuario($_POST['nombre_usuario']);
		$o->set_tipo_usuario($_POST['tipo_usuario']);		  		  		 		   		  		  		 	  		  
		$o->reporte_usuarios();
	}	  	 	  	
	else if(isset($_POST['reporte_apartados'])){
		$o = new reportes();
		if(!empty($_POST['fecha_inicio'])) {
			$o->set_fecha_inicio($_POST['fecha_inicio']);
		}
		if(!empty($_POST['fecha_fin'])) {
			$o->set_fecha_fin($_POST['fecha_fin']);
		}
		if(!empty($_POST['cliente'])) {
			$o->set_cliente($_POST['cliente']);
		}
		$o->reporte_apartados();
	}

	else if(isset($_POST['reporte_salidas'])){
		$o = new reportes();
		if(!empty($_POST['fecha_inicio'])) {
			$o->set_fecha_inicio($_POST['fecha_inicio']);
		}
		if(!empty($_POST['fecha_fin'])) {
			$o->set_fecha_fin($_POST['fecha_fin']);
		}
		if(!empty($_POST['cliente'])) {
			$o->set_cliente($_POST['cliente']);
		}
		$o->reporte_salidas();
	}
	
	else if(isset($_POST['reporte_entradas'])){
		$o = new reportes();
		if(!empty($_POST['fecha_inicio'])) {
			$o->set_fecha_inicio($_POST['fecha_inicio']);
		}
		if(!empty($_POST['fecha_fin'])) {
			$o->set_fecha_fin($_POST['fecha_fin']);
		}
		if(!empty($_POST['proveedor'])) {
			$o->set_proveedor($_POST['proveedor']);
		}
		$o->reporte_entradas();
	}	
	require_once("vista/".$pagina.".php"); 
}
else{
	echo "pagina en construccion";
}
?>