
<!DOCTYPE html>
<html lang="es">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>
<title>Consejos de Belleza | LoveMakeup</title>
</head>

<style>
img{
  cursor: pointer;
transition: transform 0.2s;
}

  img:hover{
    transform: scale(1.1);
  }
</style>

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
             <li class="breadcrumb-item active" aria-current="page">Consejos de Belleza</li>
        </ol>
      </nav>
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Consejos de Belleza y Maquillaje</h2>
        </div>
      </div>
      <div class="row">
        <!-- Card 1 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <article class="post-item card border-1 border-light shadow-sm h-100">
            <div class="image-holder zoom-effect">
              <a href="#" data-bs-toggle="modal" data-bs-target="#consejo1Modal">
                <img src="assets/img/Consejos/maquillaje_autoestima.jpg" alt="Maquillaje y autoestima" class="card-img-top">
              </a>
            </div>
            <div class="card-body d-flex flex-column">
              <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                <div class="meta-date">15 May 2025</div>
                <div class="meta-categories">Bienestar</div>
              </div>
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#consejo1Modal">Maquillaje como impulsor de la autoestima</a>
                </h3>
                <p class="mb-3">Descubre cómo el maquillaje puede transformar tu confianza personal y potenciar tu bienestar emocional...</p>
              </div>
              <div class="mt-auto">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#consejo1Modal">
                  Leer más <i class="fas fa-arrow-right ms-1"></i>
                </button>
              </div>
            </div>
          </article>
        </div>
        
        <!-- Card 2 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <article class="post-item card border-1 border-light shadow-sm h-100">
            <div class="image-holder zoom-effect">
              <a href="#" data-bs-toggle="modal" data-bs-target="#consejo2Modal">
                <img src="assets/img/Consejos/maquillaje_calidad.jpg" alt="Maquillaje de calidad" class="card-img-top">
              </a>
            </div>
            <div class="card-body d-flex flex-column">
              <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                <div class="meta-date">10 May 2025</div>
                <div class="meta-categories">Productos</div>
              </div>
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#consejo2Modal">La importancia del maquillaje de calidad</a>
                </h3>
                <p class="mb-3">Por qué invertir en productos de calidad marca la diferencia en tu piel y en los resultados finales...</p>
              </div>
              <div class="mt-auto">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#consejo2Modal">
                  Leer más <i class="fas fa-arrow-right ms-1"></i>
                </button>
              </div>
            </div>
          </article>
        </div>
        
        <!-- Card 3 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <article class="post-item card border-1 border-light shadow-sm h-100">
            <div class="image-holder zoom-effect">
              <a href="#" data-bs-toggle="modal" data-bs-target="#consejo3Modal">
                <img src="assets/img/Consejos/asesoria_maquillaje.jpg" alt="Asesoría maquillaje" class="card-img-top">
              </a>
            </div>
            <div class="card-body d-flex flex-column">
              <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                <div class="meta-date">5 May 2025</div>
                <div class="meta-categories">Asesoría</div>
              </div>
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#consejo3Modal">Asesoría personalizada en maquillaje</a>
                </h3>
                <p class="mb-3">Aprende a elegir los productos y técnicas que mejor se adaptan a tu rostro, estilo y necesidades...</p>
              </div>
              <div class="mt-auto">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#consejo3Modal">
                  Leer más <i class="fas fa-arrow-right ms-1"></i>
                </button>
              </div>
            </div>
          </article>
        </div>

        <!-- Card 4 -->
        <div class="col-md-6 col-lg-3 mb-4">
          <article class="post-item card border-1 border-light shadow-sm h-100">
            <div class="image-holder zoom-effect">
              <a href="#" data-bs-toggle="modal" data-bs-target="#consejo4Modal">
                <img src="assets/img/Consejos/gama_maquillaje.jpg" alt="Gamas de maquillaje" class="card-img-top">
              </a>
            </div>
            <div class="card-body d-flex flex-column">
              <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                <div class="meta-date">28 Abr 2025</div>
                <div class="meta-categories">Gamas</div>
              </div>
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#consejo4Modal">Tipos de gama en productos de maquillaje</a>
                </h3>
                <p class="mb-3">Guía completa sobre las diferentes gamas de productos y cómo elegir según tus necesidades y presupuesto...</p>
              </div>
              <div class="mt-auto">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#consejo4Modal">
                  Leer más <i class="fas fa-arrow-right ms-1"></i>
                </button>
              </div>
            </div>
          </article>
        </div>

<!-- Modal 1: Maquillaje y Autoestima -->
<div class="modal fade" id="consejo1Modal" tabindex="-1" aria-labelledby="consejo1ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title" id="consejo1ModalLabel">Maquillaje como impulsor de la autoestima</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5 mb-3">
            <img src="assets/img/Consejos/maquillaje_autoestima.jpg" alt="Maquillaje y autoestima" class="img-fluid rounded">
            <div class="mt-3">
              <span class="badge bg-primary me-2">Bienestar</span>
              <span class="badge bg-secondary">15 May 2025</span>
            </div>
          </div>
          <div class="col-md-7">
            <h4>El poder transformador del maquillaje en tu confianza</h4>
            <p>El maquillaje va mucho más allá de la estética; es una poderosa herramienta de autoexpresión que puede impactar positivamente en nuestra percepción personal y bienestar emocional.</p>
            
            <div class="accordion" id="accordionConsejo1">
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading1-1">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1-1" aria-expanded="true" aria-controls="collapse1-1">
                    Autoexpresión y creatividad
                  </button>
                </h5>
                <div id="collapse1-1" class="accordion-collapse collapse show" aria-labelledby="heading1-1" data-bs-parent="#accordionConsejo1">
                  <div class="accordion-body">
                    <p>El maquillaje permite expresar nuestra personalidad, estado de ánimo y estilo único. Esta libertad creativa nos conecta con nuestro yo auténtico y fomenta la aceptación personal.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading1-2">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1-2" aria-expanded="false" aria-controls="collapse1-2">
                    El ritual de autocuidado
                  </button>
                </h5>
                <div id="collapse1-2" class="accordion-collapse collapse" aria-labelledby="heading1-2" data-bs-parent="#accordionConsejo1">
                  <div class="accordion-body">
                    <p>Dedicar tiempo a maquillarnos es un acto de amor propio. Este ritual diario nos permite conectar con nosotros mismos, practicar mindfulness y comenzar el día con una actitud positiva.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading1-3">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1-3" aria-expanded="false" aria-controls="collapse1-3">
                    Refuerzo positivo
                  </button>
                </h5>
                <div id="collapse1-3" class="accordion-collapse collapse" aria-labelledby="heading1-3" data-bs-parent="#accordionConsejo1">
                  <div class="accordion-body">
                    <p>Ver nuestra mejor versión en el espejo genera un circuito de retroalimentación positiva. Los cumplidos recibidos y la sensación de vernos bien potencian nuestra confianza en entornos sociales y profesionales.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading1-4">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1-4" aria-expanded="false" aria-controls="collapse1-4">
                    Empoderamiento personal
                  </button>
                </h5>
                <div id="collapse1-4" class="accordion-collapse collapse" aria-labelledby="heading1-4" data-bs-parent="#accordionConsejo1">
                  <div class="accordion-body">
                    <p>El maquillaje nos permite tomar el control de nuestra imagen. Esta capacidad de transformación nos empodera y nos recuerda que tenemos libertad para definirnos a nosotros mismos.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading1-5">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1-5" aria-expanded="false" aria-controls="collapse1-5">
                    Maquillaje consciente
                  </button>
                </h5>
                <div id="collapse1-5" class="accordion-collapse collapse" aria-labelledby="heading1-5" data-bs-parent="#accordionConsejo1">
                  <div class="accordion-body">
                    <p>Lo importante es mantener una relación saludable con el maquillaje, usándolo como potenciador, no como una máscara. El verdadero poder está en sentirnos bien con y sin él, apreciando su capacidad para realzar nuestra belleza natural.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <a href="?pagina=catalogo_producto" class="btn btn-primary">Ver productos</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal 2: Maquillaje de Calidad -->
<div class="modal fade" id="consejo2Modal" tabindex="-1" aria-labelledby="consejo2ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title" id="consejo2ModalLabel">La importancia del maquillaje de calidad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5 mb-3">
            <img src="assets/img/Consejos/maquillaje_calidad.jpg" alt="Maquillaje de calidad" class="img-fluid rounded">
            <div class="mt-3">
              <span class="badge bg-primary me-2">Productos</span>
              <span class="badge bg-secondary">10 May 2025</span>
            </div>
          </div>
          <div class="col-md-7">
            <h4>Por qué invertir en productos de calidad marca la diferencia</h4>
            <p>Elegir productos de maquillaje de calidad no es un lujo sino una inversión en tu piel y en resultados profesionales. Descubre las razones por las que vale la pena invertir en buenos cosméticos:</p>
            
            <div class="accordion" id="accordionConsejo2">
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading2-1">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2-1" aria-expanded="true" aria-controls="collapse2-1">
                    Protección para tu piel
                  </button>
                </h5>
                <div id="collapse2-1" class="accordion-collapse collapse show" aria-labelledby="heading2-1" data-bs-parent="#accordionConsejo2">
                  <div class="accordion-body">
                    <p>Los productos de calidad contienen ingredientes dermatológicamente testados, libres de sustancias nocivas y con propiedades beneficiosas para la piel. Muchos incluyen protección solar, antioxidantes y activos hidratantes que cuidan tu piel mientras la embellecen.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading2-2">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2-2" aria-expanded="false" aria-controls="collapse2-2">
                    Mayor durabilidad y rendimiento
                  </button>
                </h5>
                <div id="collapse2-2" class="accordion-collapse collapse" aria-labelledby="heading2-2" data-bs-parent="#accordionConsejo2">
                  <div class="accordion-body">
                    <p>Un buen maquillaje permanece intacto durante horas sin necesidad de retoques constantes. La pigmentación superior requiere menos cantidad de producto, haciendo que tu inversión rinda más a largo plazo.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading2-3">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2-3" aria-expanded="false" aria-controls="collapse2-3">
                    Acabado profesional
                  </button>
                </h5>
                <div id="collapse2-3" class="accordion-collapse collapse" aria-labelledby="heading2-3" data-bs-parent="#accordionConsejo2">
                  <div class="accordion-body">
                    <p>La diferencia es visible: texturas refinadas que se funden con la piel, colores vibrantes y fieles, y acabados naturales que realzan sin apelmazar. El resultado final siempre se ve más natural y pulido.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading2-4">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2-4" aria-expanded="false" aria-controls="collapse2-4">
                    Seguridad en cada aplicación
                  </button>
                </h5>
                <div id="collapse2-4" class="accordion-collapse collapse" aria-labelledby="heading2-4" data-bs-parent="#accordionConsejo2">
                  <div class="accordion-body">
                    <p>Las marcas reconocidas invierten en investigación y pruebas rigurosas. Esto minimiza el riesgo de reacciones alérgicas, irritaciones o problemas como acné cosmético que pueden surgir con productos de baja calidad.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading2-5">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2-5" aria-expanded="false" aria-controls="collapse2-5">
                    Inversión inteligente
                  </button>
                </h5>
                <div id="collapse2-5" class="accordion-collapse collapse" aria-labelledby="heading2-5" data-bs-parent="#accordionConsejo2">
                  <div class="accordion-body">
                    <p>No es necesario que todo tu kit sea de alta gama. Prioriza la inversión en productos de larga duración que están en contacto directo con tu piel, como bases, correctores y primers, mientras puedes ser más flexible con productos como sombras o labiales.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <a href="?pagina=catalogo_producto" class="btn btn-primary">Ver productos</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal 3: Asesoría personalizada -->
<div class="modal fade" id="consejo3Modal" tabindex="-1" aria-labelledby="consejo3ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title" id="consejo3ModalLabel">Asesoría personalizada en maquillaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5 mb-3">
            <img src="assets/img/Consejos/asesoria_maquillaje.jpg" alt="Asesoría maquillaje" class="img-fluid rounded">
            <div class="mt-3">
              <span class="badge bg-primary me-2">Asesoría</span>
              <span class="badge bg-secondary">5 May 2025</span>
            </div>
          </div>
          <div class="col-md-7">
            <h4>Cómo elegir lo mejor para ti según tu rostro y estilo</h4>
            <p>Navegar entre miles de productos y técnicas puede resultar abrumador. La asesoría personalizada es clave para encontrar lo que realmente funciona para ti:</p>
            
            <div class="accordion" id="accordionConsejo3">
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading3-1">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3-1" aria-expanded="true" aria-controls="collapse3-1">
                    Conoce tu tipo de piel
                  </button>
                </h5>
                <div id="collapse3-1" class="accordion-collapse collapse show" aria-labelledby="heading3-1" data-bs-parent="#accordionConsejo3">
                  <div class="accordion-body">
                    <p>El primer paso para una asesoría efectiva es identificar si tu piel es seca, grasa, mixta o sensible. Esto determinará el tipo de base, primer y productos de cuidado que mejor funcionarán para ti, evitando brillos indeseados o parches secos.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading3-2">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3-2" aria-expanded="false" aria-controls="collapse3-2">
                    Identifica tu subtono
                  </button>
                </h5>
                <div id="collapse3-2" class="accordion-collapse collapse" aria-labelledby="heading3-2" data-bs-parent="#accordionConsejo3">
                  <div class="accordion-body">
                    <p>Determinar si tu subtono es cálido, frío o neutro es fundamental para elegir bases y correctores que se fundan perfectamente con tu piel, así como colores de maquillaje que realcen tu belleza natural.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading3-3">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3-3" aria-expanded="false" aria-controls="collapse3-3">
                    Morfología facial
                  </button>
                </h5>
                <div id="collapse3-3" class="accordion-collapse collapse" aria-labelledby="heading3-3" data-bs-parent="#accordionConsejo3">
                  <div class="accordion-body">
                    <p>Cada rostro tiene proporciones únicas. Una buena asesoría te ayudará a identificar técnicas de contorno, iluminación y aplicación de rubor que potencien tus rasgos más favorecedores y armonicen tu rostro.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading3-4">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3-4" aria-expanded="false" aria-controls="collapse3-4">
                    Maquillaje según ocasión
                  </button>
                </h5>
                <div id="collapse3-4" class="accordion-collapse collapse" aria-labelledby="heading3-4" data-bs-parent="#accordionConsejo3">
                  <div class="accordion-body">
                    <p>No es lo mismo un maquillaje para la oficina que para una boda o una sesión de fotos. Aprende a adaptar tu maquillaje según la iluminación, duración del evento y tipo de ocasión.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading3-5">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3-5" aria-expanded="false" aria-controls="collapse3-5">
                    Cuidado y mantenimiento
                  </button>
                </h5>
                <div id="collapse3-5" class="accordion-collapse collapse" aria-labelledby="heading3-5" data-bs-parent="#accordionConsejo3">
                  <div class="accordion-body">
                    <p>Una asesoría completa incluye consejos sobre limpieza de brochas, orden de aplicación de productos y rutinas de desmaquillado que preserven la salud de tu piel y la duración de tus productos.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <a href="?pagina=catalogo_producto" class="btn btn-primary">Ver productos</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal 4: Gamas de productos -->
<div class="modal fade" id="consejo4Modal" tabindex="-1" aria-labelledby="consejo4ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title" id="consejo4ModalLabel">Tipos de gama en productos de maquillaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5 mb-3">
            <img src="assets/img/Consejos/gama_maquillaje.jpg" alt="Gama de maquillaje" class="img-fluid rounded">
            <div class="mt-3">
              <span class="badge bg-primary me-2">Gamas</span>
              <span class="badge bg-secondary">28 Abr 2025</span>
            </div>
          </div>
          <div class="col-md-7">
            <h4>Entendiendo las diferentes categorías de productos cosméticos</h4>
            <p>El mercado del maquillaje ofrece opciones para todos los presupuestos y necesidades. Conocer las características de cada gama te ayudará a tomar decisiones informadas:</p>
            
            <div class="accordion" id="accordionConsejo4">
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading4-1">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4-1" aria-expanded="true" aria-controls="collapse4-1">
                    Gama Alta
                  </button>
                </h5>
                <div id="collapse4-1" class="accordion-collapse collapse show" aria-labelledby="heading4-1" data-bs-parent="#accordionConsejo4">
                  <div class="accordion-body">
                    <p>Caracterizada por ingredientes exclusivos, fórmulas patentadas y envases de diseño. Estas marcas invierten fuertemente en investigación e innovación, ofreciendo productos con texturas sofisticadas y alta pigmentación.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading4-2">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4-2" aria-expanded="false" aria-controls="collapse4-2">
                    Gama Media
                  </button>
                </h5>
                <div id="collapse4-2" class="accordion-collapse collapse" aria-labelledby="heading4-2" data-bs-parent="#accordionConsejo4">
                  <div class="accordion-body">
                    <p>El equilibrio perfecto entre calidad y precio accesible. Estas marcas ofrecen productos con buena formulación y rendimiento, manteniendo estándares profesionales sin el precio elevado de las marcas de lujo.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading4-3">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4-3" aria-expanded="false" aria-controls="collapse4-3">
                    Gama Farmacéutica
                  </button>
                </h5>
                <div id="collapse4-3" class="accordion-collapse collapse" aria-labelledby="heading4-3" data-bs-parent="#accordionConsejo4">
                  <div class="accordion-body">
                    <p>Desarrollados con enfoque dermatológico, estos productos combinan maquillaje con beneficios para la piel. Ideales para pieles sensibles o con condiciones específicas.</p>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h5 class="accordion-header" id="heading4-4">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4-4" aria-expanded="false" aria-controls="collapse4-4">
                    Gama Económica
                  </button>
                </h5>
                <div id="collapse4-4" class="accordion-collapse collapse" aria-labelledby="heading4-4" data-bs-parent="#accordionConsejo4">
                  <div class="accordion-body">
                    <p>Accesibles y versátiles, estas marcas han mejorado significativamente sus fórmulas en los últimos años, ofreciendo alternativas de calidad a precios competitivos.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <a href="?pagina=catalogo_producto" class="btn btn-primary">Ver productos</a>
      </div>
    </div>
  </div>
</div>
<!-- Cierre del section de consejos de belleza -->
</div>
</section>

<!-- Sección de espaciado para separar el contenido del footer -->
<section class="py-2">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- Espacio reducido para separar el contenido del footer -->
      </div>
    </div>
  </div>
</section>

<?php include 'vista/complementos/footer_catalogo.php' ?>
  
</body>
</html>
  
</body>
</html>