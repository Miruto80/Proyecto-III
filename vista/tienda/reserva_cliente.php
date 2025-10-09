<?php
// ===================================================
// VISTA: RESERVA_CLIENTE
// ===================================================

// Evitar el Notice de sesión duplicada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = $_SESSION;
$carrito = $_SESSION['carrito'] ?? [];
$carritoEmpty = empty($carrito);

// ✅ Definir $sesion_activa para evitar warnings en nav_catalogo
$sesion_activa = isset($_SESSION['id']) && !empty($_SESSION['id']);

// Calcular total USD
$total = 0;
foreach ($carrito as $item) {
    $cantidad = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor']
        ? $item['precio_mayor']
        : $item['precio_detal'];
    $total += $cantidad * $precioUnitario;
}

// Obtener métodos de pago
require_once __DIR__ . '/../../modelo/verpedidoweb.php';
$venta = new VentaWeb();
$metodos_pago = $venta->obtenerMetodosPago();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'vista/complementos/head_catalogo.php'; ?>
  <title>Reserva de Productos</title>
</head>
<body>

<?php include 'vista/complementos/nav_catalogo.php'; ?>

<!-- SCRIPT PARA TASA DEL DÓLAR -->
<script>
async function obtenerTasaDolarApi() {
  try {
    const respuesta = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
    if (!respuesta.ok) throw new Error(`Error HTTP: ${respuesta.status}`);
    const datos = await respuesta.json();
    const tasaBCV = parseFloat(datos.promedio).toFixed(2);
    var totalBs = <?php echo $total; ?>;
    var resultadoBs = (totalBs * tasaBCV).toFixed(2);
    document.getElementById("bs").textContent = resultadoBs + " Bs";
    document.getElementById("precio_total_bs").value = resultadoBs;
  } catch (error) {
    document.getElementById("bs").textContent = "Error al cargar tasa BCV";
  }
}
document.addEventListener("DOMContentLoaded", obtenerTasaDolarApi);
</script>

<style>
.text-color1 { color: #ff009a; }
.pasos-container {
  display: flex; justify-content: space-between; align-items: center;
  max-width: 600px; margin: 40px auto;
}
.paso { text-align: center; flex: 1; position: relative; }
.paso:not(:last-child)::after {
  content: ''; position: absolute; top: 15px; right: -50%;
  width: 100%; height: 2px; background-color: #ccc;
}
.circulo {
  width: 30px; height: 30px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: bold; margin: 0 auto 8px;
}
.completado .circulo { background: #f679d4; }
.actual .circulo { background: #4fa7fa; }
.pendiente .circulo { background: #adb5bd; }
.sombra-suave { box-shadow: 0 4px 12px rgba(255, 105, 180, 0.25); }
.opcion-custom {
  display: block; padding: 15px; border: 2px solid #dee2e6; border-radius: 12px;
  transition: all 0.3s ease; background-color: #fff; color: #f679d4;
}
input[type="radio"]:checked + .opcion-custom {
  border-color: #f679d4; background-color: #ffe9f9; color: black;
}
</style>

<section class="section-padding pt-0">
  <div class="container-lg">

    <!-- PASOS DE COMPRA -->
    <div class="pasos-container mb-5">
      <div class="paso completado"><div class="circulo">1</div><span>Carrito</span></div>
      <div class="paso actual"><div class="circulo">2</div><span>Reserva</span></div>
      <div class="paso pendiente"><div class="circulo">3</div><span>Confirmación</span></div>
    </div>

    <div class="row g-5 m-5">
      <!-- FORMULARIO DE RESERVA -->
      <div class="col-md-6 sombra-suave card p-4">
        <h4 class="mb-3">Completar Reserva | Pago Móvil</h4>

        <?php if ($carritoEmpty): ?>
          <div class="alert alert-warning">Tu carrito está vacío.</div>
        <?php else: ?>
          <form id="formReserva" enctype="multipart/form-data" class="row g-4">
            <input type="hidden" name="continuar_pago" value="1">
            <input type="hidden" name="id_persona" value="<?= $usuario['id'] ?>">
            <input type="hidden" name="estado" value="1">
            <input type="hidden" name="precio_total_usd" value="<?= $total ?>">
            <input type="hidden" name="precio_total_bs" id="precio_total_bs" value="">
            <input type="hidden" name="tipo" value="3">

            <div class="mb-3">
              <p class="mb-1 text-primary"><b>Datos del pago móvil</b></p>
              <p class="mb-1">• Venezuela (0102)  C.I.: V-30.352.937  Telf.: 0414-509.49.59</p>
              <p class="mb-1">• Mercantil (0105)  C.I.: V-11.787.299  Telf.: 0426-554.13.64</p>
            </div>

            <!-- BANCOS -->
            <div class="col-md-6">
              <label class="form-label">Banco de Origen</label>
              <select name="banco" id="banco" class="form-select" required>
                <option selected disabled>Seleccione</option>
                <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
                <option value="0134-Banesco">0134-Banesco</option>
                <option value="0191-Banco Nacional De Credito">0191-Banco Nacional De Credito</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Banco de Destino</label>
              <select name="banco_destino" id="banco_destino" class="form-select" required>
                <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
              </select>
            </div>

            <!-- REFERENCIA Y TELÉFONO -->
            <div class="col-md-6">
              <label class="form-label">Referencia Bancaria</label>
              <input type="text" name="referencia_bancaria" id="referencia_bancaria" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono Emisor</label>
              <input type="text" name="telefono_emisor" id="telefono_emisor" class="form-control" required>
            </div>

            <!-- COMPROBANTE -->
            <div class="col-12">
              <label class="form-label">Adjuntar Comprobante</label>
              <input type="file" name="imagen" id="imagen" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
              <img id="preview" src="#" alt="Vista previa" class="img-fluid border rounded d-none mt-2" style="max-height:200px;">
            </div>

            <!-- ACEPTAR TÉRMINOS -->
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="check_terminos">
              <label class="form-check-label" for="check_terminos">
                Acepto los <a data-bs-toggle="modal" data-bs-target="#modalTerminos">Términos y Condiciones</a>
              </label>
            </div>

            <button type="button" id="btn-guardar-reserva" class="btn btn-primary w-100" disabled>
              Realizar Reserva <i class="fa-solid fa-calendar-check ms-2"></i>
            </button>

            <p class="text-muted mt-2 text-center"><small>Reserva con confianza, tu mejor elección te espera.</small></p>
          </form>
        <?php endif; ?>
      </div>

      <!-- RESUMEN DE LA RESERVA -->
      <div class="col-md-5">
        <div class="card p-3 sombra-suave">
          <h5>Resumen de Reserva</h5>
          <p><?= count($carrito) ?> producto<?= count($carrito) !== 1 ? 's' : '' ?></p>
          <?php foreach ($carrito as $item):
            $cantidad = $item['cantidad'];
            $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
            $subtotal = $cantidad * $precioUnitario;
          ?>
            <div class="d-flex mb-2">
              <img src="<?= htmlspecialchars($item['imagen']) ?>" style="width:50px;height:50px;object-fit:cover;margin-right:8px">
              <div>
                <div><?= htmlspecialchars($item['nombre']) ?></div>
                <small>Cantidad: <?= $cantidad ?> × $<?= number_format($precioUnitario, 2) ?></small>
                <div><strong>Subtotal: $<?= number_format($subtotal, 2) ?></strong></div>
              </div>
            </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between"><strong>Total USD:</strong><strong>$<?= number_format($total, 2) ?></strong></div>
          <div class="d-flex justify-content-between"><strong>Total Bs:</strong><strong id="bs">0 Bs</strong></div>
        </div>

        <div class="mt-3 text-end">
          <a href="?pagina=vercarrito" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i> Volver al Carrito
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MODAL TÉRMINOS -->
<div class="modal fade" id="modalTerminos" tabindex="-1" aria-labelledby="modalTerminosLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modalTerminosLabel">Términos y Condiciones</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Al realizar una reserva, aceptas nuestras políticas de compra, entrega y pagos.</p>
      </div>
    </div>
  </div>
</div>

<?php include 'vista/complementos/footer_catalogo.php'; ?>
<script src="assets/js/reserva_cliente.js"></script>
</body>
</html>
