$(document).on('click', '.ver-detalles', function () {
    const fila = $(this).closest('tr');
  
    const cantidadMayor = fila.find('.cantidad_mayor').text();
    const precioMayor = fila.find('.precio_mayor').text();
    const stockMaximo = fila.find('.stock_maximo').text();
    const stockMinimo = fila.find('.stock_minimo').text();
  
    $('#modal-cantidad-mayor').text(cantidadMayor);
    $('#modal-precio-mayor').text(precioMayor);
    $('#modal-stock-maximo').text(stockMaximo);
    $('#modal-stock-minimo').text(stockMinimo);
  
    $('#modalDetallesProducto').modal('show');
  });
  
  $('#btnAbrirRegistrar').on('click', function () {
    // Limpiar formulario
    $('#u')[0].reset();
    $('#id_producto').val('');
    $('#accion').val('registrar');
    $('#modalTitle').text('Registrar Producto');
    $('#imagen').attr('src', 'assets/img/logo.PNG');
  });
  
  $(document).ready(function () {
    $('#btnEnviar').on("click", function () {
      var datos = new FormData($('#u')[0]);
      if ($('#accion').val() === 'registrar') {
        datos.append('registrar', 'registrar');
      } else if ($('#accion').val() === 'modificar') {
        datos.append('modificar', 'modificar');
      } else {
        alert('Acción no definida');
        return;
      }
      enviaAjax(datos);
    });
  });
  
  // modificar al abrir el modal
  function abrirModalModificar(boton) {
    const fila = $(boton).closest('tr');
    if (!id_producto) {
  
      console.error('No se pudo obtener el id_producto para modificar');
  
      return;
  
    }
  
    const nombre = fila.find('td').eq(0).text().trim();
    const descripcion = fila.find('td').eq(1).text().trim();
    const marca = fila.find('td').eq(2).text().trim();
    const cantidadMayor = fila.find('.cantidad_mayor').text().trim();
    const precioMayor = fila.find('.precio_mayor').text().trim(); 
    const precioDetal = fila.find('td').eq(5).text().trim(); 
    const stockDisponible = fila.find('td').eq(6).text().trim();
    const stockMaximo = fila.find('.stock_maximo').text().trim();
    const stockMinimo = fila.find('.stock_minimo').text().trim();
    const categoriaTexto = fila.find('td').eq(10).text().trim();
    const imagenSrc = fila.find('td').eq(9).find('img').attr('src');
    $('#imagenActual').val(imagenSrc);
  
    // Buscar el valor del select que corresponde al texto de la categoria
  
    const categoriaSelect = $("#categoria option").filter(function () {
  
      return $(this).text().trim() === categoriaTexto;
  
    }).val();
  
  
    $('#id_producto').val(id_producto);
    $('#nombre').val(nombre);
    $('#descripcion').val(descripcion);
    $('#marca').val(marca);
    $('#cantidad_mayor').val(cantidadMayor);
    $('#precio_mayor').val(precioMayor);
    $('#precio_detal').val(precioDetal);
    $('#stock_disponible').val(stockDisponible);
    $('#stock_maximo').val(stockMaximo);
    $('#stock_minimo').val(stockMinimo);
    if (categoriaSelect !== undefined) {
      $('#categoria').val(categoriaSelect);
    } else {
      // Si no encuentra coincidencia, limpiar selección para evitar error
      $('#categoria').val('');
    }
  
    $('#accion').val('modificar'); 
    // Mostrar imagen actual, solo si existe y no mostrar la predeterminada por defecto
    if (imagenSrc && imagenSrc !== 'assets/img/logo.PNG') {
      $('#imagen').attr('src', imagenSrc);
    } else {
      $('#imagen').attr('src', 'assets/img/logo.PNG');
  
    }
    // Abrir modal
    $('#modalTitle').text('Modificar Producto');
  
    $('#registro').modal('show');
  
  }
  
  function eliminarproducto(id_producto) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
      const datos = new FormData();
      datos.append('id_producto', id_producto);
      datos.append('eliminar', 'eliminar');
      enviaAjax(datos);
    }
  }
  
  function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
      icon: icono,
      timer: tiempo,
      title: titulo,
      html: mensaje,
      showConfirmButton: false,
    });
  }
  
  function enviaAjax(datos) {
    $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      beforeSend: function () {},
      timeout: 10000,
      success: function (respuesta) {
        console.log(respuesta);
        var lee = JSON.parse(respuesta);
        try {
          if (lee.accion == 'consultar') {
            crearConsulta(lee.datos);
          }
          else if (lee.accion == 'incluir') {
            if (lee.respuesta == 1) {
              $('#u')[0].reset();
              muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
              setTimeout(function () {
                location.href = "?pagina=producto";
              }, 1000);
            } else {
              muestraMensaje("error", 1000, "ERROR", "ERROR");
            }
          } else if (lee.accion == 'actualizar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
              setTimeout(function () {
                location = '?pagina=producto';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
            }
          } else if (lee.accion == 'eliminar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente");
              setTimeout(function () {
                location.href = "?pagina=producto";
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", lee.text);
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
      }
    });
  }
  
  $("#archivo").on("change", function () {
    mostrarImagen(this);
  });
  
  $("#imagen").on("error", function () {
    $(this).prop("src", "assets/img/logo.PNG");
  });
  
  function mostrarImagen(f) {
    var tamano = f.files[0].size;
    var megas = parseInt(tamano / 1024);
  
    if (megas > 1024) {
      muestraMensaje("error", 2000, "Error", "La imagen debe ser igual o menor a 1024 K");
      $(f).val('');
    } else {
      if (f.files && f.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#imagen').attr('src', e.target.result);
        }
        reader.readAsDataURL(f.files[0]);
      }
    }
  }
  