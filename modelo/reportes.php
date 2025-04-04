<?php
require_once('dompdf/vendor/autoload.php'); 

use Dompdf\Dompdf; 

require_once('modelo/datos.php');

class reportes extends datos{	
	private $nombre_categoria; 
	private $descripcion_categoria;		
	private $nombre; 
	private $descripcion;	
	private $numero_documento; 
	private $direccion; 
	private $correo;
	private $telefono; 
	private $catalogo;		
	private $codigo; 
	private $precio_compra;
	private $precio_venta;
	private $stock_total;
	private $stock_minimo;
	private $marca;
	private $modelo; 
	private $tipo_unidad;
	private $apellido;
	private $cedula; 
	private $nombre_usuario;
	private $tipo_usuario;
	private $fecha;
	private $cliente;
	private $proveedor;
	private $fecha_inicio;
	private $fecha_fin;

	function set_nombre_categoria($valor){
		$this->nombre_categoria = $valor; 
	}

	function set_descripcion_categoria($valor){
		$this->descripcion_categoria = $valor;
	}

	function set_nombre($valor){
		$this->nombre = $valor; 
	}

	function set_descripcion($valor){
		$this->descripcion = $valor;
	}

	function set_numero_documento($valor){
		$this->numero_documento = $valor; 
	}

	function set_direccion($valor){
		$this->direccion = $valor; 
	}

	function set_correo($valor){
		$this->correo = $valor; 
	}

	function set_telefono($valor){
		$this->telefono = $valor; 
	}

	function set_catalogo($valor){
		$this->catalogo = $valor; 
	}

	function set_codigo($valor){
		$this->codigo = $valor; 
	}

	function set_precio_compra($valor){
		$this->precio_compra = $valor;
	}

	function set_precio_venta($valor){
		$this->precio_venta = $valor; 
	}

	function set_stock_total($valor){
		$this->stock_total = $valor; 
	}

	function set_stock_minimo($valor){
		$this->stock_minimo = $valor; 
	}

	function set_marca($valor){
		$this->marca = $valor; 
	}

	function set_modelo($valor){
		$this->modelo = $valor; 
	}

	function set_tipo_unidad($valor){
		$this->tipo_unidad = $valor; 
	}

	function set_apellido($valor){
		$this->apellido = $valor; 
	}

	function set_cedula($valor){
		$this->cedula = $valor; 
	}

	function set_nombre_usuario($valor){
		$this->nombre_usuario = $valor; 
	}

	function set_tipo_usuario($valor){
		$this->tipo_usuario = $valor; 
	}

	function set_fecha($valor){
		$this->fecha = $valor;
	}

	function set_cliente($valor){
		$this->cliente = $valor;
	}

	function set_proveedor($valor){
		$this->proveedor = $valor;
	}

	function set_fecha_inicio($valor){
		$this->fecha_inicio = $valor;
	}

	function set_fecha_fin($valor){
		$this->fecha_fin = $valor;
	}

	function reporte_categorias(){	
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("Select * from categorias where nombre like :nombre and 
				descripcion like :descripcion");
			$resultado->bindValue(':nombre','%'.$this->nombre_categoria.'%');
			$resultado->bindValue(':descripcion','%'.$this->descripcion_categoria.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha.".</p>";	
			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Categorias</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
			$html = $html."<th style='border: solid;'>#</th>";			
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Descripcion</th>";
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['descripcion']."</td>";		 
					$html = $html."</tr>";
					$n++;
				}
			}
			else{	
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";	
		}catch(Exception $e){
		}
		$fecha = date('d-m-y');		
		$pdf = new DOMPDF();

		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteCategorias".$fecha.".pdf", array("Attachment" => false));	
	}


	function reporte_ubicaciones(){	
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("Select * from ubicaciones where nombre like :nombre and 
				descripcion like :descripcion");
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':descripcion','%'.$this->descripcion.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha.".</p>";	
			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Ubicaciones</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
			$html = $html."<th style='border: solid;'>#</th>";			
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Descripcion</th>";
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['descripcion']."</td>";		 
					$html = $html."</tr>";
					$n++;
				}
			}
			else{	
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";	

		}catch(Exception $e){
		}
		$fecha = date('d-m-y');			
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteUbicaciones".$fecha.".pdf", array("Attachment" => false));	
	}	


	function reporte_proveedores(){	
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("Select * from proveedores where 
				nombre like :nombre and  numero_documento like :numero_documento and 
				direccion like :direccion and correo like :correo and
				telefono like :telefono and catalogo like :catalogo");
			$resultado->bindValue(':numero_documento','%'.$this->numero_documento.'%');
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':direccion','%'.$this->direccion.'%');
			$resultado->bindValue(':correo','%'.$this->correo.'%');
			$resultado->bindValue(':telefono','%'.$this->telefono.'%');
			$resultado->bindValue(':catalogo','%'.$this->catalogo.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha.".</p>";	
			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Proveedores</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
			$html = $html."<th style='border: solid;'>#</th>";			
			$html = $html."<th style='border: solid;'>Documento</th>";
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Direccion</th>";
			$html = $html."<th style='border: solid;'>Correo</th>";
			$html = $html."<th style='border: solid;'>Telefono</th>";												
			$html = $html."<th style='border: solid;'>Catalogo</th>";
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['numero_documento']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['direccion']."</td>";
					$html = $html."<td style='border: solid;'>".$f['correo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['telefono']."</td>";
					$html = $html."<td style='border: solid;'>".$f['catalogo']."</td>";		 
					$html = $html."</tr>";
					$n++;
				}
			}
			else{	
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";	

		}catch(Exception $e){
		}
		$fecha = date('d-m-y');		
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteProveedores".$fecha.".pdf", array("Attachment" => false));	
	}


	function reporte_productos(){	
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("Select * from productos where 
				codigo like :codigo and  
				nombre like :nombre and 
				precio_compra like :precio_compra and 
				precio_venta like :precio_venta and
				stock_total like :stock_total and
				stock_minimo like :stock_minimo and  
				marca like :marca and 
				modelo like :modelo and 
				tipo_unidad like :tipo_unidad 

				");
			$resultado->bindValue(':codigo','%'.$this->codigo.'%');
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':precio_compra','%'.$this->precio_compra.'%');
			$resultado->bindValue(':precio_venta','%'.$this->precio_venta.'%');
			$resultado->bindValue(':stock_total','%'.$this->stock_total.'%');
			$resultado->bindValue(':stock_minimo','%'.$this->stock_minimo.'%');
			$resultado->bindValue(':marca','%'.$this->marca.'%');
			$resultado->bindValue(':modelo','%'.$this->modelo.'%');
			$resultado->bindValue(':tipo_unidad','%'.$this->tipo_unidad.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha.".</p>";	
			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Productos</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
			$html = $html."<th style='border: solid;'>#</th>";	
			$html = $html."<th style='border: solid;'>Codigo</th>";
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Precio compra</th>";
			$html = $html."<th style='border: solid;'>Precio venta</th>";
			$html = $html."<th style='border: solid;'>Stock total</th>";
			$html = $html."<th style='border: solid;'>Stock minimo</th>";
			$html = $html."<th style='border: solid;'>Marca</th>";
			$html = $html."<th style='border: solid;'>Modelo</th>";
			$html = $html."<th style='border: solid;'>Tipo unidad</th>";
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['codigo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>$".$f['precio_compra']."</td>";
					$html = $html."<td style='border: solid;'>$".$f['precio_venta']."</td>";
					$html = $html."<td style='border: solid;'>".$f['stock_total']."</td>";
					$html = $html."<td style='border: solid;'>".$f['stock_minimo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['marca']."</td>";
					$html = $html."<td style='border: solid;'>".$f['modelo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['tipo_unidad']."</td>";
					$html = $html."</tr>";
					$n++;
				}
			}
			else{	
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";	

		}catch(Exception $e){
		}
		$fecha = date('d-m-y');		
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteProductos".$fecha.".pdf", array("Attachment" => false));	
	}


	function reporte_clientes(){	
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("Select * from clientes where 
				numero_documento like :numero_documento and 
				nombre like :nombre and  
				apellido like :apellido and
				correo like :correo and
				telefono like :telefono and 				  
				direccion like :direccion ");
			$resultado->bindValue(':numero_documento','%'.$this->numero_documento.'%');
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':apellido','%'.$this->apellido.'%');
			$resultado->bindValue(':correo','%'.$this->correo.'%');
			$resultado->bindValue(':telefono','%'.$this->telefono.'%');
			$resultado->bindValue(':direccion','%'.$this->direccion.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha.".</p>";	
			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Clientes</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
			$html = $html."<th style='border: solid;'>#</th>";			
			$html = $html."<th style='border: solid;'>Documento</th>";
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Apellido</th>";
			$html = $html."<th style='border: solid;'>Correo</th>";
			$html = $html."<th style='border: solid;'>Telefono</th>";												
			$html = $html."<th style='border: solid;'>Direccion</th>";
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['numero_documento']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['apellido']."</td>";
					$html = $html."<td style='border: solid;'>".$f['correo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['telefono']."</td>";
					$html = $html."<td style='border: solid;'>".$f['direccion']."</td>";		 
					$html = $html."</tr>";
					$n++;
				}
			}
			else{	
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";	

		}catch(Exception $e){
		}
		$fecha = date('d-m-y');		
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html(utf8_decode($html));
		$pdf->render();
		$pdf->stream("ReporteClientes".$fecha.".pdf", array("Attachment" => false));	
	}	

	function reporte_usuarios(){	
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("Select * from usuarios where 
				cedula like :cedula and
				nombre like :nombre and
				apellido like :apellido and
				correo like :correo and
				telefono like :telefono and
				nombre_usuario like :nombre_usuario and
				tipo_usuario like :tipo_usuario
				");
			$resultado->bindValue(':cedula','%'.$this->cedula.'%');
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':apellido','%'.$this->apellido.'%');
			$resultado->bindValue(':correo','%'.$this->correo.'%');
			$resultado->bindValue(':telefono','%'.$this->telefono.'%');
			$resultado->bindValue(':nombre_usuario','%'.$this->nombre_usuario.'%');
			$resultado->bindValue(':tipo_usuario','%'.$this->tipo_usuario.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha.".</p>";	
			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Usuarios</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
			$html = $html."<th style='border: solid;'>#</th>";			
			$html = $html."<th style='border: solid;'>Cedula</th>";
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Apellido</th>";
			$html = $html."<th style='border: solid;'>Correo</th>";
			$html = $html."<th style='border: solid;'>Telefono</th>";
			$html = $html."<th style='border: solid;'>Nombre de usuario</th>";
			$html = $html."<th style='border: solid;'>Tipo de usuario</th>";									
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['cedula']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['apellido']."</td>";
					$html = $html."<td style='border: solid;'>".$f['correo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['telefono']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre_usuario']."</td>";
					$html = $html."<td style='border: solid;'>".$f['tipo_usuario']."</td>";								 
					$html = $html."</tr>";
					$n++;
				}
			}
			else{	
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";	

		}catch(Exception $e){
		}
		$fecha = date('d-m-y');		
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteUsuarios".$fecha.".pdf", array("Attachment" => false));	
	}


	function reporte_apartados(){    
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');
			$fecha_actual = date('d-m-y H:i');
			$sql = "SELECT a.id, a.fecha_apartado, 
			c.tipo_documento, c.numero_documento, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
			p.codigo, p.nombre AS producto_nombre, ap.cantidad, ap.precio,
			(ap.cantidad * ap.precio) AS subtotal,
			SUM(ap.cantidad * ap.precio) OVER (PARTITION BY a.id) AS total
			FROM apartados a
			JOIN clientes c ON a.cliente_id = c.id
			JOIN apartados_detalles ap ON a.id = ap.apartado_id
			JOIN productos p ON ap.producto_id = p.id
			WHERE 1=1";

			if(!empty($this->fecha_inicio) && !empty($this->fecha_fin)){
				$sql .= " AND DATE(a.fecha_apartado) BETWEEN :fecha_inicio AND :fecha_fin";
			}
			if(!empty($this->cliente)){
				$sql .= " AND c.numero_documento LIKE :cliente";
			}
			$sql .= " ORDER BY a.fecha_apartado DESC";

			$resultado = $co->prepare($sql);

			if(!empty($this->fecha_inicio) && !empty($this->fecha_fin)){
				$resultado->bindValue(':fecha_inicio', $this->fecha_inicio);
				$resultado->bindValue(':fecha_fin', $this->fecha_fin);
			}
			if(!empty($this->cliente)){
				$resultado->bindValue(':cliente', '%'.$this->cliente.'%');
			}

			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);

			$html = "<html><head></head><body>";
			$html .= "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute; left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";        
			$html .= "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha_actual.".</p>";    
			$html .= "<div style='background-color: #f1c40f; border: solid;'><h2 style='color:#14345a; text-align: center;'>Reporte de Apartados</h2></div>";

			if($fila){
				$currentApartado = null;
				$n = 1;
				
				foreach($fila as $f){
					if ($currentApartado !== $f['id']) {
						if ($currentApartado !== null) {
							$html .= "<tr style='background-color: #f7dc6f;'>";
							$html .= "<td colspan='4' style='text-align: right; border: solid;'><strong>Total:</strong></td>";
							$html .= "<td style='text-align: right; border: solid;'><strong>$" . number_format($prevTotal, 2) . "</strong></td>";
							$html .= "</tr>";
							$html .= "</tbody></table></td>";
							$html .= "</tr>";
							$html .= "</table>";
						}

						$html .= "<table style='width:100%; border: solid; margin-bottom: 20px;'>";
						$html .= "<tr style='background-color: #f7dc6f;'>";
						$html .= "<th style='border: solid; padding: 5px;' colspan='2'>Apartado #" . $n . "</th>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td style='border: solid; padding: 5px;'><strong>Fecha:</strong> " . $f['fecha_apartado'] . "</td>";
						$html .= "<td style='border: solid; padding: 5px;'><strong>Cliente:</strong> " . $f['cliente_nombre'] . " " . $f['cliente_apellido'] . " <strong>Documento: </strong>" . $f['numero_documento'] . "</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td colspan='2' style='padding: 0;'>";
						$html .= "<table style='width:100%; border-collapse: collapse;'>";
						$html .= "<thead style='background-color: #f7dc6f;'>";
						$html .= "<tr>";
						$html .= "<th style='border: solid;'>Codigo</th>";
						$html .= "<th style='border: solid;'>Producto</th>";
						$html .= "<th style='border: solid;'>Cantidad</th>";
						$html .= "<th style='border: solid;'>Precio</th>";
						$html .= "<th style='border: solid;'>Subtotal</th>";
						$html .= "</tr>";
						$html .= "</thead><tbody>";
						
						$currentApartado = $f['id'];
						$prevTotal = $f['total'];
						$n++;
					}
					
					$html .= "<tr>";
					$html .= "<td style='border: solid; text-align: center;'>" . $f['codigo'] . "</td>";
					$html .= "<td style='border: solid;'>" . $f['producto_nombre'] . "</td>";
					$html .= "<td style='border: solid; text-align: center;'>" . $f['cantidad'] . "</td>";
					$html .= "<td style='border: solid; text-align: right;'>$" . number_format($f['precio'], 2) . "</td>";
					$html .= "<td style='border: solid; text-align: right;'>$" . number_format($f['subtotal'], 2) . "</td>";
					$html .= "</tr>";
				}
				
				if ($currentApartado !== null) {
					$html .= "<tr style='background-color: #f7dc6f;'>";
					$html .= "<td colspan='4' style='text-align: right; border: solid;'><strong>Total:</strong></td>";
					$html .= "<td style='text-align: right; border: solid;'><strong>$" . number_format($prevTotal, 2) . "</strong></td>";
					$html .= "</tr>";
					$html .= "</tbody></table></td>";
					$html .= "</tr>";
					$html .= "</table>";
				}
			}
			
			$html .= "</body></html>";    

		}catch(Exception $e){
		}
		
		$fecha = date('d-m-y');        
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteApartados".$fecha.".pdf", array("Attachment" => false));    
	}



	function reporte_entradas(){    
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');
			$fecha_actual = date('d-m-y H:i');
			
			$sql = "SELECT e.id, e.fecha_entrada, e.nota_entrega,
			pv.tipo_documento, pv.numero_documento, pv.nombre AS proveedor_nombre,
			p.codigo, p.nombre AS producto_nombre, ed.cantidad, ed.precio,
			(ed.cantidad * ed.precio) AS subtotal,
			SUM(ed.cantidad * ed.precio) OVER (PARTITION BY e.id) AS total
			FROM entradas e
			JOIN proveedores pv ON e.proveedor_id = pv.id
			JOIN entradas_detalles ed ON e.id = ed.entrada_id
			JOIN productos p ON ed.producto_id = p.id
			WHERE 1=1";

			if(!empty($this->fecha_inicio) && !empty($this->fecha_fin)){
				$sql .= " AND DATE(e.fecha_entrada) BETWEEN :fecha_inicio AND :fecha_fin";
			}

			if(!empty($this->proveedor)){
				$sql .= " AND pv.numero_documento LIKE :proveedor";
			}
			$sql .= " ORDER BY e.fecha_entrada DESC";

			$resultado = $co->prepare($sql);
			
			if(!empty($this->fecha_inicio) && !empty($this->fecha_fin)){
				$resultado->bindValue(':fecha_inicio', $this->fecha_inicio);
				$resultado->bindValue(':fecha_fin', $this->fecha_fin);
			}
			if(!empty($this->proveedor)){
				$resultado->bindValue(':proveedor', '%'.$this->proveedor.'%');
			}
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html .= "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute; left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";        
			$html .= "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha_actual.".</p>";    
			$html .= "<div style='background-color: #f1c40f; border: solid;'><h2 style='color:#14345a; text-align: center;'>Reporte de Entradas</h2></div>";
			if($fila){
				$currentEntrada = null;
				$n = 1;
				foreach($fila as $f){
					if ($currentEntrada !== $f['id']) {
						if ($currentEntrada !== null) {
							$html .= "<tr style='background-color: #f7dc6f;'>";
							$html .= "<td colspan='4' style='text-align: right; border: solid;'><strong>Total:</strong></td>";
							$html .= "<td style='text-align: right; border: solid;'><strong>$" . number_format($prevTotal, 2) . "</strong></td>";
							$html .= "</tr>";
							$html .= "</tbody></table></td>";
							$html .= "</tr>";
							$html .= "</table>";
						}
						$html .= "<table style='width:100%; border: solid; margin-bottom: 20px;'>";
						$html .= "<tr style='background-color: #f7dc6f;'>";
						$html .= "<th style='border: solid; padding: 5px;' colspan='2'>Entrada #" . $n . "</th>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td style='border: solid; padding: 5px;'><strong>Fecha:</strong> " . $f['fecha_entrada'] . "</td>";
						$html .= "<td style='border: solid; padding: 5px;'><strong>Proveedor:</strong> " . $f['proveedor_nombre'] . " <strong>Documento: </strong>" . $f['numero_documento'] . "</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td colspan='2' style='padding: 0;'>";
						$html .= "<table style='width:100%; border-collapse: collapse;'>";
						$html .= "<thead style='background-color: #f7dc6f;'>";
						$html .= "<tr>";
						$html .= "<th style='border: solid;'>Codigo</th>";
						$html .= "<th style='border: solid;'>Producto</th>";
						$html .= "<th style='border: solid;'>Cantidad</th>";
						$html .= "<th style='border: solid;'>Precio</th>";
						$html .= "<th style='border: solid;'>Subtotal</th>";
						$html .= "</tr>";
						$html .= "</thead><tbody>";
						$currentEntrada = $f['id'];
						$prevTotal = $f['total'];
						$n++;
					}
					
					$html .= "<tr>";
					$html .= "<td style='border: solid; text-align: center;'>" . $f['codigo'] . "</td>";
					$html .= "<td style='border: solid;'>" . $f['producto_nombre'] . "</td>";
					$html .= "<td style='border: solid; text-align: center;'>" . $f['cantidad'] . "</td>";
					$html .= "<td style='border: solid; text-align: right;'>$" . number_format($f['precio'], 2) . "</td>";
					$html .= "<td style='border: solid; text-align: right;'>$" . number_format($f['subtotal'], 2) . "</td>";
					$html .= "</tr>";
				}
				
				if ($currentEntrada !== null) {
					$html .= "<tr style='background-color: #f7dc6f;'>";
					$html .= "<td colspan='4' style='text-align: right; border: solid;'><strong>Total:</strong></td>";
					$html .= "<td style='text-align: right; border: solid;'><strong>$" . number_format($prevTotal, 2) . "</strong></td>";
					$html .= "</tr>";
					$html .= "</tbody></table></td>";
					$html .= "</tr>";
					$html .= "</table>";
				}
			}
			
			$html .= "</body></html>";    

		}catch(Exception $e){
		}
		$fecha = date('d-m-y');        
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteEntradas".$fecha.".pdf", array("Attachment" => false));    
	}

	function reporte_salidas(){    
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');
			$fecha_actual = date('d-m-y H:i');
			
			$sql = "SELECT s.id, s.fecha_salida, 
			c.tipo_documento, c.numero_documento, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
			p.codigo, p.nombre AS producto_nombre, sp.cantidad, sp.precio,
			(sp.cantidad * sp.precio) AS subtotal,
			SUM(sp.cantidad * sp.precio) OVER (PARTITION BY s.id) AS total
			FROM salidas s
			JOIN clientes c ON s.cliente_id = c.id
			JOIN salidas_detalles sp ON s.id = sp.salida_id
			JOIN productos p ON sp.producto_id = p.id
			WHERE 1=1";

			if(!empty($this->fecha_inicio) && !empty($this->fecha_fin)){
				$sql .= " AND DATE(s.fecha_salida) BETWEEN :fecha_inicio AND :fecha_fin";
			}
			if(!empty($this->cliente)){
				$sql .= " AND c.numero_documento LIKE :cliente";
			}
			$sql .= " ORDER BY s.fecha_salida DESC";

			$resultado = $co->prepare($sql);
			
			if(!empty($this->fecha_inicio) && !empty($this->fecha_fin)){
				$resultado->bindValue(':fecha_inicio', $this->fecha_inicio);
				$resultado->bindValue(':fecha_fin', $this->fecha_fin);
			}

			if(!empty($this->cliente)){
				$resultado->bindValue(':cliente', '%'.$this->cliente.'%');
			}
			
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);

			$html = "<html><head></head><body>";
			$html .= "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute; left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>FERRETERIA Y REPUESTOS YSDAEL 24, C.A<br>J-504886165</h2>
			</div>";        
			$html .= "<p style='color: #14345a;'><strong>Direccion:</strong> Avenida Florencio Jimenez Km 8 y 1/2 Via Quibor. Barrio Santa Rosalia, Barquisimeto, Lara<br><strong>Telefono:</strong> 0414-5290300<br><strong>Fecha:</strong> ".$fecha_actual.".</p>";    
			$html .= "<div style='background-color: #f1c40f; border: solid;'><h2 style='color:#14345a; text-align: center;'>Reporte de Salidas</h2></div>";

			if($fila){
				$currentSalida = null;
				$n = 1;
				
				foreach($fila as $f){
					if ($currentSalida !== $f['id']) {
						if ($currentSalida !== null) {
							$html .= "<tr style='background-color: #f7dc6f;'>";
							$html .= "<td colspan='4' style='text-align: right; border: solid;'><strong>Total:</strong></td>";
							$html .= "<td style='text-align: right; border: solid;'><strong>$" . number_format($prevTotal, 2) . "</strong></td>";
							$html .= "</tr>";
							$html .= "</tbody></table></td>";
							$html .= "</tr>";
							$html .= "</table>";
						}

						$html .= "<table style='width:100%; border: solid; margin-bottom: 20px;'>";
						$html .= "<tr style='background-color: #f7dc6f;'>";
						$html .= "<th style='border: solid; padding: 5px;' colspan='2'>Salida #" . $n . "</th>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td style='border: solid; padding: 5px;'><strong>Fecha:</strong> " . $f['fecha_salida'] . "</td>";
						$html .= "<td style='border: solid; padding: 5px;'><strong>Cliente:</strong> " . $f['cliente_nombre'] . " " . $f['cliente_apellido'] . " <strong>Documento: </strong>" . $f['numero_documento'] . "</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td colspan='2' style='padding: 0;'>";
						$html .= "<table style='width:100%; border-collapse: collapse;'>";
						$html .= "<thead style='background-color: #f7dc6f;'>";
						$html .= "<tr>";
						$html .= "<th style='border: solid;'>Codigo</th>";
						$html .= "<th style='border: solid;'>Producto</th>";
						$html .= "<th style='border: solid;'>Cantidad</th>";
						$html .= "<th style='border: solid;'>Precio</th>";
						$html .= "<th style='border: solid;'>Subtotal</th>";
						$html .= "</tr>";
						$html .= "</thead><tbody>";
						
						$currentSalida = $f['id'];
						$prevTotal = $f['total'];
						$n++;
					}
					
					$html .= "<tr>";
					$html .= "<td style='border: solid; text-align: center;'>" . $f['codigo'] . "</td>";
					$html .= "<td style='border: solid;'>" . $f['producto_nombre'] . "</td>";
					$html .= "<td style='border: solid; text-align: center;'>" . $f['cantidad'] . "</td>";
					$html .= "<td style='border: solid; text-align: right;'>$" . number_format($f['precio'], 2) . "</td>";
					$html .= "<td style='border: solid; text-align: right;'>$" . number_format($f['subtotal'], 2) . "</td>";
					$html .= "</tr>";
				}
				
				if ($currentSalida !== null) {
					$html .= "<tr style='background-color: #f7dc6f;'>";
					$html .= "<td colspan='4' style='text-align: right; border: solid;'><strong>Total:</strong></td>";
					$html .= "<td style='text-align: right; border: solid;'><strong>$" . number_format($prevTotal, 2) . "</strong></td>";
					$html .= "</tr>";
					$html .= "</tbody></table></td>";
					$html .= "</tr>";
					$html .= "</table>";
				}
			}
			
			$html .= "</body></html>";    

		}catch(Exception $e){
		}
		
		$fecha = date('d-m-y');        
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteSalidas".$fecha.".pdf", array("Attachment" => false));    
	}
}
?>