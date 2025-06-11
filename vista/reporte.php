<!DOCTYPE html>
<html lang="es">
<head>
  
  <?php include 'complementos/head.php'; ?>
  <title> Reportes | LoveMakeup </title>
  
</head>
<body class="g-sidenav-show bg-gray-100">
  <?php include 'complementos/sidebar.php'; ?>
  
  <main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Bienvenid@</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Inicio</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Generar Reporte</h6>
        </nav>
        <?php include 'complementos/nav.php'; ?>
      </div>
    </nav>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0">
                  <i class="fa-solid fa-file-pdf" style="color: #f6c5b4;"></i> Generar Reporte
                </h4>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
                  <span class="icon text-white"><i class="fas fa-file-medical"></i></span>
                  <span class="text-white">#</span>
                </button>
              </div>

              









              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

   

    <?php include 'complementos/footer.php'; ?>
    <script src="assets/js/tipousuario.js"></script>
  </main>
</body>
</html>
