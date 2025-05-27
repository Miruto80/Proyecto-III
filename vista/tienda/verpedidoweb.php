<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>
<link rel="stylesheet" href="assets/css/estilo_pago.css"> <!-- El CSS del diseño de la tarjeta -->
</head>

<body>
<?php

$usuario = $_SESSION;
$carrito = $_SESSION['carrito'] ?? [];
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
        color: #ffffff;
        background-color: #ff70d9ff;
        font-weight: bold;
    }
    .referer{
        font-weight: bold;
    }

  

    





 
</style>

<div class="cart-step">
    <div class="">1</div>
    <div class="current-step"><a  href="?pagina=vercarrito" class="">
          Carrito de Compras
  </a></div>
    <div>→</div>
    <div class="step-number">2</div>
    <div class=""><a  href="?pagina=verpedidoweb" class="">
           Procesar Pago
  </a></div>
    
</div>

<div class="row m-3">
                    <div class="col-md-7">
                        <div class="left border">
                            <div class="row">
<div class="text-center span mb-2">
          <span class="header">Detalles de Pago</span>
          
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
                        <form class="form row " id="formPedido" >
                            <input type="hidden"  name="id_pedido" id="id_pedido"> 
                              <input type="hidden" name="id_persona" id="id_persona" value="<?php echo $usuario['id']; ?>">
                             <input type="hidden" name="estado" id="estado" value="1">
                             <input type="hidden" name="precio_total" id="precio_total" value="<?= $total ?> ">
                             <input type="hidden" name="tipo" id="tipo" value="2">

                             <div class="col-6">
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

                                    <div class="col-6">
                                        <span>Banco de Destino:</span>
                                       <select class="form-select" id="banco_destino" name="banco_destino"  required>
                                       <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                                       <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
                                      
                                         </select>

                                    </div>

                                <span  class="col-6" name="referencia_bancaria">Referencia Bancaria:</span> 
                                <span class="col-6" name="telefono_emisor">Telefono Emisor:</span>
                                <div class="col-md-6">
                                <input type="text" class="form-control m-1" name="referencia_bancaria" id="referencia_bancaria" placeholder="Ejem: 0456">
                                </div>
                               <div class="col-md-6">
                                <input type="text" class="form-control m-1" name="telefono_emisor" id="telefono_emisor" placeholder="Ejem: 424">
                                </div>
                                <div class="row">



                                    <div class="col-6"><span>Metodo de Pago:</span>
                                
                
                                    <select class="form-select text-gray-900" name="id_metodopago" id="metodopago" required>
                                       <option disabled selected>Seleccione un Metodo de pago</option>
                                                  <?php foreach ($metodos_pago as $mp): ?>
                                        <option value="<?= $mp['id_metodopago'] ?>"><?= $mp['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>


                                    </div>
                                    <div class="col-6"><span>Metodo de Entrega:</span>
                                
                                      <select class="form-select text-gray-900" name="id_entrega" id="metodoentrega" required>
                                      <option disabled selected>Seleccione un Metodo de Entrega</option>
                                         <?php foreach ($metodos_entrega as $me): ?>
                                        <option value="<?= $me['id_entrega'] ?>"><?= $me['nombre'] ?></option>
                                        <?php endforeach; ?>
                                         </select>
                                    </div>

                             <div class="col-12"><span>Direccion de Entrega:</span>
                                    <input class="form-control" type="text" id="direccion" name="direccion" placeholder="Ingrese la Direccion a Detalle">
                               </div>


</p>
                                </div>

                               
                        </form> <!-- fin del formulario -->

 <button class="btns btn-success btn-rp" id="btn-guardar-pedido">Realizar Pedido</button>
                             <p class="text-muted text-center">Compra con confianza, tu mejor elección te espera.
                        </div>                        
                    </div>

     <div class="col-md-5">
        <div class="right border">
        <div class="header">Resumen del Pedido</div>
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
           
        </div>

        <input type="hidden" id="id_detalle">
     <input type="hidden" id="id_detalle_reserva">
         <input type="hidden" id="condicion" value="Sin Validar">
      
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