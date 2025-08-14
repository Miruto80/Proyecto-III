


<?php

// Recuperar datos de entrega y carrito
$entrega = $_SESSION['pedido_entrega'];
$carrito = $_SESSION['carrito'];




// Calcular total USD
$total = 0;
foreach ($carrito as $item) {
    $cantidad = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
    $total += $cantidad * $precioUnitario;
}

// Obtener métodos de pago
require_once __DIR__ . '/../../modelo/verpedidoweb.php';
$venta = new VentaWeb();
$metodos_pago = $venta->obtenerMetodosPago();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'vista/complementos/head_catalogo.php'; ?>
</head>
<body>
<!----  tasa dolar   --->
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
<!----  tasa dolar   --->

  <?php include 'vista/complementos/nav_catalogo.php'; ?>

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

  <section class="section-padding pt-0">
 
    <div class="container-lg "> 
      <!-- Pasos -->
      <div class="pasos-container mb-5">
        <div class="paso completado"><div class="circulo">1</div><span>Producto</span></div>
        <div class="paso completado"><div class="circulo">2</div><span>Entrega</span></div>
        <div class="paso actual"><div class="circulo">3</div><span>Pago</span></div>
        <div class="paso pendiente"><div class="circulo">4</div><span>Confirmación</span></div>
      </div>

      <div class="row g-5 m-5 ">
     
     <div class="col-md-6 sombra-suave card mb-3" style="background-color:#ffff;">    <!-- D1 -->
       <br>
       <h4 class="mb-3">Completar pago | Pago Movil</h4>
          <form id="formPago" class="row g-4" enctype="multipart/form-data">
            <!-- flag para AJAX -->
            <div class="mb-3">
             <p class="mb-1 text-primary"><b>Datos del pago movil</b></p>
            <p class="mb-1">• Venezuela (0102)  C.I.: V-30.352.937  Telf.: 0414-509.49.59</p>
             <p class="mb-1">• Mercantil (0105)  C.I.: V-11.787.299  Telf.: 0426-554.13.64</p>
           <p></p>
          </div>
            <input type="hidden" name="continuar_pago" value="1">

            <!-- Datos ocultos de entrega -->
            <?php foreach (
                ['id_metodoentrega','direccion_envio','sucursal_envio','empresa_envio','zona','parroquia','sector'] as $field
            ):
                if (isset($entrega[$field])):
                    $val = htmlspecialchars($entrega[$field], ENT_QUOTES);
            ?>
            <input type="hidden" name="<?= $field ?>" value="<?= $val ?>">
            <?php endif; endforeach; ?>

            <!-- Datos ocultos de persona -->
            <input type="hidden" name="id_persona" value="<?= $_SESSION['id'] ?>">

            <!-- Datos ocultos de carrito -->
            <?php foreach ($carrito as $i => $item): ?>
            <input type="hidden" name="carrito[<?= $i ?>][id]" value="<?= $item['id'] ?>">
            <input type="hidden" name="carrito[<?= $i ?>][cantidad]" value="<?= $item['cantidad'] ?>">
            <input type="hidden" name="carrito[<?= $i ?>][cantidad_mayor]" value="<?= $item['cantidad_mayor'] ?>">
            <input type="hidden" name="carrito[<?= $i ?>][precio_detal]" value="<?= $item['precio_detal'] ?>">
            <input type="hidden" name="carrito[<?= $i ?>][precio_mayor]" value="<?= $item['precio_mayor'] ?>">
            <?php endforeach; ?>

            <!-- Totales -->
            <input type="hidden" name="precio_total_usd" id="precio_total_usd" value="<?= $total ?> ">
           
            <input type="hidden" name ="precio_total_bs" id="precio_total_bs" value="">
          
            <!-- Método de Pago -->
       
            
              <input type="hidden" value="1" name="id_metodopago" id="metodopago">

              <div class="col-md-6">
              <label class="form-label">Banco de Origen</label>
              <select name="banco" id="banco" class="form-select" required>
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
            <div class="col-md-6">
              <label class="form-label">Banco de Destino</label>
              <select name="banco_destino" id="banco_destino" class="form-select" required>
                <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
              </select>
            </div>


            <div class="col-md-6">
              <label class="form-label">Referencia Bancaria</label>
              <input type="text" name="referencia_bancaria" id="referencia_bancaria" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono Emisor</label>
              <input type="text" name="telefono_emisor" id="telefono_emisor" class="form-control" required>
            </div>
          
            <div class="col-12">
              <label class="form-label">Subir comprobante</label>
              <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
            </div>

         
            <div class="form-check form-switch mb-3 ml-3">
              <input class="form-check-input" type="checkbox" id="che">
              <label class="form-check-label" for="che">Acepto los <a href="">terminos y condidiones</a></label>
            </div>
          <button type="button" id="btn-guardar-pago" class="btn btn-primary w-100 ">Realizar Pago <i class="fa-solid fa-credit-card ms-2"></i></button>
          <p class="text-muted mt-2"><small>Compra con confianza, tu mejor elección te espera.</small></p>

           
          </form>

        </div>

     

        <!-- Resumen del Pedido -->
        <div class="col-md-5 ">
          <div class="card p-3 sombra-suave">
            <h5>Resumen del Pedido</h5>
            <p><?= count($carrito) ?> producto<?= count($carrito)!==1?'s':'' ?></p>
            <?php foreach($carrito as $item): 
              $precio = $item['cantidad'] >= $item['cantidad_mayor']
                        ? $item['precio_mayor']
                        : $item['precio_detal'];
              $subtotal = $item['cantidad'] * $precio;
            ?>
              <div class="d-flex mb-2">
                <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="" style="width:50px;height:50px;object-fit:cover;margin-right:8px">
                <div>
                  <div><?= htmlspecialchars($item['nombre']) ?></div>
                  <small>Cantidad: <?= $item['cantidad'] ?> × $<?= number_format($precio,2) ?></small>
                  <div><strong>Subtotal: $<?= number_format($subtotal,2) ?></strong></div>
                </div>
              </div>
            <?php endforeach; ?>
            <hr>
            <div class="d-flex justify-content-between">
              <strong>Total USD:</strong>
              <strong>$<?= number_format($total,2) ?></strong>
            </div>
            <div class="d-flex justify-content-between">
              <strong>Total Bs:</strong>
              <strong id="bs">0</strong>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 text-end">

<a href="?pagina=Pedidoentrega" class="btn btn-secondary">
  <i class="fa-solid fa-arrow-left me-2"></i> Volver a Entrega
</a>
</div>
  </section>

  <?php include 'vista/complementos/footer_catalogo.php'; ?>
  <script src="assets/js/Pedidopago.js"></script>
</body>
</html>
