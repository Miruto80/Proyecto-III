<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;	
	exit;
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){
	if(!empty($_POST)){
		$o = new entradas();
		$accion = $_POST['accion'];
		if($accion=='consultar'){
			echo  json_encode($o->consultar()); 
		}
		else{		  
			if($accion=='incluir'){
				$o->set_descripcion($_POST['descripcion']); 
				if($accion == 'incluir'){
					$o->set_nombre($_POST['nombre']); 
					echo json_encode($o->incluir()); 
				} 	
			}
		}
		if($accion=='listadoproveedores'){
			$respuesta = $o->listadoproveedores();
			echo json_encode($respuesta);
		}
		elseif($accion=='listadoproductos'){
			$respuesta = $o->listadoproductos();
			echo json_encode($respuesta);
		}
		elseif($accion=='entrada'){
			$nota_ruta = null;
			
			if (isset($_FILES['nota_entrega']) && $_FILES['nota_entrega']['error'] == 0) {
				$nota_nombre = uniqid() . '_' . $_FILES['nota_entrega']['name'];
				$nota_ruta = 'otros/img/entradas/' . $nota_nombre;
				move_uploaded_file($_FILES['nota_entrega']['tmp_name'], $nota_ruta);
			}
			
			$respuesta = $o->entrada(
				$_POST['idcliente'],
				$_POST['idp'],
				$_POST['cant'],
				$_POST['pcp'],
				$nota_ruta
			);
			echo json_encode($respuesta);
		}
		exit; 
	}
	require_once("vista/".$pagina.".php"); 
}
else{
	echo "pagina en construccion";
}
?>