<?php 

require_once('modelo/conexion.php');

class producto extends Conexion{
 private $conex;
 private $nombre;
 private $descripcion;
 private $marca;
 private $cantidad_mayor;
 private $precio_mayor;
 private $precio_detal;
 private $stock_disponible;
 private $stock_maximo;
 private $stock_minimo;
 private $imagen;
 private $estatus;


function __construct(){
    $this->conex = new Conexion();
    $this->conex = $this->conex->conex();
}



public function registrar(){
	$registro ="INSERT INTO productos(nombre,descripcion,marca,cantidad_mayor,precio_mayor,precio_detal,stock_disponible,stock_maximo,stock_minimo,imagen,estatus)
	VALUES (:nombre,1)";

	$strExec = $this->conex->prepare($registro);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);
	$strExec->bindParam(':nombre',$this->nombre);

	 $resul=$strExec->execute();
	  if ($resul) {
		  $res['respuesta'] = 1;
		   $res['accion'] = 'incluir';
	} else {
		 $res['respuesta'] = 0;
		 $res['accion'] = 'incluir';
	  }

return $res;
} // fin registrar




public function consultar(){

	$registro ="SELECT * FROM productos";
	$consulta = $this->conex->prepare($registro);
	$resul = $consulta->execute();

	$datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
		if ($resul){
			return $datos;
		} else{
			return $res = 0;
		}

} //fin consultar
	
	
	function set_nombre($valor)
		{
			$this->nombre = $valor;
		}
	function set_descripcion($valor)
		{
			$this->descripcion = $valor;
		}
	function set_marca($valor)
		{
			$this->marca = $valor;
		}
	function set_cantidad_mayor($valor)
		{
			$this->cantidad_mayor = $valor;
		}
	function set_precio_mayor($valor)
		{
			$this->precio_mayor = $valor;
		}
	function set_precio_detal($valor)
		{
			$this->precio_detal = $valor;
		}
	function set_stock_disponible($valor)
		{
			$this->stock_disponible = $valor;
		}
	function set_stock_maximo($valor)
		{
			$this->stock_maximo = $valor;
		}
	function set_stock_minimo($valor)
		{
			$this->stock_minimo = $valor;
		}
	function set_imagen($valor)
		{
			$this->imagen = $valor;
		}
	function set_estatus($valor)
		{
			$this->estatus = $valor;
		}
	
	
	
		public function get_nombre()
		{
			return $this->nombre;
		}
		
		public function get_descripcion()
		{
			return $this->descripcion;
		}
		
		public function get_marca()
		{
			return $this->marca;
		}
		
		public function get_cantidad_mayor()
		{
			return $this->cantidad_mayor;
		}
		
		public function get_precio_mayor()
		{
			return $this->precio_mayor;
		}
		
		public function get_precio_detal()
		{
			return $this->precio_detal;
		}
		
		public function get_stock_disponible()
		{
			return $this->stock_disponible;
		}
		
		public function get_stock_maximo()
		{
			return $this->stock_maximo;
		}
		
		public function get_stock_minimo()
		{
			return $this->stock_minimo;
		}
		
		public function get_imagen()
		{
			return $this->imagen;
		}
		
		public function get_estatus()
		{
			return $this->estatus;
		}



}


?>