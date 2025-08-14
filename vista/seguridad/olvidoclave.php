<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Olvido de Clave | LoveMakeup C.A</title>
	<link id="pagestyle" href="assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
	  <!-- JS LIBRERIA -->
  <script src="assets/js/libreria/jquery.min.js"></script>
  <script src="assets/js/libreria/sweetalert2.js"></script>
<style>
	body{
	background-color: #879cc5;
	}

	.text-g{
	font-size: 16px;
	}

	.text-t{
		font-size: 16px;
	}

	.color-g{
		color:#EB60E4;
		font-size: 18px;
	}

	.text-c{
		color:#000000;
		font-size: 20px;
	}
</style>

</head>
<body>


<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card p-4 shadow-lg" style="width: 600px;">
		<div class="text-center">
		<img src="assets/img/logo2.png" class="img-fluid mb-1" style="width:100px;">
		</div>
		
    	<h4 class="text-center text-primary mb-1">Olvido de Contraseña</h4>
		<p class="text-center color-g"> <b>Paso: 1 de 3</b></p>
		
    	<h6 class="text-center">
			Estimado 
			<?php  echo ($_SESSION["tabla_origen"] == 1) ? "Cliente" : "Usuario";?>, 
			<?php echo $_SESSION["nombres"] . " " . $_SESSION["apellidos"]; ?>
		</h6>
<hr class="bg-dark">
        <form action="?pagina=olvidoclave" method="POST" id="forclave" autocomplete="off">
		<div class="text-center mb-5">
            <p class="text-center text-primary m-0"><strong>Ingrese su correo Electronico</strong></p>
            <input type="text" id="correo" name="correo" class="form-control text-center text-dark" placeholder="correo: tucorreo@dominio.com"> 
            <span id="textocorreo" class="text-danger m-0"></span>
			<br>
		<div class="text-center text-dark ">
		<p>
			Para continuar, por favor ingresa tu <strong>correo electrónico</strong>. 
			Te enviaremos un codigo de verificación.
		</p>
		</div>

		</div>
        <div class="d-flex justify-content-between ">
			<button type="submit" name="cerrarolvido" class="btn btn-danger">Cancelar</button>
				
            <button type="button" class="btn btn-success" id="validar">Continuar</button>
			</form>
        </div>
    </div>

 <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/olvidoclave.js"></script>
</body>
</html>