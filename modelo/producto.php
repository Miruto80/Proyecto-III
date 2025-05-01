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

    function consultar(){
		$co = $this->conex();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{

			$resultado = $co->query("select * from productos");
			if($resultado){
				$respuesta = '';
				foreach($resultado as $row){
					$respuesta .= "<tr>";
					
					$respuesta .= "<td class='text-center'>{$row['nombre']}</td>";
					$respuesta .= "<td class='text-center'>{$row['descripcion']}</td>";
					$respuesta .= "<td class='text-center'>{$row['marca']}</td>";
					$respuesta .= "<td class='text-center'>{$row['cantidad_mayor']}</td>";
					$respuesta .= "<td class='text-center'>{$row['precio_mayor']}</td>";				
					$respuesta .= "<td class='text-center'>{$row['precio_detal']}</td>";				
					$respuesta .= "<td class='text-center'>{$row['stock_disponible']}</td>";
					$respuesta .= "<td class='text-center'>{$row['stock_maximo']}</td>";
					$respuesta .= "<td class='text-center'>{$row['stock_minimo']}</td>";
					$respuesta .= "<td class='text-center'>{$row['imagen']}</td>";
					$respuesta .= "<td class='text-center'>{$row['estatus']}</td>";
					$respuesta .= "<td class='text-center action-column'>";

					$respuesta .= "<button name='modificar' class='btn btn-primary btn-sm modificar'> 
                    <i class='fas fa-pencil-alt' title='Editar'> </i> 
                   </button>";
					$respuesta .= "<button name='eliminar' class='btn btn-primary btn-sm eliminar'> 
                    <i class='fas fa-trash-alt' title='Eliminar'> </i> 
                   </button>";
					$respuesta .= "</td>";

					$respuesta .= "</tr>";
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  $respuesta;
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  '';
			}
		} catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}









}













?>