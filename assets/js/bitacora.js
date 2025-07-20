$(document).ready(function () {
  // DataTables se inicializa automáticamente con datatables-demo.js

  // Función para eliminar registros de bitácora
  $('.eliminar').on('click', function (e) {
    e.preventDefault();

    Swal.fire({
      title: '¿Desea eliminar este registro de la bitácora?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#38b96f',
      cancelButtonColor: '#EF233C',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        var form = $(this).closest('form');
        var datos = new FormData(form[0]);
        enviaAjax(datos);
      }
  });
});



  // Función para limpiar bitácora antigua
  $('#limpiarBitacora').on('click', function() {
    Swal.fire({
      title: '¿Limpiar bitácora antigua?',
      text: 'Se eliminarán registros de más de 90 días. Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, limpiar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '?pagina=bitacora&limpiar=1',
          type: 'POST',
          success: function(response) {
            try {
              var data = JSON.parse(response);
              if (data.success) {
                Swal.fire({
                  title: '¡Éxito!',
                  text: data.message,
                  icon: 'success',
                  timer: 2000,
                  showConfirmButton: false
                });
                // Eliminar filas de la tabla que sean de más de 90 días
                const fechaLimite = new Date();
                fechaLimite.setDate(fechaLimite.getDate() - 90);
                $('#myTable tbody tr').each(function() {
                  const fechaTexto = $(this).find('td').eq(0).text();
                  if (fechaTexto) {
                    const partes = fechaTexto.split(/[\/ :]/);
                    // Formato esperado: dd/mm/yyyy hh:mm:ss
                    const fecha = new Date(partes[2], partes[1]-1, partes[0], partes[3], partes[4], partes[5]);
                    if (fecha < fechaLimite) {
                      $(this).remove();
                    }
                  }
                });
                // Si la tabla queda vacía, mostrar mensaje
                if ($('#myTable tbody tr').length === 0) {
                  $('#myTable tbody').append('<tr><td colspan="6" class="text-center">No hay registros en la bitácora</td></tr>');
                }
              } else {
                Swal.fire('Error', data.message, 'error');
              }
            } catch (e) {
              Swal.fire('Error', 'Error al procesar la respuesta', 'error');
            }
          },
          error: function() {
            Swal.fire('Error', 'Error de conexión', 'error');
          }
        });
      }
    });
  });
});

// Función para mostrar mensajes
function muestraMensaje(icono, tiempo, titulo, mensaje) {
  Swal.fire({
    icon: icono,
    timer: tiempo,
    title: titulo,
    html: mensaje,
    showConfirmButton: false,
  });
}

// Función para enviar AJAX
function enviaAjax(datos) {
    $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
    beforeSend: function () {
      Swal.fire({
        title: 'Procesando...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
    },
      timeout: 10000,
      success: function (respuesta) {
        console.log(respuesta);
        var lee = JSON.parse(respuesta);
        try {
            if (lee.accion == 'eliminar') {
                if (lee.respuesta == 1) {
            muestraMensaje("success", 1000, "Eliminado con éxito", "El registro se ha eliminado correctamente");
                  setTimeout(function () {
              location.reload();
                  }, 1000);
                } else {
            muestraMensaje("error", 2000, "ERROR", lee.mensaje || lee.text);
                }
              }
        } catch (e) {
          alert("Error en JSON " + e.name);
        }
      },
      error: function (request, status, err) {
        Swal.close();
        if (status == "timeout") {
          muestraMensaje("error", 2000, "Error", "Servidor ocupado, intente de nuevo");
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
        }
      },
      complete: function () {
      Swal.close();
      }
    });
  }

// Función para ver detalles de un registro
function verDetalles(id) {
  $.ajax({
    url: '?pagina=bitacora',
    type: 'POST',
    data: {detalles: id},
    dataType: 'json',
    beforeSend: function() {
      Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo detalles del registro',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
    },
    success: function(response) {
      Swal.close();
      
      // Verificar si hay error en la respuesta
      if(response.error) {
        Swal.fire('Error', response.error, 'error');
        return;
      }
      
      // Verificar que la respuesta tenga los campos necesarios
      if(!response.nombre || !response.apellido || !response.nombre_usuario) {
        Swal.fire('Error', 'Datos incompletos en la respuesta', 'error');
        return;
      }
      
      // Información del Usuario
      $('#detalle-usuario').text(response.nombre + ' ' + response.apellido);
      $('#detalle-rol').text(response.nombre_usuario);
      
      // Información del Evento
      $('#detalle-fecha').text(response.fecha_hora || 'No disponible');
      
      // Tipo de Acción con badge
      let badgeClass = '';
      switch(response.accion) {
        case 'CREAR': badgeClass = 'bg-success'; break;
        case 'MODIFICAR': badgeClass = 'bg-primary'; break;
        case 'ELIMINAR': badgeClass = 'bg-danger'; break;
        case 'ACCESO A MÓDULO': badgeClass = 'bg-info'; break;
        case 'CAMBIO_ESTADO': badgeClass = 'bg-warning'; break;
        default: badgeClass = 'bg-secondary';
      }
      $('#detalle-accion').html(`<span class="badge ${badgeClass}">${response.accion || 'N/A'}</span>`);
      
      // Descripción con formato
      let desc = response.descripcion || 'Sin descripción';
      if (desc.match(/\[(.*?)\]$/)) {
        let partes = desc.split(/\[(.*?)\]$/);
        $('#detalle-descripcion').html(`
          <p class="mb-2">${partes[0]}</p>
          <span class="badge bg-primary">[${partes[1]}]</span>
        `);
      } else {
        $('#detalle-descripcion').text(desc);
      }
      
      $('#detallesModal').modal('show');
    },
    error: function(xhr, status, error) {
      Swal.close();
      console.error('Error AJAX:', xhr.responseText);
      
      // Intentar parsear la respuesta para obtener más detalles del error
      let errorMessage = 'No se pudieron cargar los detalles';
      try {
        if (xhr.responseText) {
          // Si la respuesta contiene HTML, mostrar un mensaje genérico
          if (xhr.responseText.includes('<html') || xhr.responseText.includes('<br />')) {
            errorMessage = 'Error del servidor. Verifique la conexión.';
          } else {
            // Intentar parsear como JSON
            const errorResponse = JSON.parse(xhr.responseText);
            if (errorResponse.error) {
              errorMessage = errorResponse.error;
            }
          }
        }
      } catch (e) {
        // Si no se puede parsear, usar el mensaje por defecto
        errorMessage = 'Error de conexión con el servidor';
      }
      
      Swal.fire('Error', errorMessage, 'error');
    }
  });
}

$(document).ready(function() {


  $('#entrar').on("click", function () {
         
    var datos = new FormData($('#u')[0]);
    datos.append('entrar', 'entrar');
    enviaAjax(datos);
 
  });


});




  