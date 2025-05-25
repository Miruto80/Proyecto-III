<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>OLVIDO</title>
	<link id="pagestyle" href="assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
	  <!-- JS LIBRERIA -->
  <script src="assets/js/libreria/jquery.min.js"></script>
  <script src="assets/js/libreria/sweetalert2.js"></script>

</head>
<body>

<style type="text/css">
	body{
	background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-image: url('assets/img/f010.png');
	}

	.text-g{
	font-size: 14px;
	}

	.color-g{
		color:red;
	}
</style>

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card p-4 shadow-lg" style="width: 600px;">
    	<h4 class="text-center color-g">Olvido de Contraseña</h4>
    	<h6 class="text-center mb-3">Estimado Cliente, <?php echo $_SESSION["nombres"]." ".$_SESSION["apellidos"]; ?> </h6>
        <div class="mb-3 text-center">
            <label for="input" class="form-label fw-bold text-g">Ingrese su correo Electronico</label>
            <input type="text" id="input" class="form-control text-center" placeholder="correo: tucorreo@dominio.com"> 
        </div>
        <div class="d-flex justify-content-between">
            <form action="?pagina=login" method="POST">
<button type="submit" name="cerrarolvido" class="btn btn-danger">Cancelar</button>
</form>


            <button class="btn btn-success">Continuar</button>
        </div>
    </div>
 <script src="assets/js/core/bootstrap.min.js"></script>
</body>
</html>