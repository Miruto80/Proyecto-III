<?php
// aviso_legal.php
// Puedes incluir validación de sesión si lo deseas
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'vista/complementos/head_catalogo.php' ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aviso Legal</title>
  <link rel="stylesheet" href="estilos.css"> <!-- Asegúrate que el CSS esté disponible -->
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

  <header class="header">
    <div class="overlay">
      <h1>Aviso Legal</h1>
    </div>
  </header>

  <main class="contenido">
    <section>
      <h2>1. Información General</h2>
      <p>En cumplimiento con la legislación vigente, se pone a disposición de los usuarios la siguiente información:</p>
      <ul>
        <li><strong>Titular del sitio web:</strong> Yermary Prato</li>
        <li><strong>Domicilio:</strong> Av 20 con calles 29 y 30 CC Barquisimeto plaza, Estado Lara, Venezuela</li>
        <li><strong>Correo electrónico:</strong> [Correo electrónico de contacto]</li>
        <li><strong>Teléfono:</strong> 04245115415</li>
        <li><strong>Número de identificación fiscal:</strong> [C.I]</li>
        <li><strong>Registro mercantil:</strong> [Datos del registro mercantil, si aplica]</li>
      </ul>
    </section>

    <section>
      <h2>2. Condiciones de Uso</h2>
      <p>El acceso y uso de este sitio web está sujeto a las siguientes condiciones de uso. Al utilizar este sitio web, aceptas cumplir con estas condiciones. Si no estás de acuerdo con ellas, te recomendamos no utilizar nuestro sitio web.</p>
    </section>

    <section>
      <h2>3. Propiedad Intelectual</h2>
      <p>Todo el contenido de este sitio web, incluyendo textos, gráficos, imágenes, logotipos, iconos, software, 
        y cualquier otro material, está protegido por derechos de autor y otros derechos de propiedadintelectual.</p>
      <p> Estos contenidos son propiedad exclusiva de LoveMakeup o de sus licenciantes.</p>
        <p>Queda prohibida su reproducción, distribución, modificación, o cualquier otro uso no autorizado expresamente
         por el titular de los derechos.</p>
    </section>

    <section>
      <h2>4. Limitación de Responsabilidad</h2>
      <p><strong>a. Contenidos:</strong> Los contenidos de este sitio web son de carácter general y tienen una finalidad meramente informativa.
       LoveMakeup no garantiza la exactitud, integridad o actualidad de los contenidos.
       En ningún caso seremos responsables de cualquier daño directo o indirecto que pueda derivarse del acceso o uso de la información contenida en este sitio web.</p>

      <p><strong>b. Enlaces Externos:</strong> Este sitio web puede contener enlaces a otros sitios web gestionados por terceros.
       LoveMakeup no se hace responsable del contenido ni de la disponibilidad de estos sitios externos, ni asume ninguna responsabilidad
        por cualquier daño o pérdida que pueda derivarse del uso de dichos sitios.</p>
      
        <p><strong>c. Disponibilidad del Servicio:</strong> LoveMakeup no garantiza la disponibilidad y continuidad del funcionamiento del sitio web.
         En la medida en que sea posible, se advertirá con antelación de cualquier interrupción en el 
         funcionamiento del sitio web.</p>
    </section>

    <section>
      <h2>5. Obligaciones del Usuario</h2>
      <p>El usuario se compromete a utilizar el sitio web de manera lícita y a no realizar ninguna acción que pueda dañar
         la imagen, los intereses o los derechos de LoveMakeup o de terceros.
          El usuario deberá abstenerse de realizar cualquier acción que sobrecargue, 
          dañe o inutilice el sitio web, o que impida, de cualquier forma, su normal utilización.</p>
    </section>

    <section>
      <h2>6. Protección de Datos Personales</h2>
      <p>Cualquier dato personal proporcionado a través de este sitio web será tratado conforme a nuestra
         <a href="?pagina=politica_privacidad">politica de privacidad</a>. Te recomendamos leerla detenidamente para entender cómo recopilamos,
          utilizamos y protegemos tu información personal.</p>
    </section>

    <section>
      <h2>7. Modificaciones del Aviso Legal</h2>
      <p>LoveMakeup se reserva el derecho de modificar este Aviso Legal en cualquier momento.
         Cualquier modificación será publicada en esta página y entrará en vigor desde el momento de su publicación. 
         Te recomendamos revisar periódicamente este Aviso Legal para estar informado sobre las condiciones que rigen 
         el uso de este sitio web.</p>
    </section>

    <section>
      <h2>8. Legislación Aplicable y Jurisdicción</h2>
      <p>Este Aviso Legal se rige por la legislación vigente en Venezuela. 
        Para cualquier controversia que pudiera derivarse del acceso o uso de este sitio web,
         el usuario y LoveMakeup acuerdan someterse expresamente a los juzgados y tribunales de Barquisimeto,
          renunciando a cualquier otro fuero que pudiera corresponderles.</p>
    </section>

    <section>
      <h2>9. Contacto</h2>
      <p>Si tienes alguna pregunta o inquietud sobre este Aviso Legal, por favor contáctanos a:</p>
      <ul>
        <li>LoveMakeup</li>
        <li>Av 20 con calles 29 y 30 CC Barquisimeto plaza, Estado Lara, Venezuela</li>
        <li>04245115415</li>
      </ul>
    </section>
  </main>
<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
</body>
</html>
