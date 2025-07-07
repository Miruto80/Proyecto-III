<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>
</head>

<body>
<script>
async function obtenerTasaDolarApi() {
    try {
        const respuesta = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }
        const datos = await respuesta.json();
        const tasaBCV = parseFloat(datos.promedio).toFixed(2); 
        var totalBs = <?php echo $total; ?>;
        var resultadoBs = (totalBs * tasaBCV).toFixed(2); 
        document.getElementById("bs").textContent = "Resultado: " + resultadoBs + " Bs";  
        document.getElementById("precio_total_bs").value = resultadoBs ;  
    } catch (error) {
        document.getElementById("bs").textContent = "Error al cargar el total";
        console.error("Error al obtener la tasa:", error);
    }
}
document.addEventListener("DOMContentLoaded", obtenerTasaDolarApi);
</script>

<?php

$usuario = $_SESSION;
$carrito = $_SESSION['carrito'] ?? [];
$carritoEmpty = empty($_SESSION['carrito']);
$total = 0;

?>

<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
<div class="preloader-wrapper">
  <div class="preloader"></div>
</div>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

<!-- php CARRITO--> 
<?php include 'vista/complementos/carrito.php' ?>

<!-- php ENCABEZADO LOGO, ICONO CARRITO Y LOGIN--> 
<?php include 'vista/complementos/nav_catalogo.php' ?>

<style>


.card{
    max-width: 1000px;
    margin: 2vh;
}
.card-top{
    padding: 0.7rem 5rem;
}
.card-top a{
    float: left;
    margin-top: 0.7rem;
}
#logo{
    font-family: 'Dancing Script';
    font-weight: bold;
    font-size: 1.6rem;
}
.card-body{
    padding: 0 5rem 5rem 5rem;
    background-image: url("https://i.imgur.com/4bg1e6u.jpg");
    background-size: cover;
    background-repeat: no-repeat;
}
@media(max-width:768px){
    .card-body{
        padding: 0 1rem 1rem 1rem;
        background-image: url("https://i.imgur.com/4bg1e6u.jpg");
        background-size: cover;
        background-repeat: no-repeat;
    }  
    .card-top{
        padding: 0.7rem 1rem;
    }
}
.row{
    margin: 0;
}
.upper{
    padding: 1rem 0;
    justify-content: space-evenly;
}
#three{
    border-radius: 1rem;
        width: 22px;
    height: 22px;
    margin-right:3px;
    border: 1px solid blue;
    text-align: center;
    display: inline-block;
}
#payment{
    margin:0;
    color: blue;
}
.icons{
    margin-left: auto;
}
form span{
    color: rgb(179, 179, 179);
}
.form{
    padding: 2vh 0;
}
input{
    border: 1px solid rgba(0, 0, 0, 0.137);
    padding: 1vh;
   
    outline: none;
    width: 100%;
    background-color: rgb(247, 247, 247);
}
input:focus::-webkit-input-placeholder
{
      color:transparent;
}
.header{
    font-size: 1.5rem;
}
.left{
    background-color: #ffffff;
    padding: 2vh;   
}
.left img{
    width: 2rem;
}
.left .col-4{
    padding-left: 0;
}
.right .item{
    padding: 0.3rem 0;
}
.right{
    background-color: #ffffff;
    padding: 2vh;
}
.col-8{
    padding: 0 1vh;
}
.lower{
    line-height: 2;
}
.btns{
  
    color: white;
    width: 100%;
    font-size: 0.7rem;
    margin: 4vh 0 1.5vh 0;
    font-size: 16px;
    padding: 1.5vh;
    border-radius: 8px;
    background-color: #ff70d9ff;
    border-color: #ffffff;
}
.btns:focus{
    box-shadow: none;
    outline: none;
    box-shadow: none;
    color: white;
    -webkit-box-shadow: none;
    transition: none; 
    background-color: #ff70d9ff;
    border-color: #ffffff;
}
.btns:hover{
    color: white;
    background-color: #ff70d9ff;
    border-color: #ffffff;
   
}
a{
    color: black;
}
a:hover{
    color: black;
    text-decoration: none;
}
input[type=checkbox]{
    width: unset;
    margin-bottom: unset;
}

.cart-step {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      margin-left: 30px;
    }
    .cart-step div {
      font-weight: bold;
      margin-right: 15px;
    }
    .cart-step .current-step {
      color: black;
    }
    .cart-step .step-number {
      width: 30px;
      height: 30px;
      background-color: #000;
      color: white;
      border-radius: 50%;
      text-align: center;
      line-height: 30px;
      margin-right: 10px;
    }

    .span{
      
        font-weight: bold;
    }
    .referer{
        font-weight: bold;
    }

  

    .is-invalid {
    border: 1.5px solid #dc3545 !important; /* rojo bootstrap */
    box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
}

/* Mensajes de error debajo del input */
.error-text {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 3px;
    font-weight: 500;
    display: block;
    min-height: 18px; /* evitar que salte el contenido al aparecer o desaparecer */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.left, .right {
  background: #fff;
  border-radius: 1rem;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
  padding: 1.5rem;
}

.header, .header2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #444;
  border-bottom: 2px solid #e1e1e1;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
}

.form-select, .form-control {
  border-radius: 0.5rem;
  border: 1px solid #ccc;
}

.btns.btn-success.btn-rp {
  padding: 0.6rem 1.5rem;
  border-radius: 0.6rem;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  background-color: #ff70d9ff;
  border: none;
  transition: background-color 0.3s ease-in-out;
}

.btns.btn-success.btn-rp:hover {
  background-color: #ff70d9ff;
}

.item {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 0.6rem;
  padding: 1rem;
  margin-bottom: 0.8rem;
}

.img-fluid.img {
  border-radius: 0.5rem;
}

.referer h5 {
  font-weight: 600;
  color: #333;
}

.lower {
  font-size: 1.2rem;
  padding: 1rem;
  margin-top: 1rem;
  border-radius: 0 0 1rem 1rem;
}


.enlace-compra {
  
    display: inline-block;
    background-color: #f3f4f6; /* gris claro */
    color: #374151; /* gris oscuro elegante */
    padding: 10px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }

  .enlace-compra:hover {
    background-color: #e5e7eb;
    color: #111827;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transform: translateY(-1px);
  }

  .enlace-compra:active {
    transform: scale(0.98);
  }

  .enlace-compra-disabled {
    display: inline-block;
    background-color: #f9fafb;
    color: #9ca3af;
    padding: 10px 24px;
    border-radius: 12px;
    font-weight: 500;
    font-size: 16px;
    text-decoration: none;
    cursor: not-allowed;
    box-shadow: none;}

    .detalle-compra-container {
    text-align: center;
    margin-top: 24px;
  }
 
</style>


<?php
$carritoVacio = empty($_SESSION['carrito']);
?>
    <div class="detalle-compra-container">
  <?php if ($carritoVacio): ?>
    <span class="enlace-compra-disabled">Volver atras</span>
  <?php else: ?>
    <a href="?pagina=vercarrito" class="enlace-compra">Volver atras<i class="fa-solid fa-reply"></i> </a>
  <?php endif; ?>
</div>


<div class="row m-3">
                    <div class="col-md-7">
                        <div class="left border">
                            <div class="row">
<div class="text-center span mb-2">
          <span class="header">Detalles de Pago.</span>
          
</div>                                
 
 

  <div class="col-5 text-center referer">
    <h5>Banco: Venezuela</h5>
    <p class="mb-1">C.I.: 30.352.937</p>
    <p>TLF.: 0414-509.49.59</p>
  </div>
<div class="col-2 p-1 logo">
 <img src="assets/img/logo.png" style="width: 100px; height:100px;">
</div>
  <div class="col-5 text-center referer">
    <h5>Banco: Mercantil</h5>
    <p class="mb-1">C.I.: 11.787.299</p>
    <p>TLF.: 0426-554.13.64</p>
  </div>

                            </div>
                        <form class="form row " id="formPedido" enctype="multipart/form-data" >
                            <input type="hidden"  name="id_pedido" id="id_pedido"> 
                              <input type="hidden" name="id_persona" id="id_persona" value="<?php echo $usuario['id']; ?>">
                             <input type="hidden" name="estado" id="estado" value="1">
                             <input type="hidden" name="precio_total_usd" id="precio_total_usd" value="<?= $total ?> ">
                             <input type="hidden" name ="precio_total_bs" id="precio_total_bs" value="">
                             <input type="hidden" name="tipo" id="tipo" value="2">

                           

                              <div class="col-6" style="margin-left: 200px;"><span>Metodo de Entrega:</span>
                                
                                <select class="form-select text-gray-900" name="id_metodoentrega" id="metodoentrega" required>
                               
                                   <?php foreach ($metodos_entrega as $me): ?>
                                  <option value="<?= $me['id_entrega'] ?>"><?= $me['nombre'] ?></option>
                                  <?php endforeach; ?>
                                   </select>
                              </div>
                      <div class="row" id="divdelivery">
                                  <div class="col-4">            
                              <label for="zona" class="labeldel">Zona:</label>
                              <select class="form-select text-gray-900" id="zona">
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
                              <select class="form-select text-gray-900" id="parroquia">
                                <option value="">-- Selecciona una parroquia --</option>
                              </select>
                              </div>
                              
                              <div class="col-4">
                              <label for="sector" class="labeldel">Sector:</label>
                              <select class="form-select text-gray-900" id="sector">
                                <option value="">-- Selecciona un sector --</option>
                              </select>
                            </div>

                            <div class="col-12" >
                              <span class="labeldel">Direccion de Entrega:</span>
                                    <input class="form-control" type="text" id="direccion" name="direccion" placeholder="--Ingrese la Direccion a Detalle--">
                               </div>

                       </div>


                       <div class="row" id="divEnvio" style="display: none;">

                               <div class="col-md-6  text-gray-900"> 
                                  <span> Codigo de Sucursal:</span> 
                                <input type="text" class="form-control m-1" name="" id="codigoSu" placeholder="------">
                                </div>

                                <div class="col-md-6  text-gray-900"> 
                                  <span> Nombre Sucursal:</span> 
                                <input type="text" class="form-control m-1" name="" id="nomSu" placeholder="------">
                                </div>
                       </div>

         <!--             <div class="row" id="divAdomicilio" style="display: ;">
                        <div class="col-3">
                                  <label for="estado" class="labeldel">Estado:</label>
                                  <select id="estado_loc" class="form-select text-gray-900">
                                    <option value="">-- Selecciona un estado --</option>
                                    <option value="Lara">Lara</option>
                                    <option value="Zulia">Zulia</option>
                                    <option value="Miranda">Miranda</option>
                                   
                                  </select>
                         </div>
                           <div class="col-3">
                                  <label for="ciudad" class="labeldel">Ciudad:</label>
                                  <select id="ciudad_loc" class="form-select text-gray-900" disabled>
                                    <option value="">-- Selecciona una ciudad --</option>
                                  </select>
                                </div>
                            </div>

                            
                            <div class="row" id="divdelivery">
                                <div class="col-4">
                                  <label for="zona" class="labeldel">Zona:</label>
                                  <select id="zona_loc" class="form-select text-gray-900" disabled>
                                    <option value="">-- Selecciona una zona --</option>
                                  </select>
                             </div>
                              <div class="col-4">
                                  <label for="parroquia" class="labeldel">Parroquia:</label>
                                  <select id="parroquia_loc" class="form-select text-gray-900" disabled>
                                    <option value="">-- Selecciona una parroquia --</option>
                                  </select>
                                </div>
                                <div class="col-4">
                                  <label for="sector" class="labeldel">Sector:</label>
                                  <select id="sector_loc" class="form-select text-gray-900" disabled>
                                    <option value="">-- Selecciona un sector --</option>
                                  </select>
                                </div>
                                
                      </div> -->
                            
                               <input type="hidden" name="direccion_completa" id="direccion_completa">
                                    <input id="direccion_input" name="direccion_envio" type="hidden" value="">
                                
                               <div class="col-6"><span>Metodo de Pago:</span>
                                
                
                                <select class="form-select text-gray-900" name="id_metodopago" id="metodopago" required>
                                   <option disabled selected>--Seleccione un Metodo de pago--</option>
                                              <?php foreach ($metodos_pago as $mp): ?>
                                    <option value="<?= $mp['id_metodopago'] ?>"><?= $mp['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>


                                </div>
                               
                                <div class="col-md-6  text-gray-900"> 
                                  <span> Referencia Bancaria:</span> 
                                <input type="text" class="form-control m-1" name="referencia_bancaria" id="referencia_bancaria" placeholder="--0456--">
                                </div>

                               
                                 
                             
                               
                               <div class="col-md-4  text-gray-900">   
                                <span class="col-6" name="">Telefono Emisor:</span>
                                <input type="text" class="form-control m-1" name="telefono_emisor" id="telefono_emisor" placeholder="--424--">
                                </div>

                                <div class="col-4">


                                        <span>Banco de Origen:</span>
                                
                                       <select class="form-select" id="banco" name="banco"  required>
                                       <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                                       <option value="0156-100% Banco ">0156-100% Banco </option>
                                       <option value="0172-Bancamiga Banco Universal,C.A">0172-Bancamiga Banco Universal,C.A</option>
                                       <option value="0114-Bancaribe">0114-Bancaribe</option>
                                       <option value="0171-Banco Activo">0171-Banco Activo</option>
                                       <option value="0166-Banco Agricola De Venezuela">0166-Banco Agricola De Venezuela</option>
                                       <option value="0128-Bancon Caroni">0128-Bancon Caroni</option>
                                       <option value="0163-Banco Del Tesoro">0163-Banco Del Tesoro</option>
                                       <option value="0175-Banco Digital De Los Trabajadores, Banco Universal">0175-Banco Digital De Los Trabajadores, Banco Universal</option>
                                       <option value="0115-Banco Exterior">0115-Banco Exterior</option>
                                       <option value="0151-Banco Fondo Comun">0151-Banco Fondo Comun</option>
                                       <option value="0173-Banco Internacional De Desarrollo">0173-Banco Internacional De Desarrollo</option>
                                       <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
                                       <option value="0191-Banco Nacional De Credito">0191-Banco Nacional De Credito</option>
                                       <option value="0138-Banco Plaza">0138-Banco Plaza</option>
                                       <option value="0137-Banco Sofitasa">0137-Banco Sofitasa</option>
                                       <option value="0104-Banco Venezolano De Credito">0104-Banco Venezolano De Credito</option>
                                       <option value="0168-Bancrecer">0168-Bancrecer</option>
                                       <option value="0134-Banesco">0134-Banesco</option>
                                       <option value="0177-Banfanb">0177-Banfanb</option>
                                       <option value="0146-Bangente">0146-Bangente</option>
                                       <option value="0174-Banplus">0174-Banplus</option>
                                       <option value="0108-BBVA Provincial">0108-BBVA Provincial</option>
                                       <option value="0157-Delsur Banco Universal">0157-Delsur Banco Universal</option>
                                       <option value="0601-Instituto Municipal De Credito Popular">0601-Instituto Municipal De Credito Popular</option>
                                       <option value="0178-N58 Banco Digital Banco Microfinanciero S.A">0178-N58 Banco Digital Banco Microfinanciero S.A</option>
                                       <option value="0169-R4 Banco Microfinanciero C.A.">0169-R4 Banco Microfinanciero C.A.</option>
                                      </select>


                                    </div>

                                    
                                    <div class="col-4">
                                        <span>Banco de Destino:</span>
                                       <select class="form-select" id="banco_destino" name="banco_destino"  required>
                                       <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                                       <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
                                      
                                         </select>

                                    </div>


                                    <div class="mb-3">
    <span>Subir comprobante:</span>
    <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*" required>
  </div>

<!-- Switch empresa (solo aparece en MRW/Zoom) -->
<div id="entregaTipoContainer" style="display:none;">
  <label><input type="radio" name="entregaTipo" value="sucursal" checked> Sucursal</label>
  <label><input type="radio" name="entregaTipo" value="domicilio"> Domicilio</label>
</div>

<!-- Reusa tu bloque zona/parroquia/sector/dirección para domicilio -->
<div id="domicilioFields" style="display:none;">
  <!-- ya existentes selects de zona/parroquia/sector y campo dirección -->
</div>

<!-- Sucursal interna de MRW/Zoom -->
<div id="sucursalFields" style="display:none;">
  <label for="sucursal">Seleccione sucursal:</label>
  <select id="sucursal" name="sucursal_envio" class="form-select">
    <option value="">-- Selecciona--</option>
    <option value="Central">Central</option>
    <option value="Norte">Norte</option>
    <!-- etc -->
  </select>
</div>

<!-- Inputs específicos de empresa externa -->
<div id="empresaFields" style="display:none;">
  <label for="empresa">Compañía:</label>
  <input type="text" id="empresa" name="empresa_envio" class="form-control" placeholder="MRW o Zoom">
  <label for="tracking">Número de guía:</label>
  <input type="text" id="tracking" name="tracking" class="form-control" placeholder="1234567890">
</div>

                               
                        </form> <!-- fin del formulario -->
      <?php if ($carritoEmpty): ?>
    <button  class="btns btn-success btn-rp" style=" color: #aaa; pointer-events: none; cursor: default; text-decoration: none;">Realizar Pedido</button>
  <?php else: ?>
   <button class="btns btn-success btn-rp" id="btn-guardar-pedido">Realizar Pedido</button>
  <?php endif; ?>
 
                             <p class="text-muted text-center">Compra con confianza, tu mejor elección te espera.
                        </div>                        
                    </div>

     <div class="col-md-5">
        <div class="right border">
        <div class="header2">Resumen del Pedido</div>
        <p><?= count($carrito) ?> producto<?= count($carrito) !== 1 ? 's' : '' ?></p>

      <?php foreach ($carrito as $item):
    $id = $item['id'];
    $cantidad = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
    $subtotal = $cantidad * $precioUnitario;
?>
    <div class="row item"
        data-id-producto="<?= $id ?>"
        data-cantidad="<?= $cantidad ?>"
        data-precio-unitario="<?= $precioUnitario ?>"
        data-subtotal="<?= $subtotal ?>"
    >
        <div class="col-4 align-self-center">
            <img class="img-fluid img" src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
        </div>
        <div class="col-8">
            <div class="row"><b>precio: $<?= number_format($precioUnitario, 2) ?></b></div>
            <div class="row text-muted"><b>nombre:<?= htmlspecialchars($item['nombre']) ?></b></div>
            <div class="row"><b>Cantidad: <?= $cantidad ?></b></div>
            <div class="col text-right"><b>sub total: $<?= number_format($subtotal, 2) ?></b></div>
        </div>
    </div>
<?php endforeach; ?>
    </div>
     
       
        <div class="row lower" style="background-color:#ff70d9ff; color:#fff;">
            <div class="col text-left"><b>Total a Pagar</b></div>
            <div class="col text-right"><b>$<?= number_format($total, 2) ?></b></div>
            <h5 class="text-white" id="bs">0</h5>
           
           
        </div>

    
      
     </div>
            </div>
                </div>
    </div>



</div>

<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
<script src="assets/js/verpedidoweb.js"></script>
</body>
</html>