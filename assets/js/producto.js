$(document).ready(function () {
  let productosStockBajo = [];

  $('tbody tr').each(function () {
      const fila = $(this);
      const nombreProducto = fila.find('td').eq(0).text().trim();
      const stockDisponible = parseInt(fila.find('td').eq(4).text().trim(), 10);
      const stockMinimo = parseInt(fila.data('stock-minimo'), 10);

      // Verificar si el stock está cerca o ha alcanzado el mínimo
      if (stockDisponible <= stockMinimo || stockDisponible <= stockMinimo + (stockMinimo * 0.1)) {
          productosStockBajo.push(`"${nombreProducto}"`);
          fila.find('td').eq(4).html('<i class="fa-solid fa-triangle-exclamation" style="color: red;"></i> ' + stockDisponible);
      }
  });

  if (productosStockBajo.length > 0) {
    Swal.fire({
      icon: "warning",
      title: "¡Atención! Stock bajo",
      html: `Estos productos están cerca o han alcanzado el stock mínimo: <strong>${productosStockBajo.join(', ')}</strong>.`,
      toast: true,
      position: "top",
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
      didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
      }
  });
  }
});

$('#btnLimpiar').on("click", function () {
  $('#nombre, #descripcion, #marca, #cantidad_mayor, #precio_mayor, #precio_detal, #stock_maximo, #stock_minimo, #categoria').val('').removeClass('is-valid is-invalid');
  $('#imagen').attr("src", "assets/img/logo.PNG");
});




$(document).on('click', '.ver-detalles', function () {
  const fila = $(this).closest('tr');

  // Acceder a los datos almacenados en los atributos data-*
  const cantidadMayor = fila.data('cantidad-mayor');
  const precioMayor = fila.data('precio-mayor');
  const stockMaximo = fila.data('stock-maximo');
  const stockMinimo = fila.data('stock-minimo');

  // Asignar los valores a los elementos del modal
  $('#modal-cantidad-mayor').text(cantidadMayor);
  $('#modal-precio-mayor').text(precioMayor);
  $('#modal-stock-maximo').text(stockMaximo);
  $('#modal-stock-minimo').text(stockMinimo);

  // Mostrar el modal con los detalles
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
      if(validarenvio()){
      var datos = new FormData($('#u')[0]);
      if ($('#accion').val() === 'registrar') {
        datos.append('registrar', 'registrar');
      } else if ($('#accion').val() === 'modificar') {
        datos.append('modificar', 'modificar');
      } else {
        alert('Acción no definida');
        return;
      }
    }
      enviaAjax(datos);
    });
  });
  
  // modificar al abrir el modal
  function abrirModalModificar(boton) {
    const fila = $(boton).closest('tr');

    // Obtener ID del producto desde el botón de eliminación
    const botonEliminarOnclick = fila.find('button.eliminar').attr('onclick');
    let id_producto = null;

    if (botonEliminarOnclick) {
        const match = botonEliminarOnclick.match(/(\d+)/);
        if (match) {
            id_producto = match[0];
        }
    }

    if (!id_producto) {
        console.error('No se pudo obtener el id_producto para modificar');
        return;
    }

    // Obtener datos visibles desde la tabla
    const nombre = fila.find('td').eq(0).text().trim();
    const descripcion = fila.find('td').eq(1).text().trim();
    const marca = fila.find('td').eq(2).text().trim();
    const precioDetal = fila.find('td').eq(3).text().trim();
    const stockDisponible = fila.find('td').eq(4).text().trim();
    const imagenSrc = fila.find('td').eq(5).find('img').attr('src');
    const categoriaTexto = fila.find('td').eq(6).text().trim();

    // Obtener datos ocultos desde data-*
    const cantidadMayor = fila.data('cantidad-mayor');
    const precioMayor = fila.data('precio-mayor');
    const stockMaximo = fila.data('stock-maximo');
    const stockMinimo = fila.data('stock-minimo');

    // Asignar valores al formulario
    $('#id_producto').val(id_producto);
    $('#nombre').val(nombre);
    $('#descripcion').val(descripcion);
    $('#marca').val(marca);
    $('#cantidad_mayor').val(cantidadMayor);
    $('#precio_mayor').val(precioMayor);
    $('#precio_detal').val(precioDetal);
    $('#stock_maximo').val(stockMaximo);
    $('#stock_minimo').val(stockMinimo);

    // Mantener la lógica original de búsqueda de categoría
    const categoriaSelect = $("#categoria option").filter(function () {
        return $(this).text().trim() === categoriaTexto;
    }).val();

    if (categoriaSelect !== undefined) {
        $('#categoria').val(categoriaSelect);
    } else {
        $('#categoria').val('');
    }

    $('#accion').val('modificar');

    // **Corrección en la imagen**  
    $('#imagenActual').val(imagenSrc); // Se guarda la imagen actual correctamente  
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
  Swal.fire({
    title: '¿Eliminar producto?',
    text: '¿Desea eliminar este producto?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      const datos = new FormData();
      datos.append('id_producto', id_producto);
      datos.append('eliminar', 'eliminar');
      enviaAjax(datos); // Aquí sí usas tu flujo normal con muestraMensaje()
    }
  });
}

function cambiarEstatusProducto(id_producto, estatus_actual) {
  Swal.fire({
      title: estatus_actual == 2 ? '¿Reactivar producto?' : '¿Desactivar producto?',
      text: estatus_actual == 2 ? '¿Quieres volver a activar este producto?' : '¿Quieres desactivar este producto?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: estatus_actual == 2 ? 'Sí, activar' : 'Sí, desactivar',
      cancelButtonText: 'Cancelar'
  }).then((result) => {
      if (result.isConfirmed) {
          const datos = new FormData();
          datos.append('id_producto', id_producto);
          datos.append('estatus_actual', estatus_actual);
          datos.append('accion', 'cambiarEstatus');
          enviaAjax(datos)
        }
      });
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
        try {
          var lee = JSON.parse(respuesta);
          if (lee.accion == 'consultar') {
            crearConsulta(lee.datos);
          }
          else if (lee.accion == 'incluir') {
            if (lee.respuesta == 1) {
              $('#u')[0].reset();
              muestraMensaje("success", 1000, "Éxito", lee.mensaje || "Producto registrado exitosamente");
              setTimeout(function () {
                location.href = "?pagina=producto";
              }, 1000);
            } else {
              muestraMensaje("error", 3000, "Error", lee.mensaje || "Error al registrar el producto");
              // No redirigir en caso de error para que el usuario pueda ver el mensaje
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
            }
          } else if (lee.accion == 'cambiarEstatus') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha Cambiado el estatus con con éxito", "Los datos se han borrado correctamente");
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
	$("#nombre").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#nombre").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#snombre"),"Solo letras  entre 3 y 30 caracteres");
	});
	
	$("#marca").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	
	$("#marca").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
		$(this),$("#smarca"),"Solo letras  entre 3 y 30 caracteres");
	});
	
	$("#cantidad_mayor").on("keypress",function(e){
		validarkeypress(/^[0-9-\b]*$/,e);
	});
	
	$("#cantidad_mayor").on("keyup",function(){
		validarkeyup(/^[0-9]{1,8}$/,$(this),
		$("#scantidad_mayor"),"Solo numeros hasta 8 digitos");
	});
	
	$("#precio_detal").on("keypress", function(e) {
    validarkeypress(/^[0-9.]$/, e);
  });
	
  $("#precio_detal").on("keyup", function() {
    let precioDetal = parseFloat($(this).val());
    let precioMayor = parseFloat($("#precio_mayor").val());

    if (precioDetal < precioMayor) {
        $("#sprecio_detal").text("El precio al detal no puede ser menor que el precio al mayor");
        $(this).val("").removeClass('is-valid').addClass('is-invalid');
    } else {
        $("#sprecio_detal").text("");
        validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/, $(this), $("#sprecio_detal"), "Solo numeros hasta 8 digitos y 2 decimales");
    }
});
  
	$("#precio_mayor").on("keypress", function(e) {
    validarkeypress(/^[0-9.]$/, e);
  });
	
  $("#precio_mayor").on("keyup", function() {
    let precioDetal = parseFloat($("#precio_detal").val());
    let precioMayor = parseFloat($(this).val());

    if (precioMayor > precioDetal) {
        $("#sprecio_mayor").text("El precio al mayor no puede ser mayor que el precio al detal");
        $(this).val("").removeClass('is-valid').addClass('is-invalid');
    } else {
        $("#sprecio_mayor").text("");
        validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/, $(this), $("#sprecio_mayor"), "Solo numeros hasta 8 digitos y 2 decimales");
    }
});



$("#stock_maximo").on("keypress", function(e){
    validarkeypress(/^[0-9-\b]*$/, e);
});

$("#stock_maximo").on("keyup", function() {
  let stockMaximo = parseInt($(this).val());
  let stockMinimo = parseInt($("#stock_minimo").val());

  if (stockMaximo < stockMinimo) {
      $("#sstock_maximo").text("El stock máximo no puede ser menor que el stock mínimo");
      $(this).val("").removeClass('is-valid').addClass('is-invalid');
  } else {
      $("#sstock_maximo").text("");
      validarkeyup(/^[0-9]{1,8}$/, $(this), $("#sstock_maximo"), "Solo numeros hasta 8 digitos");
  }
});


$("#stock_minimo").on("keypress", function(e){
    validarkeypress(/^[0-9-\b]*$/, e);
});

$("#stock_minimo").on("keyup", function() {
  let stockMaximo = parseInt($("#stock_maximo").val());
  let stockMinimo = parseInt($(this).val());

  if (stockMinimo > stockMaximo) {
      $("#sstock_minimo").text("El stock mínimo no puede ser mayor que el stock máximo");
      $(this).val("").removeClass('is-valid').addClass('is-invalid');
  } else {
      $("#sstock_minimo").text("");
      validarkeyup(/^[0-9]{1,8}$/, $(this), $("#sstock_minimo"), "Solo numeros hasta 8 digitos");
  }
});

	
  
function validarenvio() {
  if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") == 0) {
      muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo nombre");
      return false;
  }
  
  else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $("#marca"), $("#smarca"), "Solo letras entre 3 y 30 caracteres") == 0) {
      muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo marca");
      return false;
  }

  else if ($("#descripcion").val().trim().length === 0) {
      muestraMensaje("error", 2000, "Error", "La descripción no puede estar vacía");
      return false;
  }
  
  else if (validarkeyup(/^[0-9]{1,8}$/,
      $("#cantidad_mayor"), $("#scantidad_mayor"), "Solo numeros hasta 8 digitos") == 0) {
      muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo cantidad mayor");
      return false;
  }

  
  else if (validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/,
  $("#precio_detal"), $("#sprecio_detal"), "Solo numeros hasta 8 digitos y 2 decimales") == 0) {
    muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo precio detal");
    return false;
  }
  
  else if (validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/,
  $("#precio_mayor"), $("#sprecio_mayor"), "Solo numeros hasta 8 digitos y 2 decimales") == 0) {
    muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo precio mayor");
    return false;
  }
  else if (validarkeyup(/^[0-9]{1,8}$/,
      $("#stock_maximo"), $("#sstock_maximo"), "Solo numeros hasta 8 digitos") == 0) {
      muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo stock máximo");
      return false;
  }

  else if (validarkeyup(/^[0-9]{1,8}$/,
      $("#stock_minimo"), $("#sstock_minimo"), "Solo numeros hasta 8 digitos") == 0) {
      muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo stock mínimo");
      return false;
  }

  else if ($("#categoria").val() === null || $("#categoria").val() === "") {
      muestraMensaje("error", 2000, "Error", "Debes seleccionar una categoría");
      return false;
  }
  
  return true;
}


  function validarkeypress(er,e){
	
    key = e.keyCode;
    
    
      tecla = String.fromCharCode(key);
    
    
      a = er.test(tecla);
    
      if(!a){
    
      e.preventDefault();
      }
    
      
  }
  //Función para validar por keyup
  function validarkeyup(er, $input, $mensaje, mensaje) {
    const valor = $input.val().trim();
    if (er.test(valor)) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $mensaje.text('');
        return 1;
    }
    else {
        $input.removeClass('is-valid').addClass('is-invalid');
        $mensaje.text(mensaje);
        return 0;
    }
}
  
// Inicializar Driver.js
$('#btnAyuda').on("click", function () {
  
  const driver = window.driver.js.driver;
  
  const driverObj = new driver({
    nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior',
    popoverClass: 'driverjs-theme',
    closeBtn:false,
    steps: [
      { element: '.table-color', popover: { title: 'Tabla de productos', description: 'Aqui es donde se guardaran los registros de productos', side: "left", }},
      { element: '#btnAbrirRegistrar', popover: { title: 'Boton de registrar', description: 'Darle click aqui te llevara a un modal para poder registrar', side: "bottom", align: 'start' }},
      { element: '.modificar', popover: { title: 'Modificar producto', description: 'Este botón te permite editar la información de un producto registrado.', side: "left", align: 'start' }},
      { element: '.eliminar', popover: { title: 'Eliminar producto', description: 'Usa este botón para eliminar un producto de la lista.', side: "left", align: 'start' }},
      { element: '.ver-detalles', popover: { title: 'Ver detalles', description: 'Haz clic aquí para ver más información sobre un producto específico.', side: "left", align: 'start' }},
      { element: '.btn-desactivar', popover: { title: 'Cambiar estatus', description: 'Este botón te permite desactivar o activar un producto', side: "left", align: 'start' }},
      { element: '.dt-search', popover: { title: 'Buscar', description: 'Te permite buscar un producto en la tabla', side: "right", align: 'start' }},
      { popover: { title: 'Eso es todo', description: 'Este es el fin de la guia espero hayas entendido'} }
    ]
  });
  
  // Iniciar el tour
  driverObj.drive();
});

// Ejecutar la guía interactiva cuando la página cargue

function verificarProductoDuplicado(selectActual) {
    const productoId = selectActual.value;
    let filasDuplicadas = [];
    let cantidadTotal = 0;

    // Buscar todas las filas que tienen el mismo producto
    document.querySelectorAll('.producto-select-venta').forEach(select => {
        if (select !== selectActual && select.value === productoId) {
            filasDuplicadas.push(select.closest('.producto-fila'));
            cantidadTotal += parseInt(select.closest('.producto-fila').querySelector('.cantidad-input-venta').value) || 0;
        }
    });

    return { filasDuplicadas, cantidadTotal };
}

function inicializarEventosProducto(fila) {
    const selectProducto = fila.querySelector('.producto-select-venta');
    const inputCantidad = fila.querySelector('.cantidad-input-venta');
    const inputPrecio = fila.querySelector('.precio-input-venta');
    const btnRemover = fila.querySelector('.remover-producto-venta');
    const btnAgregar = fila.querySelector('.agregar-producto-venta');
    const stockInfo = fila.querySelector('.stock-info');

    // Inicializar evento para agregar producto
    if (btnAgregar) {
        btnAgregar.addEventListener('click', function() {
            const nuevaFila = fila.cloneNode(true);
            
            // Limpiar valores de la nueva fila
            nuevaFila.querySelector('.producto-select-venta').value = '';
            nuevaFila.querySelector('.precio-input-venta').value = '0.00';
            nuevaFila.querySelector('.subtotal-venta').textContent = '0.00';
            nuevaFila.querySelector('.cantidad-input-venta').value = '1';
            nuevaFila.querySelector('.stock-info').textContent = '';
            
            // Insertar la nueva fila después de la fila actual
            fila.parentNode.insertBefore(nuevaFila, fila.nextSibling);
            
            // Inicializar eventos en la nueva fila
            inicializarEventosProducto(nuevaFila);
            actualizarTotal();
        });
    }

    selectProducto.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value === '') {
            inputPrecio.value = '0.00';
            inputCantidad.value = '1';
            inputCantidad.removeAttribute('max');
            stockInfo.textContent = '';
            calcularSubtotal(fila);
            return;
        }

        // Verificar si el producto ya está en otra fila
        const { filasDuplicadas, cantidadTotal } = verificarProductoDuplicado(this);
        
        if (filasDuplicadas.length > 0) {
            Swal.fire({
                title: 'Producto duplicado',
                text: 'Este producto ya está en la lista. ¿Deseas sumar la cantidad a la fila existente?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, sumar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Sumar la cantidad a la primera fila encontrada
                    const filaExistente = filasDuplicadas[0];
                    const cantidadExistente = parseInt(filaExistente.querySelector('.cantidad-input-venta').value) || 0;
                    const cantidadNueva = parseInt(inputCantidad.value) || 1;
                    const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                    
                    if (cantidadExistente + cantidadNueva <= stock) {
                        filaExistente.querySelector('.cantidad-input-venta').value = cantidadExistente + cantidadNueva;
                        calcularSubtotal(filaExistente);
                        
                        // Limpiar la fila actual
                        this.value = '';
                        inputPrecio.value = '0.00';
                        inputCantidad.value = '1';
                        stockInfo.textContent = '';
                        calcularSubtotal(fila);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Stock insuficiente',
                            text: `Solo hay ${stock} unidades disponibles de este producto`
                        });
                        this.value = '';
                    }
                } else {
                    // Si el usuario cancela, limpiar la selección
                    this.value = '';
                    inputPrecio.value = '0.00';
                    inputCantidad.value = '1';
                    stockInfo.textContent = '';
                    calcularSubtotal(fila);
                }
            });
            return;
        }
        
        // Si no hay duplicados, continuar con el comportamiento normal
        const precio = selectedOption.getAttribute('data-precio');
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        
        inputPrecio.value = precio || '0.00';
        inputCantidad.value = '1';
        inputCantidad.setAttribute('max', stock);
        
        stockInfo.textContent = `Stock: ${stock}`;
        if (stock === 0) {
            inputCantidad.classList.add('is-invalid');
            Swal.fire({
                icon: 'warning',
                title: 'Sin stock',
                text: 'Este producto no tiene unidades disponibles'
            });
        } else {
            inputCantidad.classList.remove('is-invalid');
        }
        
        calcularSubtotal(fila);
    });

    // ... resto del código de inicializarEventosProducto ...
}
