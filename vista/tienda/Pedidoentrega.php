<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>

</head>
<style>
  .text-color1{
    color: #ff009a;
  }

    .pasos-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 600px;
      margin: 50px auto;
    }

    .paso {
      text-align: center;
      position: relative;
      flex: 1;
    }

    .paso:not(:last-child)::after {
      content: '';
      position: absolute;
      top: 15px;
      right: -50%;
      width: 100%;
      height: 2px;
      background-color: #ccc;
      z-index: 0;
    }

    .circulo {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      margin: 0 auto 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      position: relative;
      z-index: 1;
    }

    .completado .circulo {
      background-color: #f679d4; /* amarillo */
    }

    .actual .circulo {
      background-color: #4fa7fa; /* naranja */
    }

    .pendiente .circulo {
      background-color: #adb5bd; /* gris */
    }

    .paso span {
      font-size: 14px;
    }

    .sombra-suave {
box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3); 
}

.opcion-custom {
  display: block;
  padding: 15px;
  border: 2px solid #dee2e6;
  border-radius: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  background-color: #fff;
  color: #f679d4;
  font-weight: 500;
}

.opcion-custom i {
  font-size: 24px;
  margin-bottom: 8px;
}


input[type="radio"]:checked + .opcion-custom {
  border-color: #f679d4;
  background-color: #ffe9f9;
  color: black;
}

  </style>
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

<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
        <div class="pasos-container">
    <div class="paso completado">
      <div class="circulo">1</div>
      <span>Producto</span>
    </div>
    <div class="paso actual">
      <div class="circulo">2</div>
      <span>Entrega</span>
    </div>
    <div class="paso pendiente">
      <div class="circulo">3</div>
      <span>Pago</span>
    </div>
    <div class="paso pendiente">
      <div class="circulo">4</div>
      <span>Confirmación</span>
    </div>
  </div>
  
  <div class="container py-2"> 
    <!-- FORMULARIO ENTREGA -->
<form id="formEntrega" class="row g-4">
  <div class="row text-center justify-content-center mb-4">
    <p>Seleccione el metodo de entrega</p>


    <div class="col-md-3">
      <input type="radio" id="op1" name="metodo_entrega" value="4" class="d-none">
      <label for="op1" class="opcion-custom">
        <i class="fa-solid fa-shop"></i><br>
        Tienda física
      </label>
    </div>


    <div class="col-md-3">
      <input type="radio" id="op2" name="metodo_entrega" value="2" class="d-none">
      <label for="op2" class="opcion-custom">
        <i class="fa-solid fa-truck"></i><br>
        Envíos nacionales
      </label>
    </div>


    <div class="col-md-3">
      <input type="radio" id="op3" name="metodo_entrega" value="1" class="d-none">
      <label for="op3" class="opcion-custom">
      <i class="fa-solid fa-bicycle"></i><br>
        Delivery
      </label>
    </div>
  </div>

  <input type="hidden" name="continuar_entrega" value="1">
 
    <div id="formulario-opciones">
      <!-- Aquí se cargará el contenido según la opción seleccionada -->
      </div>
    </div>

    
      <br>
    <div class="d-flex justify-content-between">
    <a href="?pagina=vercarrito" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i> Atrás</a>
      <button type="button" id="btn-continuar-entrega" class="btn btn-primary me-2"> <i class="fa-solid fa-arrow-right"> </i> Continuar</button>
      </div>

  
</form>
    </div>

  </section>



<template id="form-op1">
  <div class="card p-3 sombra-rosada">
     <div class="mb-3">
      <p class="text-center text-color1">Retiro en Tienda fisica</p>
    <label for="retira" class="form-label text-dark">Tienda física ubicada en la av 20 con calles 29 y 30 CC Barquisimeto plaza, Estado Lara, Venezuela </label>
    <input type="text" class="form-control text-dark" name="direccion_envio" id="retira" value="Retiro en Tienda Fisica" readonly>
  </div>
  </div>
</template>

<template id="form-op2">
  <div class="card p-3 sombra-rosada">
     <div class="row g-3 mb-3">
      <p class="text-center text-color1">Envios Nacionales</p>
      <div class="col-md-6">
      <label class="form-label">Empresa</label>
      <select name="empresa_envio" class="form-select">
        <option value="">Selecciona</option>
        <?php foreach($metodos_entrega as $me): ?>
          <?php if (in_array($me['id_entrega'], [2,3])): ?>
            <option  value="<?= $me['id_entrega'] ?>"><?= $me['nombre'] ?></option>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <label for="codigoSucursal" class="form-label text-dark">Código de la sucursal</label>
      <input type="text" name="sucursal_envio" class="form-control text-dark" id="codigoSucursal" placeholder="Ej. 2140">
    </div>
  </div>
  <div class="mb-3">
    <label for="nombreSucursal" class="form-label text-dark">Nombre de la sucursal</label>
    <input type="text"   name="direccion_envio" class="form-control text-dark" id="nombreSucursal" placeholder="Ej. Sucursal Barquisimeto Oeste">
  </div>
  </div>
</template>

<!-- delivery -->
<template id="form-op3">
  <div class="card p-3 sombra-rosada">
   <div class="row g-3 mb-3">
    <p class="text-center text-color1">Delivery</p>
    <div class="col-4">            
                              <label for="zona" class="labeldel">Zona:</label>
                              <select id="zona" name="zona" class="form-select text-gray-900" id="zona">
                                <option value="">-- Selecciona una zona --</option>
                                <option value="norte">Norte</option>
                                <option value="sur">Sur</option>
                                <option value="este">Este</option>
                                <option value="oeste">Oeste</option>
                                <option value="centro">Centro</option>
                              </select>
                              </div>
                              <div class="col-4">
                              <label for="parroquia" class="labeldel">Parroquia:</label>
                              <select id="parroquia" name="parroquia" class="form-select text-gray-900" id="parroquia">
                                <option value="">-- Selecciona una parroquia --</option>
                              </select>
                              </div>
                              <div class="col-4">
                              <label for="sector" class="labeldel">Sector:</label>
                              <select id="sector" name="sector" class="form-select text-gray-900" id="sector">
                                <option value="">-- Selecciona un sector --</option>
                              </select>
                            </div>
  </div>
  <div class="mb-3">
    <label for="direccion" class="form-label text-dark">Dirección exacta</label>
    <input type="text" name="direccion_envio" class="form-control text-dark" id="direccion" placeholder="Ej. Calle 5, casa 12, frente a panadería...">
  </div>
  </div>
</template>



<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
<script src="assets/js/Pedidoentrega.js"></script>
  
</body>

</html>