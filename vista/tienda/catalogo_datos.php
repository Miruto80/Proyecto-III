<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>

</head>

<body>

<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

<!-- php CARRITO--> 
<?php include 'vista/complementos/carrito.php' ?>

<!-- php ENCABEZADO LOGO, ICONO CARRITO Y LOGIN--> 
<?php include 'vista/complementos/nav_catalogo.php' ?>


<!--Banner IMG-->
  <section>

    <div class="slideshow slide-in arrow-absolute text-white position-relative">
      <div class="swiper-wrapper">
        <div class="swiper-slide jarallax">
          <img src="assets/img/b4.jpg" class="jarallax-img" alt="slideshow">
          <div class="banner-content w-100 my-5">
            <div class="container">
              <div class="row justify-content-center text-center">
                <div class="col-md-12 pt-2">
                  <p class="fs-3 text-dark"><b>LoveMakeup C.A </b></p>
                  <h2 class="display-1 text-dark text-uppercase ls-0">
                          <b>Mis Datos</b>
                  </h2>
                </div>
              </div>
            </div>
          </div>
        </div>
    
     
      </div>
     
    </div>

  </section>



<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page">Ver</li>
             <li class="breadcrumb-item active" aria-current="page">Mis Datos</li>
        </ol>
      </nav>
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Datos Personales </h2>
        </div>
      </div>
       <form>
      <div class="row">
         <h5>información personal</h5>

            <div class="col">
                <label>Cedula</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control text-dark" name="" value ="<?php echo $_SESSION['cedula']?>">
                </div>
            </div>
            <div class="col">
                <label>Nombre</label>
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                   <input type="text" class="form-control text-dark" name="" value ="<?php echo $_SESSION['nombre']?>">
                </div>
                
            </div>
            <div class="col">
                <label>Apellido</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                     <input type="text" class="form-control text-dark" name="" value ="<?php echo $_SESSION['apellido']?>">
                </div>
              
            </div>
        </div>
         <div class="row">
            <div class="col">
                 <label>Telefono</label>
                  <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control text-dark" name=""  value ="<?php echo $_SESSION['telefono']?>">
                </div>
                
            </div>
            <div class="col">
                <label>Correo Electronico</label>
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control text-dark" name=""  value ="<?php echo $_SESSION['correo']?>">
                </div>
                
            </div>

        </div>

        <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-dark me-md-2" type="button">Actualizar Datos</button>
                <button class="btn btn-primary" type="reset">Limpiar</button>
            </div>
     

        </div>
     </form>   

<div class="row bg-light">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Seguridad </h2>
        </div>
      </div>
      <div class="row">
         <h5>Clave Actual</h5>
            <div class="col-sm-5">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control" name="">
                </div>
            </div>
        </div>
         <div class="row">
         <h5>Clave nueva</h5>
            <div class="col">
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                   <input type="text" class="form-control" name="">
                </div>
               
            </div>
            <div class="col">
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control" name="">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
             <button class="btn btn-dark me-md-2" type="button">Cambiar Clave</button>
             <button class="btn btn-primary" type="button">Limpiar</button>
        </div>
        </div>


        <div class="row bg-light">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Estado de la Cuenta </h2>
        </div>
      </div>
        
         <div class="row">
            <div class="col">
                <label>Estado</label>
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control text-dark" name=""  value ="Activo">
                </div>
                
            </div>
           <div class="col">
                <label>Cantidad de Pedido Completados</label>
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control text-dark" name=""  value ="100">
                </div>
                
            </div>
             <div class="col">
                <label>Cantidad de Pedido Por Confirmar Pago</label>
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control text-dark" name=""  value ="100">
                </div>
                
            </div>
        </div>

        <div class="row">
            <div class="col">
                <p>Eliminar Cuenta  <button class="btn btn-dark"> Eliminar Cuenta</button> </p> 
             </div>
        </div>

      </div>
      </div>


      </div>
     
      </div>



    </div>
  </section>

<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
  
</body>

</html>