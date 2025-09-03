<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>
<link rel="stylesheet" href="assets/css/formulario.css">
<style>
  .modal-productoo {
  border-radius: 15px;
  border: none;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-productoo .modal-header {
  background: linear-gradient(135deg, #f88af8ff 0%, #4dc0eeff 100%);
  border-radius: 15px 15px 0 0;
  border-bottom: none;
  padding: 1.5rem;
}

.modal-productoo .modal-title {
  color: #2c3e50;
  font-weight: 700;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  gap: 10px;
}

.modal-productoo .modal-title i {
  font-size: 1.8rem;
  color: #0a0909;
}

.modal-productoo .modal-body {
  padding: 2rem;
  background: #f8f9fa;
}

.modal-productoo .btn-close {
  background-color: rgba(8, 6, 6, 0.8);
  border-radius: 50%;
  padding: 8px;
  transition: all 0.3s ease;

}

.modal-productoo .btn-close:hover {
  background-color: #eb0f0f;
  transform: scale(1.1);
}

</style>
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

<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="custom-breadcrumb mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page">Ver</li>
             <li class="breadcrumb-item active" aria-current="page">Mis Datos</li>
        </ol>
      </nav>
      <br>

      <hr>
    <div class="conteiner">
      <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
          <!-- Foto y datos del usuario -->
          <div class="d-flex align-items-center mb-3 mb-md-0">
            <i class="fas fa-user-circle fa-3x text-titel me-3"></i>
            <div>
              <h5 class="mb-0"> <?php echo $nombreCompleto ?> </h5>
              <small class="text-muted"> <?php echo $_SESSION['nombre_usuario'];?> </small>
            </div>
          </div>

          <!-- Opciones -->
          <div class="d-flex flex-column flex-md-row gap-2">
           <button id="btn-personales" class="btn btn-custom active" onclick="mostrarFormulario('personales')">Datos personales</button>
            <button id="btn-seguridad" class="btn btn-custom" onclick="mostrarFormulario('seguridad')">Seguridad</button>
            <button id="btn-direcciones" class="btn btn-custom" onclick="mostrarFormulario('direcciones')">Direcciones</button>
            </div>
          </div>
        </div>
      </div>
      <hr>
    <!-- Formularios -->
        <div id="form-personales" class="formulario mt-3">
          <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title text-titel-1">Datos Personales </h2>
        </div>
      </div>
       <form action="?pagina=catalogo_datos" method="POST" autocomplete="off" id="u">
      <div class="row">
         <h5>Información personal</h5>
  
        </div>  
          <div class="seccion-formularioo">
          <div class="row mb-3">
            <div class="col-md-4 mb-3">
              <label for="cedula">Cédula</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-id-card" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="cedula" name="cedula" value="<?php echo $_SESSION['cedula'] ?>">
              </div>
              <p id="textocedula" class="text-danger"></p>
            </div>

            <div class="col-md-4 mb-3">
              <label for="nombre">Nombre</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre'] ?>">
              </div>
              <p id="textonombre" class="text-danger"></p>
            </div>

            <div class="col-md-4 mb-3">
              <label for="apellido">Apellido</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="apellido" name="apellido" value="<?php echo $_SESSION['apellido'] ?>">
              </div>
              <p id="textoapellido" class="text-danger"></p>
            </div>

            <div class="col-md-6 mb-3">
              <label for="telefono">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-mobile-screen-button" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="telefono" name="telefono" value="<?php echo $_SESSION['telefono'] ?>">
              </div>
              <p id="textotelefono" class="text-danger"></p>
            </div>

            <div class="col-md-6 mb-3">
              <label for="correo">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="correo" name="correo" value="<?php echo $_SESSION['correo'] ?>">
              </div>
              <p id="textocorreo" class="text-danger"></p>
            </div>
          </div>
  </div>  
        <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn-verde me-md-2" type="button" id="actualizar"> <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Datos</button>
                <button class="btn-reset" type="reset"> <i class="fa-solid fa-repeat me-2"></i> Restaurar</button>
            </div>
     

        </div>
     </form>   
    
  </div><!-- f1 /-->

        <div id="form-seguridad" class="formulario  mt-3 d-none"> <!-- f2-->
          <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title text-titel-1">Seguridad</h2>
        </div>
      </div>
            <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h4 class=""> Cambio de clave </h4>
        </div>
      </div>
      <form action="?pagina=catalogo_datos" method="POST" autocomplete="off" id="formclave">
     <div class="seccion-formularioo">
  <div class="row mb-3">
  
    <div class="col-12">
      <label for="claveactual">Clave actual</label>
    </div>
    <div class="col-md-6 col-lg-5">
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-key" style="color:#ff2bc3;"></i></span>
        <input type="text" class="form-control text-dark" id="clave" name="clave">
      </div>
      <p id="textoclave" class="text-danger"></p>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label for="clavenueva" class="text-dark">Clave nueva</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
        <input type="text" class="form-control text-dark" id="clavenueva" name="clavenueva">
      </div>
      <p id="textoclavenueva" class="text-danger"></p>
    </div>

    <div class="col-md-6">
      <label for="clavenuevac" class="text-dark">Confirmar clave nueva</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
        <input type="text" class="form-control text-dark" id="clavenuevac" name="clavenuevac">
      </div>
      <p id="textoclavenuevac" class="text-danger"></p>
    </div>
  </div>

  <input type="hidden" name="persona" value="<?php echo $_SESSION['id'] ?>">

  </div>
        <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
             <button class="btn-verde me-md-2" type="button" id="actualizarclave"> <i class="fa-solid fa-key"></i> Cambiar Clave</button>
             <button class="btn-reset" type="reset"> <i class="fa-solid fa-eraser"></i> Limpiar</button>
        </div>
             </form>
        </div>

<hr>
        <div class="row bg-light">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title text-titel-1">Estado de la Cuenta </h2>
        </div>
      </div>
      
 
        <div class="row">
          <div class="col">
            <p class="text-dark">
              <i class="fa-solid fa-user-xmark"></i> ¿Deseas Eliminar la Cuenta? 
              <button class="btn-delete ms-2" data-bs-toggle="modal" data-bs-target="#cuenta"><i class="fa-solid fa-user-xmark me-2"></i>Eliminar Cuenta</button>
            </p>
          </div>
        </div>

        </div> <!-- f2 / -->


 <!--|||||||||||||||||||||||| DIRECCION -->

        <div id="form-direcciones" class="formulario d-none"> <!-- f3 -->
        <?php
// Mapeamos las direcciones por método de entrega
$direccionMap = [];
foreach ($direccion as $dir) {
    $direccionMap[$dir['id_metodoentrega']] = $dir;
}

foreach ($entrega as $item) {
    if ($item['estatus'] == 1) {
        $id = $item['id_entrega'];
        $nombre = htmlspecialchars($item['nombre']);
        $dirData = $direccionMap[$id] ?? null;

        if ($id == 1) { // DELIVERY ?>
            <div class="row">
                <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
                    <h2 class="section-title text-titel-1">Direcciones</h2>
                </div>
            </div>
            <table class="table" width="100%" cellspacing="0">
                <thead class="bg-table">
                    <tr>
                        <th class="text-white"><i class="fa-solid fa-bicycle me-2"></i> DELIVERY</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="hidden" name="id_entrega[]" value="<?= $id ?>">
                            <div class="container">
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-md-1 col-12">
                                        <p class="text-dark"><b><?= $nombre ?></b></p>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <input type="text" class="form-control text-dark" name="direccion_envio_<?= $id ?>" placeholder="Dirección de mi casa" disabled
                                            value="<?= isset($dirData['direccion_envio']) ? htmlspecialchars($dirData['direccion_envio']) : '' ?>">
                                    </div>
                                    <div class="col-md-2 col-12 d-flex gap-2">
                                        <?php if ($dirData): ?>
                                          <button class="btn-editar"
                                              data-bs-toggle="modal"
                                              data-bs-target="#modalEditarDelivery"
                                              data-id="<?= $dirData['id_direccion'] ?>"
                                              data-direccion="<?= htmlspecialchars($dirData['direccion_envio']) ?>"
                                          >
                                              <i class="fa-solid fa-pen-to-square me-2"></i> Editar
                                          </button>
                                        <?php else: ?>
                                            <button class="btn-registrar"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAgregarDireccion"
                                                data-metodo="<?= $id ?>"
                                                data-nombre="<?= $nombre ?>"
                                            >
                                                <i class="fa-solid fa-file-circle-plus me-2"></i> Agregar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
<?php
        } elseif ($id == 2 || $id == 3) {
            if (!isset($renderedNational)) {
                $renderedNational = true; ?>
                <table class="table" width="100%" cellspacing="0">
                    <thead class="bg-table">
                        <tr>
                            <th class="text-white"><i class="fa-solid fa-truck me-2"></i> ENVIOS NACIONALES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="container">
<?php } ?>
                                    <input type="hidden" name="id_entrega[]" value="<?= $id ?>">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-md-1 col-12">
                                            <p class="text-dark"><b><?= $nombre ?></b></p>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <input type="text" class="form-control text-dark" name="sucursal_envio_<?= $id ?>" placeholder="Sucursal" disabled
                                                value="<?= isset($dirData['sucursal_envio']) ? htmlspecialchars($dirData['sucursal_envio']) : '' ?>">
                                        </div>
                                        <div class="col-md-7 col-12">
                                            <input type="text" class="form-control text-dark" name="direccion_envio_<?= $id ?>" placeholder="Dirección" disabled
                                                value="<?= isset($dirData['direccion_envio']) ? htmlspecialchars($dirData['direccion_envio']) : '' ?>">
                                        </div>
                                        <div class="col-md-2 col-12 d-flex gap-2">
                                            <?php if ($dirData): ?>
                                                 <button class="btn-editar"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarDireccion"
                                                    data-id="<?= $dirData['id_direccion'] ?>"
                                                    data-metodo="<?= $id ?>"
                                                    data-nombre="<?= $nombre ?>"
                                                    data-direccion="<?= htmlspecialchars($dirData['direccion_envio']) ?>"
                                                    <?php if (in_array($id, [2, 3])): ?>
                                                        data-sucursal="<?= htmlspecialchars($dirData['sucursal_envio']) ?>"
                                                    <?php endif; ?>
                                                >
                                                    <i class="fa-solid fa-pen-to-square me-2"></i> Editar
                                                </button>
                                            <?php else: ?>
                                                <button class="btn-registrar"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalAgregarDireccion"
                                                    data-metodo="<?= $id ?>"
                                                    data-nombre="<?= $nombre ?>"
                                                >
                                                    <i class="fa-solid fa-file-circle-plus me-2"></i> Agregar
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
<?php
        }
    }
}
if (isset($renderedNational)) {
    echo '                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>';
}
?>

        </div><!-- f3 /-->
      
      </div> 
    
    
    </div>
  </div>
     
      </div>

  </section>


<script>
  function mostrarFormulario(formularioId) {
    // Ocultar todos los formularios
    document.querySelectorAll('.formulario').forEach(form => {
      form.classList.add('d-none');
    });
    // Mostrar el formulario correspondiente
    document.getElementById('form-' + formularioId).classList.remove('d-none');

    document.querySelectorAll('.btn-custom').forEach(btn => {
      btn.classList.remove('active');
    });
    const btnActivo = document.getElementById('btn-' + formularioId);
    if (btnActivo) btnActivo.classList.add('active');
  }
</script>



<!-- |||||||||||||||||FOR LISTO-->
<div class="modal fade" id="modalEditarDireccion" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="?pagina=catalogo_datos" autocomplete="off" id="editardireccion"> <!-- ajusta ruta según tu backend -->
      <div class="modal-content modal-productoo">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="modalLabel">Editar Dirección</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_direccion" id="modal_id_direccion">
          <input type="hidden" name="id_metodoentrega" id="modal_id_metodo">
        
            <div class="seccion-formularioo">
          <div class="mb-3">
            <label for="modal_direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control text-dark" name="direccion_envio" id="modal_direccion">
             <p id="textodir" class="text-danger"></p>
          </div>

          <div class="mb-3" id="modal_sucursal_group" style="display: none;">
            <label for="modal_sucursal" class="form-label">Sucursal</label>
            <input type="text" class="form-control text-dark" name="sucursal_envio" id="modal_sucursal">
             <p id="textosur" class="text-danger"></p>
          </div>
        </div>
           </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-registrar" name="actualizardireccion" id="direccion">Guardar cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('modalEditarDireccion');
  modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const idDireccion = button.getAttribute('data-id');
    const metodo = button.getAttribute('data-metodo');
    const direccion = button.getAttribute('data-direccion');
    const sucursal = button.getAttribute('data-sucursal');

    document.getElementById('modal_id_direccion').value = idDireccion;
    document.getElementById('modal_id_metodo').value = metodo;
    document.getElementById('modal_direccion').value = direccion || '';

    const sucursalGroup = document.getElementById('modal_sucursal_group');
    const sucursalInput = document.getElementById('modal_sucursal');

    if (metodo === '2' || metodo === '3') {
      sucursalGroup.style.display = 'block';
      sucursalInput.value = sucursal || '';
    } else {
      sucursalGroup.style.display = 'none';
      sucursalInput.value = '';
    }
  });
});
</script>

<div class="modal fade" id="modalEditarDelivery" tabindex="-1" aria-labelledby="modalDeliveryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
   
      <div class="modal-content modal-productoo">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="modalDeliveryLabel">Editar Dirección (Delivery)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="seccion-formularioo">
           <form method="POST" action="?pagina=catalogo_datos" autocomplete="off" id="editardelivery">
          <input type="hidden" name="id_direccion" id="delivery_id_direccion">
          <input type="hidden" name="id_metodoentrega" value="1">
          <input type="hidden" name="sucursal_envio" value="no aplica">

          <div class="mb-3">
            <label for="delivery_direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control text-dark" name="direccion_envio" id="delivery_direccion">
            <p id="textodir1" class="text-danger"></p>
          </div>
        </div>
         </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-registrar" name="actualizardireccion" id="direccionedit">Guardar cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalDelivery = document.getElementById('modalEditarDelivery');
  modalDelivery.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const idDireccion = button.getAttribute('data-id');
    const direccion = button.getAttribute('data-direccion');

    document.getElementById('delivery_id_direccion').value = idDireccion;
    document.getElementById('delivery_direccion').value = direccion || '';
  });
});
</script>




<div class="modal fade" id="modalAgregarDireccion" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content modal-productoo">
        <div class="modal-header">
        <h5 class="modal-title text-white" id="modalAgregarLabel">Agregar Dirección para <span id="modalNombreMetodo"></span></h5>
          <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
             <div class="seccion-formularioo">
           <form method="POST" action="?pagina=catalogo_datos" autocomplete="off" id="incluir">
          <input type="hidden" name="id_metodoentrega" id="agregar_id_metodo">

          <div class="mb-3">
            <label for="agregar_direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control text-dark" name="direccion_envio" id="agregar_direccion">
            <p id="textodir2" class="text-danger"></p>
          </div>

          <div class="mb-3" id="agregar_sucursal_group" style="display: none;">
            <label for="agregar_sucursal" class="form-label">Sucursal</label>
            <input type="text" class="form-control text-dark" name="sucursal_envio" id="agregar_sucursal">
            <p id="textosur1" class="text-danger"></p>
          </div>
        </div>
          </div>
        <div class="modal-footer">
          <button type="button" id="agregardireccion" name="incluir" class="btn btn-registrar">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalAgregar = document.getElementById('modalAgregarDireccion');
  modalAgregar.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const metodo = button.getAttribute('data-metodo');
    const nombre = button.getAttribute('data-nombre');

    document.getElementById('agregar_id_metodo').value = metodo;
    document.getElementById('modalNombreMetodo').textContent = nombre;

    document.getElementById('agregar_direccion').value = '';
    document.getElementById('agregar_sucursal').value = '';

    const sucursalGroup = document.getElementById('agregar_sucursal_group');
    if (metodo === '2' || metodo === '3') {
      sucursalGroup.style.display = 'block';
    } else {
      sucursalGroup.style.display = 'none';
      
    }
  });
});
</script>




<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
   <script src="assets/js/catalago_datos.js"></script>


<div class="modal fade" id="cuenta" tabindex="2" aria-labelledby="s" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content modal-productoo">
      <div class="modal-header bg-table">
        <h5 class="modal-title text-white" id="exampleModalLabel">¿Deseas Eliminar la Cuenta?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style=" color: #ffffff;"></button>
      </div>
      <div class="modal-body">
        <h5 class="text-titel-2">Aviso Importante sobre la Eliminación de Cuenta</h5>
        <p class="text-dark"> <b>Estimado/a, <?php echo $nombreCompleto ?> </b></p>

        <p class="text-dark">Queremos informarte que al eliminar tu cuenta, se perderá de forma permanente toda la información relacionada con tus pedidos, tu historial de compras y la lista de tus productos favoritos.</p>

        <p class="text-dark">Esta acción es irreversible, y una vez eliminada tu cuenta, no podremos recuperar la información eliminada.</p>

         <div class="seccion-formularioo">
      <form id="eliminarForm" action="?pagina=catalogo_datos" method="POST" autocomplete="off"> 
          <label>Escriba la palabra ACEPTAR, para confimar la eliminación</label>
          <input type="text" name="confirmar" id="confirmar" class="form-control text-dark" placeholder="ACEPTAR">
          <p id="textoconfirmar" class="text-danger"></p>
          <input type="hidden" name="persona" value="<?php echo $_SESSION['id'] ?>" >
           </div>
          <div class="modal-footer">
              <button type="button" class="btn-verde" name="eliminar" id="btnEliminar">Continuar</button>
          </div>
      </form>
    </div>
  </div>
</div>



</body>

</html>