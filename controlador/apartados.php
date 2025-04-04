<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;	
	exit;
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){
	if(!empty($_POST)){
		$o = new apartados(); 	
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
		elseif($accion=='apartar'){
			$respuesta = $o->apartar($_POST['idcliente'],$_POST['idp'],$_POST['cant'],$_POST['pvp']);
			echo json_encode($respuesta);
		}
		elseif($accion == 'transferir_a_salida'){
			$respuesta = $o->transferir_a_salida($_POST['apartado_id']);
			echo json_encode($respuesta);
		}
		elseif($accion == 'cancelar_apartado'){
			$respuesta = $o->cancelar_apartado($_POST['apartado_id']);
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