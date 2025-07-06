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
