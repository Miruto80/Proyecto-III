<?php 

require_once('modelo/conexion.php');
require_once('modelo/categoria.php');

class producto extends Conexion{
 private $conex1;
 private $conex2;
 private $objcategoria;
 private $id_producto;
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
 private $categoria;
 private $estatus;

function __construct(){
    parent::__construct();

    $this->conex1 = $this->getConex1();
 	$this->conex2 = $this->getConex2();
	
	$this->objcategoria = new categoria();
}

public function registrarBitacora($id_persona, $accion, $descripcion) {
    $consulta = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                 VALUES (:accion, NOW(), :descripcion, :id_persona)";
    
    $strExec = $this->conex1->prepare($consulta);
    $strExec->bindParam(':accion', $accion);
    $strExec->bindParam(':descripcion', $descripcion);
    $strExec->bindParam(':id_persona', $id_persona);
    
    return $strExec->execute(); // Devuelve true si la inserción fue exitosa
}

public function verificarProductoExistente($nombre, $marca) {
    $consulta = "SELECT COUNT(*) FROM productos WHERE LOWER(nombre) = LOWER(:nombre) AND LOWER(marca) = LOWER(:marca) AND estatus = 1";
    $strExec = $this->conex1->prepare($consulta);
    $strExec->bindParam(':nombre', $nombre);
    $strExec->bindParam(':marca', $marca);
    $strExec->execute();
    return $strExec->fetchColumn() > 0;
}

public function registrar(){
    // Verificar si el producto ya existe
    if ($this->verificarProductoExistente($this->nombre, $this->marca)) {
        return ['respuesta' => 0, 'accion' => 'incluir', 'error' => 'Ya existe un producto con el mismo nombre y marca'];
    }

    $registro ="INSERT INTO productos(nombre,descripcion,marca,cantidad_mayor,precio_mayor,precio_detal,stock_disponible,stock_maximo,stock_minimo,imagen,id_categoria,estatus)
    VALUES (:nombre,:descripcion,:marca,:cantidad_mayor,:precio_mayor,:precio_detal,0,:stock_maximo,:stock_minimo,:imagen,:id_categoria,1)";

    $strExec = $this->conex1->prepare($registro);
    $strExec->bindParam(':nombre',$this->nombre);
    $strExec->bindParam(':descripcion',$this->descripcion);
    $strExec->bindParam(':marca',$this->marca);
    $strExec->bindParam(':cantidad_mayor',$this->cantidad_mayor);
    $strExec->bindParam(':precio_mayor',$this->precio_mayor);
    $strExec->bindParam(':precio_detal',$this->precio_detal);
    $strExec->bindParam(':stock_maximo',$this->stock_maximo);
    $strExec->bindParam(':stock_minimo',$this->stock_minimo);
    $strExec->bindParam(':imagen',$this->imagen);
    $strExec->bindParam(':id_categoria', $this->categoria);

    $resul = $strExec->execute();

    if ($resul) {
        $res['respuesta'] = 1;
        $res['accion'] = 'incluir';
    } else {
        $res['respuesta'] = 0;
        $res['accion'] = 'incluir';
    }

    return $res;
}





public function consultar() {
    $registro = "
        SELECT 
            productos.*, 
            categoria.nombre AS nombre_categoria 
        FROM 
            productos
        INNER JOIN 
            categoria ON productos.id_categoria = categoria.id_categoria
		WHERE 
            productos.estatus IN (1,2)
    ";

    $consulta = $this->conex1->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if ($resul) {
        return $datos;
    } else {
        return 0;
    }
}

public function modificar() {
    $registro = "UPDATE productos SET 
        nombre = :nombre,
        descripcion = :descripcion,
        marca = :marca,
        cantidad_mayor = :cantidad_mayor,
        precio_mayor = :precio_mayor,
        precio_detal = :precio_detal,
        stock_maximo = :stock_maximo,
        stock_minimo = :stock_minimo,
        imagen = :imagen,
        id_categoria = :id_categoria
        WHERE id_producto = :id_producto";

    $strExec = $this->conex1->prepare($registro);
    $strExec->bindParam(':id_producto', $this->id_producto);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':descripcion', $this->descripcion);
    $strExec->bindParam(':marca', $this->marca);
    $strExec->bindParam(':cantidad_mayor', $this->cantidad_mayor);
    $strExec->bindParam(':precio_mayor', $this->precio_mayor);
    $strExec->bindParam(':precio_detal', $this->precio_detal);
    $strExec->bindParam(':stock_maximo', $this->stock_maximo);
    $strExec->bindParam(':stock_minimo', $this->stock_minimo);
    $strExec->bindParam(':imagen', $this->imagen);
    $strExec->bindParam(':id_categoria', $this->categoria);

    $resul = $strExec->execute();

    return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
}


public function eliminar() {
    $registro = "UPDATE productos SET estatus = 0 WHERE id_producto = :id_producto";
    $strExec = $this->conex1->prepare($registro);
    $strExec->bindParam(':id_producto', $this->id_producto);
    $resul = $strExec->execute();
    return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
}

public function cambiarEstatusProducto($id_producto, $estatus_actual) {
    $nuevo_estatus = ($estatus_actual == 2) ? 1 : 2; // Alternar estado

    $query = "UPDATE productos SET estatus = :nuevo_estatus WHERE id_producto = :id_producto";
    $strExec = $this->conex1->prepare($query);
    $strExec->bindParam(':nuevo_estatus', $nuevo_estatus, PDO::PARAM_INT);
    $strExec->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $resul = $strExec->execute();

    return $resul ? ['respuesta' => 1, 'accion' => 'cambiarEstatus', 'nuevo_estatus' => $nuevo_estatus] : ['respuesta' => 0, 'accion' => 'cambiarEstatus'];
}

	
public function obtenerCategoria() {
	return $this->objcategoria->consultar();
} 



function set_id_producto($valor)
{
	$this->id_producto = $valor;
}
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

		
		public function set_Categoria($categoria){
			$this->categoria=$categoria;
		}
		


		public function get_Categoria(){
			return $this->categoria;
		}
		public function get_Id_producto(){
			return $this->id_producto;
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