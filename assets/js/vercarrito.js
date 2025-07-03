$(document).ready(function () {
    function muestraMensaje(icono, tiempo, titulo, mensaje) {
      Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
      });
    }
  
    // Limpiar eventos anteriores por seguridad
    $(document).off("click", ".btn-mas");
    $(document).off("click", ".btn-menos");
    $(document).off("click", ".btn-eliminar");
  
    // Botón "+"
    $(document).on("click", ".btn-mas", function () {
      const id = $(this).data("id");
      const stock = parseInt($(this).data("stock"));
      const fila = $(this).closest("tr");
      const cantidadActual = parseInt(fila.find(".cantidad").text());
  
      if (cantidadActual < stock) {
        actualizarCantidad(id, cantidadActual + 1, fila);
      } else {
        muestraMensaje('error', 1000, 'Stock limitado', 'Ya has agregado el máximo permitido.');
      }
    });
  
    // Botón "-"
    $(document).on("click", ".btn-menos", function () {
      const id = $(this).data("id");
      const fila = $(this).closest("tr");
      const cantidadActual = parseInt(fila.find(".cantidad").text());
  
      if (cantidadActual > 1) {
        actualizarCantidad(id, cantidadActual - 1, fila);
      }
    });
  
    // Botón eliminar
    $(document).on("click", ".btn-eliminar", function () {
      const id = $(this).data("id");
  
      $.ajax({
        url: 'controlador/vercarrito.php',
        type: 'POST',
        data: {
          accion: 'eliminar',
          id: id
        },
        dataType: 'json',
        success: function (res) {
          if (res.success) {
            $(`tr[data-id='${id}']`).remove();
  
            if (res.total !== undefined) {
              $("#total-carrito").text(res.total);
              setTimeout(() => location.reload(), 100);
            }
          } else {
            muestraMensaje('error', 2000, 'Error', res.mensaje || 'No se pudo eliminar el producto.');
          }
        },
        error: function (xhr, status, error) {
          console.error("Error AJAX (eliminar):", error);
          muestraMensaje('error', 2000, 'Error', 'Error en la comunicación con el servidor.');
        }
      });
    });
  
    function actualizarCantidad(id, cantidad, fila) {
      $.ajax({
        url: "controlador/vercarrito.php",
        type: "POST",
        data: {
          accion: "actualizar",
          id: id,
          cantidad: cantidad
        },
        dataType: "json",
        success: function (res) {
          if (res.success) {
            fila.find(".cantidad").text(res.cantidad);
            fila.find(".precio-unitario").text(`$${res.precio}`);
            fila.find(".subtotal").text(`$${res.subtotal}`);
            $(".total-general").text(`${res.total}`);
          } else {
            muestraMensaje('error', 2000, 'Error', res.error || 'Ocurrió un error al actualizar.');
          }
        },
        error: function (xhr, status, error) {
          console.error("Error AJAX (actualizar):", error);
          muestraMensaje('error', 2000, 'Error', 'Error en la comunicación con el servidor.');
        }
      });
    }
  });
  