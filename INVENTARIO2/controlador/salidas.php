<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;	
	exit;
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){
	if(!empty($_POST)){
		$o = new salidas(); 	
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
		if($accion=='listadoclientes'){
			$respuesta = $o->listadoclientes();
			echo json_encode($respuesta);
		}
		elseif($accion=='listadoproductos'){
			$respuesta = $o->listadoproductos();
			echo json_encode($respuesta);
		}
		elseif($accion=='salida'){
			$respuesta = $o->salida($_POST['idcliente'],$_POST['idp'],$_POST['cant'],$_POST['pvp']);
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