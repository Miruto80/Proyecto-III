<?php 
require_once("comunes/encabezado.php"); //Incluye el encabezado común 
require_once('comunes/menu.php'); //Incluye el menú común 
?> 
<div class="container">
	<h1><i class="bi bi-file-text-fill"></i> Reportes</h1> <!-- Título de la página -->
	<p style="text-align:justify;">"El módulo de Reportes te permite generar informes detallados sobre productos, clientes, usuarios y movimientos (apartados, entradas y salidas). Utiliza los filtros disponibles para personalizar la información según tus necesidades. Los reportes en PDF te ayudarán a analizar el desempeño del negocio y tomar decisiones informadas."</p>


	<div class="container"> 
		
		<form method="post" action="" id="f" target="_blank">
			<div class="row">
				<div class="col-12 col-lg-6" style="border: 1px solid grey;">
					<hr/>	
					<h4>Reporte de Categorías</h4>
					<div class="row">
						<div class="col-12 col-lg-6">
							<label for="nombre_categoria">Nombre</label>
							<input class="form-control" type="text" id="nombre_categoria" name="nombre_categoria" placeholder="Nombre de la categoría..."/>
							<span id="snombre_categoria"></span>
						</div>
						<div class="col-12 col-lg-6">
							<label for="descripcion_categoria">Descripción</label>
							<input class="form-control" type="text" id="descripcion_categoria" name="descripcion_categoria" placeholder="Descripción de la categoría..."/>
							<span id="sdescripcion_categoria"></span>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-7 col-lg-4">
							<button type="submit" class="btn btn-warning" id="reporte_categorias" name="reporte_categorias">GENERAR PDF</button>
						</div>
					</div>
					<hr/>
				</div>
				<div class="col-12 col-lg-6 " style="border: 1px solid grey;">
					<hr/>
					<h4>Reporte de Ubicaciones</h4>
					<div class="row">
						<div class="col-12 col-lg-6">
							<label for="nombre">Nombre</label>
							<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre de la ubicación..."/>
							<span id="snombre"></span>
						</div>
						<div class="col-12 col-lg-6">
							<label for="descripcion">Descripción</label>
							<input class="form-control" type="text" id="descripcion" name="descripcion" placeholder="Descripción de la ubicación..."/>
							<span id="sdescripcion"></span>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-7 col-lg-4">
							<button type="submit" class="btn btn-warning" id="reporte_ubicaciones" name="reporte_ubicaciones">GENERAR PDF</button>
						</div>
					</div>
					<hr/>
				</div>

			</div>
		</form>		
	</div> 


	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Proveedores</h4>

				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="numero_documento">Documento</label>
						<input class="form-control" type="text" id="numero_documento" name="numero_documento"  placeholder="Numero de documento..."/>
						<span id="snumero_documento"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="nombre">Nombre</label>
						<input class="form-control" type="text" id="nombre" name="nombre"  placeholder="Nombre del proveedor..."/>
						<span id="snombre"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="direccion">Dirección</label>
						<input class="form-control" type="text" id="direccion" name="direccion"  placeholder="Dirección del proveedor..."/>
						<span id="sdireccion"></span>
					</div>					
				</div>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="correo">Correo</label>
						<input class="form-control" type="text" id="correo" name="correo"  placeholder="Correo del proveedor..."/>
						<span id="scorreo"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="telefono">Teléfono</label>
						<input class="form-control" type="text" id="telefono" name="telefono"  placeholder="teléfono del proveedor..."/>
						<span id="stelefono"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="catalogo">Catálogo</label>
						<input class="form-control" type="text" id="catalogo" name="catalogo"  placeholder="Catálogo de productos..."/>
						<span id="scatalogo"></span>
					</div>					
				</div>
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_proveedores" name="reporte_proveedores">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div>  

	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Productos</h4>
				<div class="row">
					<div class="col-12 col-lg-3">
						<label for="codigo">Código</label>
						<input class="form-control" type="text" id="codigo" name="codigo" placeholder="Código del producto..."/>
						<span id="scodigo"></span>
					</div>
					<div class="col-12 col-lg-3">
						<label for="nombre">Nombre</label>
						<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre del producto..."/>
						<span id="snombre"></span>
					</div>
					<div class="col-12 col-lg-3">
						<label for="precio_compra">Precio compra</label>
						<input class="form-control" type="text" id="precio_compra" name="precio_compra" placeholder="Precio de compra..."/>
						<span id="sprecio_compra"></span>
					</div>	
					<div class="col-12 col-lg-3">
						<label for="precio_venta">Precio venta</label>
						<input class="form-control" type="text" id="precio_venta" name="precio_venta" placeholder="Precio de venta..."/>
						<span id="sprecio_venta"></span>
					</div>									
				</div>
				<div class="row">
					<div class="col-12 col-lg-3">
						<label for="stock_total">Stock o existencia</label>
						<input class="form-control" type="text" id="stock_total" name="stock_total" placeholder="Existenca del producto..."/>
						<span id="sstock_total"></span>
					</div>
					<div class="col-12 col-lg-3">
						<label for="stock_minimo">Stock minimo</label>
						<input class="form-control" type="text" id="stock_minimo" name="stock_minimo" placeholder="stock minimo del producto..."/>
						<span id="sstock_minimo"></span>
					</div>
					<div class="col-12 col-lg-3">
						<label for="marca">Marca</label>
						<input class="form-control" type="text" id="marca" name="marca" placeholder="Marca del producto..."/>
						<span id="smarca"></span>
					</div>
					<div class="col-12 col-lg-3">
						<label for="modelo">Modelo</label>
						<input class="form-control" type="text" id="modelo" name="modelo" placeholder="Modelo del producto..."/>
						<span id="smodelo"></span>
					</div>										
				</div>
				<div class="row">
					<div class="col-col-12 col-lg-3">
						<label for="tipo_unidad">Presentación del producto</label>
						<input class="form-control" type="text" id="tipo_unidad" name="tipo_unidad" placeholder="Presentación del producto..."/>
						<span id="stipo_unidad"></span>
					</div>											
				</div>											
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_productos" name="reporte_productos">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div> 

	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Clientes</h4>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="numero_documento">Documento</label>
						<input class="form-control" type="text" id="numero_documento" name="numero_documento"  placeholder="Numero de documento..."/>
						<span id="snumero_documento"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="nombre">Nombre</label>
						<input class="form-control" type="text" id="nombre" name="nombre"  placeholder="Nombre del cliente..."/>
						<span id="snombre"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="apellido">Apellido</label>
						<input class="form-control" type="text" id="apellido" name="apellido"  placeholder="Apellido del cliente..."/>
						<span id="sapellido"></span>
					</div>					
				</div>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="correo">Correo</label>
						<input class="form-control" type="text" id="correo" name="correo"  placeholder="Correo del cliente..."/>
						<span id="scorreo"></span>
					</div>					
					<div class="col-12 col-lg-4">
						<label for="telefono">Teléfono</label>
						<input class="form-control" type="text" id="telefono" name="telefono"  placeholder="teléfono del cliente..."/>
						<span id="stelefono"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="direccion">Dirección</label>
						<input class="form-control" type="text" id="direccion" name="direccion"  placeholder="Dirección del cliente..."/>
						<span id="sdireccion"></span>
					</div>										
				</div>
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_clientes" name="reporte_clientes">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div> 

	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Usuarios</h4>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="cedula">Cedula</label>
						<input class="form-control" type="text" id="cedula" name="cedula"  placeholder="Numero de cedula..."/>
						<span id="scedula"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="nombre">Nombre</label>
						<input class="form-control" type="text" id="nombre" name="nombre"  placeholder="Nombre del usuario..."/>
						<span id="snombre"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="apellido">Apellido</label>
						<input class="form-control" type="text" id="apellido" name="apellido"  placeholder="Apellido del usuario..."/>
						<span id="sapellido"></span>
					</div>					
				</div>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="correo">Correo</label>
						<input class="form-control" type="text" id="correo" name="correo"  placeholder="Correo del usuario..."/>
						<span id="scorreo"></span>
					</div>					
					<div class="col-12 col-lg-4">
						<label for="telefono">Teléfono</label>
						<input class="form-control" type="text" id="telefono" name="telefono"  placeholder="teléfono del usuario..."/>
						<span id="stelefono"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="nombre_usuario">Nombre de usuario</label>
						<input class="form-control" type="text" id="nombre_usuario" name="nombre_usuario"  placeholder="Nombre de usuario..."/>
						<span id="snombre_usuario"></span>
					</div>										
				</div>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="tipo_usuario">Tipo de usuario</label>
						<select class="form-control" id="tipo_usuario" name="tipo_usuario" >
							<option value="" selected>Todo tipo de usuario</option>
							<option value="administrador">Administrador</option>
							<option value="usuario">Usuario</option>										
						</select>
						<span id="stipo_usuario"></span>
					</div>
				</div>				
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_usuarios" name="reporte_usuarios">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div> 

	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Apartados</h4>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="fecha_inicio">Fecha inicial</label>
						<input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" />
						<span id="sfecha_inicio"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="fecha_fin">Fecha final</label>
						<input class="form-control" type="date" id="fecha_fin" name="fecha_fin" />
						<span id="sfecha_fin"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="cliente">Cliente</label>
						<input class="form-control" type="text" id="cliente" name="cliente" placeholder="Numero de documento..."/>
						<span id="scliente"></span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_apartados" name="reporte_apartados">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div> 

	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Entradas de productos</h4>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="fecha_inicio">Fecha inicial</label>
						<input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" />
						<span id="sfecha_inicio"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="fecha_fin">Fecha final</label>
						<input class="form-control" type="date" id="fecha_fin" name="fecha_fin" />
						<span id="sfecha_fin"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="proveedor">Proveedor</label>
						<input class="form-control" type="text" id="proveedor" name="proveedor" placeholder="Numero de documento..."/>
						<span id="sproveedor"></span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_entradas" name="reporte_entradas">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div> 

	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Salidas de productos</h4>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="fecha_inicio">Fecha inicial</label>
						<input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" />
						<span id="sfecha_inicio"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="fecha_fin">Fecha final</label>
						<input class="form-control" type="date" id="fecha_fin" name="fecha_fin" />
						<span id="sfecha_fin"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="cliente">Cliente</label>
						<input class="form-control" type="text" id="cliente" name="cliente" placeholder="Numero de documento..."/>
						<span id="scliente"></span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_salidas" name="reporte_salidas">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div> 
</div>
</section>
</section>
</body>
</html>