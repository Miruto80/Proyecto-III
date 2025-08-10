  $(window).on('load', function() {
 $(document).ready(function() {
  $('input[name^="permiso"]').each(function() {
    const input = $(this);
    const name = input.attr('name');

    if (name.includes('[ver]')) {
      const moduloId = name.split('[')[1].split(']')[0];

      input.on('change', function() {
        const isChecked = $(this).is(':checked');

        const acciones = ['registrar', 'editar', 'eliminar', 'especial'];

        acciones.forEach(function(accion) {
          const selector = `input[name="permiso[${moduloId}][${accion}]"]`;
          const checkbox = $(selector);

          checkbox.prop('disabled', !isChecked);
          if (!isChecked) {
            checkbox.prop('checked', false);
          }
        });
      });
    }
  });
});

if (!isChecked) {
  Swal.fire({
    icon: 'info',
    title: 'Acceso limitado',
    text: 'Para activar otras acciones, primero debes permitir "ver".'
  });
}
});

 
  $('#ayudapermiso').on("click", function () {
  
  const driver = window.driver.js.driver;
  
  const driverObj = new driver({
    nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior',
        doneBtnText: 'Listo',
    popoverClass: 'driverjs-theme',
    closeBtn:false,
    steps: [
      { element: '.modulo', popover: { title: 'Modulo', description: 'Aqui es una sección del sistema que cumple una función específica. Por ejemplo, el módulo de clientes, productos o reportes.', side: "left", }},
      { element: '.ver', popover: { title: 'Ver Datos', description: 'Permite al usuario consultar información dentro del módulo, como listas, detalles o registros, sin poder cambiar nada', side: "bottom", align: 'start' }},
      { element: '.registrar', popover: { title: 'Registrar Datos', description: 'Da acceso para crear nuevos registros, como añadir un cliente, producto, venta entre otros...', side: "left", align: 'start' }},
      { element: '.editar', popover: { title: 'Editar Datos', description: 'Permite editar o actualizar información existente, como corregir datos de un cliente, producto, usuario entre otros...', side: "left", align: 'start' }},
      { element: '.eliminar', popover: { title: 'Eliminar Datos', description: 'Autoriza al usuario a borrar registros del sistema', side: "left", align: 'start' }},
      { element: '.especial', popover: { title: 'Accion Especial', description: 'Son funciones avanzadas o específicas del módulo, como confirmar un pedido, desactivar producto entre otros...', side: "left", align: 'start' }},
      { element: '.form-check-input', popover: { title: 'Casilla de permiso', description:
         'Azul: El permiso está activo. El usuario puede realizar esa acción (por ejemplo, Ver, Modificar, Eliminar) pero en Gris: El permiso está desactivado. El usuario no tiene acceso a esa acción.', side: "right", align: 'start' }},
       { element: '.guardar', popover: { title: 'Boton guardar permiso', description: 'Te permite guardar los cambios de los permiso del usuario', side: "right", align: 'start' }},
      { popover: { title: 'Eso es todo', description: 'Este es el fin de la guia espero hayas entendido'} }
    ]
  });
  
  // Iniciar el tour
  driverObj.drive();
});