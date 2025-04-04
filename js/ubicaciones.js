function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
	if ($.fn.DataTable.isDataTable("#tablaubicacion")) {
        $("#tablaubicacion").DataTable().destroy();
    }
}

function crearDT() {
    // Crea la tabla solo si no existe
    if (!$.fn.DataTable.isDataTable("#tablaubicacion")) {
        $("#tablaubicacion").DataTable({
          language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron ubicaciones",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay ubicaciones registradas",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "<i class='bi bi-search'></i>",
            searchPlaceholder: "Buscar ubicación...",
            paginate: {
              first: "Primera",
              last: "Última",
              next: "Siguiente",
              previous: "Anterior",
          },
      },
      pageLength: 5, // Establece el número de registros por página a 5
      lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], // Opciones de número de registros por página
      autoWidth: false,
      scrollX: true,
      scrollCollapse: true,
      fixedHeader: false,
      order: [[0, "asc"]],
      responsive: true,
  });
    }
    $(window).resize(function() {
      $('#tablaubicacion').DataTable().columns.adjust().draw();
  });
}

$(document).ready(function() {
	consultar(); // Llama a la función consultar al cargar el documento

	// Validaciones para el campo nombre
   $("#nombre").on("keypress", function(e) {
    validarkeypress(/^[^"']*$/, e); 
});
   $("#nombre").on("keyup", function() {
    validarkeyup(/^[^"']{3,30}$/, $(this), $("#snombre"), "Debe tener entre 3 y 30 caracteres");
});

    // Validaciones para el campo descripción
   $("#descripcion").on("keypress", function(e) {
        validarkeypress(/^[^"']*$/, e); // Letras, números, espacios, comas y puntos
    });
   $("#descripcion").on("keyup", function() {
    validarkeyup(/^[^"']{0,100}$/, $(this), $("#sdescripcion"), "La descripcion debe tener un máximo de 100 caracteres");
});	

	// Manejo de clics en el botón de proceso
   $("#proceso").on("click", function() {
    if ($(this).text() == "INCLUIR") {
        if (validarenvio()) {
            // Confirmación para incluir una nueva ubicación
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas incluir esta nueva ubicación?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, incluir",
                cancelButtonText: "No, Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    if (validarenvio()) {
                        var datos = new FormData();
                        datos.append('accion', 'incluir'); // Acción para incluir
                        datos.append('nombre', $("#nombre").val());
                        datos.append('descripcion', $("#descripcion").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos
                    }
                }
            });
        }}
        else if ($(this).text() == "MODIFICAR") {
         if (validarenvio()) {
            // Confirmación para modificar una ubicación
            const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

            swalWithBootstrapButtons.fire({
              title: "¿Estás seguro?",
              text: "¿Deseas modificar esta ubicación?",
              icon: "question",
              showCancelButton: true,
              confirmButtonText: "Sí, modificar",
              cancelButtonText: "No, cancelar",
              reverseButtons: true
          }).then((result) => {
              if (result.isConfirmed) {
                if (validarenvio()) {
                  var datos = new FormData();
                  datos.append('accion', 'modificar'); // Acción para modificar
                  datos.append('nombre', $("#nombre").val());
                  datos.append('descripcion', $("#descripcion").val());
                  if ($("#imagen")[0].files[0]) {
                    datos.append('imagen', $("#imagen")[0].files[0]);
                }
                enviaAjax(datos); // Envía los datos
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
              title: "Cancelado",
              text: "La ubicación no ha sido modificada",
              icon: "error"
          });
        }
    });
      }}
      else if ($(this).text() == "ELIMINAR") {
       const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
         confirmButton: "btn btn-success",
         cancelButton: "btn btn-danger"
     },
     buttonsStyling: false
 });
       swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
         var datos = new FormData();
                   datos.append('accion', 'eliminar'); // Acción para eliminar
                   datos.append('nombre', $("#nombre").val());
                   enviaAjax(datos); // Envía los datos
               } else if (result.dismiss === Swal.DismissReason.cancel) {
                 swalWithBootstrapButtons.fire({
                  title: "Cancelado",
                  text: "Ubicación no eliminada",
                  icon: "error"
              });
             }
         });
}
});

// Manejo del clic en el botón incluir
   $("#incluir").on("click", function() {
	limpia(); // Limpia los campos
	$("#proceso").text("INCLUIR"); // Cambia el texto del botón
	$("#modal1").modal("show"); // Muestra el modal
});	
});

function validarenvio() {
    // Valida el envío de datos
    if (validarkeyup(/^[^"']{3,30}$/, $("#nombre"), $("#snombre"), "Texto entre 3 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre de la ubicación es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false; // Retorna falso si hay error
    } else if (validarkeyup(/^[^"']{0,100}$/, $("#descripcion"), $("#sdescripcion"), "La descripcion debe tener un máximo de 100 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La descripcion debe tener un máximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false; // Retorna falso si hay error
    }
    return true; // Retorna verdadero si todo es correcto
}

function validarkeypress(er, e) {	
	key = e.keyCode;
    tecla = String.fromCharCode(key);	
    a = er.test(tecla);	
    if (!a) {
      e.preventDefault(); // Previene la entrada si no coincide con la expresión regular
  }    
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
	// Valida el valor del campo al soltar la tecla
	a = er.test(etiqueta.val());
	if (a) {
		etiquetamensaje.text(""); // Limpia el mensaje si es válido
		return 1; // Retorna 1 si es válido
	} else {
		etiquetamensaje.text(mensaje); // Muestra el mensaje de error
		return 0; // Retorna 0 si no es válido
	}
}

function pone(pos, accion) {
    // Maneja la selección de una fila en la tabla
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR"); // Cambia el texto a MODIFICAR
        $("#nombre").prop("disabled", true); // Desactiva el campo nombre
        $("#descripcion").prop("disabled", false); // Activa el campo descripción
        $("#imagen").prop("disabled", false); // Activa el campo imagen
    } else {
        $("#proceso").text("ELIMINAR"); // Cambia el texto a ELIMINAR
        $("#nombre").prop("disabled", true); // Desactiva el campo nombre
        $("#descripcion").prop("disabled", true); // Desactiva el campo descripción
        $("#imagen").prop("disabled", true); // Desactiva el campo imagen
    }
    $("#nombre").val($(linea).find("td:eq(1)").text()); // Rellena el campo nombre
    $("#descripcion").val($(linea).find("td:eq(2)").text()); // Rellena el campo descripción

    var imagenSrc = $(linea).find("td:eq(3) img").attr("src"); // Obtiene la fuente de la imagen
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show(); // Muestra la imagen actual
        $("#imagen_url").val(imagenSrc);       
        fetch(imagenSrc)
        .then(res => res.blob())
        .then(blob => {
            const file = new File([blob], imagenSrc.split('/').pop(), { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            $("#imagen")[0].files = dataTransfer.files; // Asigna el archivo a input de imagen
        });
    } else {
        $("#imagen_actual").attr("src", "").hide(); // Oculta la imagen si no existe
        $("#imagen_url").val("");
    }
    $("#modal1").modal("show"); // Muestra el modal
}

function enviaAjax(datos) {
  // Envía datos al servidor mediante AJAX
  $.ajax({
    async: true,
    url: "", // URL del servidor
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () {
        $("#loader").show(); // Mostrar loader 
    },
    timeout: 10000, // Tiempo de espera
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta); // Intenta parsear la respuesta
        if (lee.resultado == "consultar") {
            destruyeDT();	
            $("#resultadoconsulta").html(lee.mensaje); // Muestra el mensaje de consulta
            crearDT(); // Crea la tabla
        }
        else if (lee.resultado == "incluir") {
            if (lee.mensaje == '¡Registro guardado con éxito!') {
                Swal.fire({
                    title: "¡Incluido!",
                    text: "La ubicación ha sido incluida con éxito.",
                    icon: "success"
                });
                $("#modal1").modal("hide"); // Oculta el modal
                consultar(); // Vuelve a consultar
            } else {
                Swal.fire({
                    title: "Error",
                    text: lee.mensaje,
                    icon: "error"
                });
            }
        }
        else if (lee.resultado == "modificar") {
            Swal.fire({
                title: lee.mensaje.includes('éxito') ? "¡Modificado!" : "Error",
                text: lee.mensaje,
                icon: lee.mensaje.includes('éxito') ? "success" : "error"
            });
            if (lee.mensaje.includes('éxito')) {
                $("#modal1").modal("hide"); // Oculta el modal
                consultar(); // Vuelve a consultar
            }
        }
        else if (lee.resultado == "eliminar") {
            Swal.fire({
                title: lee.mensaje == '¡Registro eliminado con éxito!' ? "¡Eliminado!" : "Error",
                text: lee.mensaje,
                icon: lee.mensaje == '¡Registro eliminado con éxito!' ? "success" : "error"
            });
            if (lee.mensaje == '¡Registro eliminado con éxito!') {
                $("#modal1").modal("hide"); // Oculta el modal
                consultar(); // Vuelve a consultar
            }
        }
        else if (lee.resultado == "error") {
            Swal.fire({
                title: "Error",
                text: lee.mensaje,
                icon: "error"
            });
        }
    } catch (e) {
        Swal.fire({
            title: "Error",
            text: "Error en JSON: " + e.name,
            icon: "error"
        });
    }
},
error: function (request, status, err) {
  // Manejo de errores en la solicitud AJAX
  if (status == "timeout") {
    Swal.fire({
        title: "Error",
        text: "Servidor ocupado, intente de nuevo",
        icon: "error"
    });
} else {
    Swal.fire({
        title: "Error",
        text: "ERROR: " + request + status + err,
        icon: "error"
    });
}
},
complete: function () {
    $("#loader").hide(); // Ocultar loader al completar
}
});
}

function limpia() {
	// Limpia los campos del formulario
	$("#nombre").val("");
	$("#descripcion").val("");
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide(); // Oculta la imagen actual
    $("#nombre").prop("disabled", false); // Habilita el campo nombre
    $("#descripcion").prop("disabled", false); // Habilita el campo descripción
    $("#imagen").prop("disabled", false); // Habilita el campo imagen
}


$("#imagen").on("change", function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $("#imagen_actual")
            .attr("src", e.target.result)
            .show();
        };
        reader.readAsDataURL(file);
    } else {
        $("#imagen_actual")
        .attr("src", "")
        .hide();
    }
});