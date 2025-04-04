function consultar() {
    // Crea un nuevo objeto FormData y agrega la acción 'consultar'
	var datos = new FormData();
	datos.append('accion', 'consultar');
	enviaAjax(datos);	
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye si es así
	if ($.fn.DataTable.isDataTable("#tablacliente")) {
        $("#tablacliente").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablacliente")) {
        $("#tablacliente").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron clientes",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay clientes registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar cliente...",
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
        $('#tablacliente').DataTable().columns.adjust().draw();
    });
}

$(document).ready(function() {
    // Llama a la función consultar al cargar el documento
	consultar();

    // Validaciones para el campo de número de documento
    $("#numero_documento").on("keypress", function(e) {
        if ($("#tipo_documento").val() === "RIF") {
            validarkeypress(/^[VEJ0-9-\b]*$/, e);
        } else {
            validarkeypress(/^[0-9-V\b]*$/, e);
        }
    });

    // Validaciones para el campo de número de documento al soltar la tecla
    $("#numero_documento").on("keyup", function() {
        var tipoDocumento = $("#tipo_documento").val();
        if (tipoDocumento === "Cédula") {
            validarkeyup(/^[0-9]{7,8}$/, $(this), $("#snumero_documento"), "El formato de CI debe ser 1234567 o 12345678");
        } else if (tipoDocumento === "RIF") {
            validarkeyup(/^[VJE]{1}-[0-9]{9}$/, $(this), $("#snumero_documento"), "El formato de RIF debe ser V/J/E-123456789");
        }else { validarkeyup(/^[0]{0}[0]{0}$/, $(this), $("#snumero_documento"), "Debe seleccionar el tipo de documento");}
    });

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombre").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });

    // Validaciones para el campo de apellido
    $("#apellido").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });	
    $("#apellido").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#sapellido"), "Solo letras entre 3 y 30 caracteres");
    });

    // Validaciones para el campo de correo
    $("#correo").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z0-9\s,.\-@]*$/, e);
    });
    $("#correo").on("keyup", function() {
        validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $(this), $("#scorreo"), "El formato de correo electrónico debe ser ejemplo@correo.com");
    });

    // Validaciones para el campo de teléfono
    $("#telefono").on("keypress", function(e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });
    $("#telefono").on("keyup", function() {
        validarkeyup(/^0[0-9]{10}$/, $(this), $("#stelefono"), "El formato de teléfono debe ser 04120000000");
    });

    // Validaciones para el campo de dirección
    $("#direccion").on("keypress", function(e) {
        validarkeypress(/^[^"']*$/, e);
    });
    $("#direccion").on("keyup", function() {
        validarkeyup(/^[^"']{1,100}$/, $(this), $("#sdireccion"), "La dirección debe tener entre 1 y 100 caracteres");
    });	

    // Manejo de clic en el botón de proceso
    $("#proceso").on("click", function() {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo cliente
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas incluir este nuevo cliente?",
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
                        datos.append('accion', 'incluir');
                        datos.append('tipo_documento', $("#tipo_documento").val());
                        datos.append('numero_documento', $("#numero_documento").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('apellido', $("#apellido").val());
                        datos.append('correo', $("#correo").val());
                        datos.append('telefono', $("#telefono").val());
                        datos.append('direccion', $("#direccion").val());
                        enviaAjax(datos);
                    }
                }
            });
        }
    }
         else if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
            // Confirmación para modificar un cliente existente
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas modificar la información de este cliente?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sí, modificar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (validarenvio()) {
                        var datos = new FormData();
                        datos.append('accion', 'modificar');
                        datos.append('tipo_documento', $("#tipo_documento").val());
                        datos.append('numero_documento', $("#numero_documento").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('apellido', $("#apellido").val());
                        datos.append('correo', $("#correo").val());
                        datos.append('telefono', $("#telefono").val());
                        datos.append('direccion', $("#direccion").val()); 								           
                        enviaAjax(datos);
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "La información de este cliente no ha sido modificada",
                        icon: "error"
                    });
                }
            });
        }
    }

        // Manejo de eliminación de un cliente
        if ($(this).text() == "ELIMINAR") {
            var tipoDocumento = $("#tipo_documento").val();
            var numeroDocumento = $("#numero_documento").val();
            var validacion;
            if (tipoDocumento === "Cédula") {
                validacion = validarkeyup(/^[0-9]{7,8}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de CI debe ser 1234567 o 12345678");
            } else if (tipoDocumento === "RIF") {
                validacion = validarkeyup(/^[VJE]{1}-[0-9]{9}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de RIF debe ser V/J/E-123456789");
            }

            if (validacion == 0) {
                muestraMensaje("El documento debe coincidir con el formato solicitado <br/>" + "99999999");
            } else {
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
                        datos.append('accion', 'eliminar');
                        datos.append('numero_documento', numeroDocumento);
                        enviaAjax(datos);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "Cliente no eliminado",
                            icon: "error"
                        });
                    }
                });
            }
        }
    });

    // Manejo del clic en el botón incluir
$("#incluir").on("click", function() {
        limpia(); // Limpia los campos
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón
        $("#modal1").modal("show"); // Muestra el modal
    });	
});

// Función para validar el envío de datos
function validarenvio() {
    // Validaciones para el tipo de documento
    if ($("#tipo_documento").val() === "Cédula") {
        if (validarkeyup(/^[0-9]{7,8}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de CI debe ser 1234567 o 12345678") == 0) {
            Swal.fire({
                title: "¡ERROR!",
                text: "La Cedula del cliente es obligatoria",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            return false;
        }
    } else if ($("#tipo_documento").val() === "RIF") {
        if (validarkeyup(/^[VJE]{1}-[0-9]{9}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de RIF debe ser V/J/E-123456789") == 0) {
            Swal.fire({
                title: "¡ERROR!",
                text: "El RIF del cliente es obligatorio",
                icon: "error",
                confirmButtonText: "Aceptar"
            });    
            return false;
        }
    } else {
        Swal.fire({
            title: "¡ERROR!",
            text: "El tipo de documento es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para nombre y apellido
    if ($("#nombre").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del cliente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if ($("#apellido").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El apellido del cliente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para el formato de nombre y apellido
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre debe contener solo letras y tener entre 3 y 30 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#apellido"), $("#sapellido"), "Solo letras entre 3 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El apellido debe contener solo letras y tener entre 3 y 30 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });     
        return false;
    }

    // Validaciones para el correo
    if ($("#correo").val().trim() !== "" && 
        validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $("#correo"), $("#scorreo"), "El formato de correo electrónico debe ser ejemplo@correo.com") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El formato de correo electrónico debe ser ejemplo@correo.com",
            icon: "error",
            confirmButtonText: "Aceptar"
        });  
    return false;
}

    // Validaciones para el teléfono
if (validarkeyup(/^0[0-9]{10}$/, $("#telefono"), $("#stelefono"), "El formato de teléfono debe ser 04120000000") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El formato de teléfono debe ser 04120000000",
        icon: "error",
        confirmButtonText: "Aceptar"
    });   
    return false;
}

    // Validaciones para la dirección
if (validarkeyup(/^[^"']{1,100}$/, $("#direccion"), $("#sdireccion"), "La dirección debe tener entre 1 y 100 caracteres") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "La dirección debe tener un máximo de 100 caracteres",
        icon: "error",
        confirmButtonText: "Aceptar"
    });   
    return false;
}
    return true; // Si todas las validaciones pasan, retorna verdadero
}

function validarkeypress(er, e) {
    // Función para validar la tecla presionada
	key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault(); // Previene la acción si no coincide con la expresión regular
    }    
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    // Función para validar el valor al soltar la tecla
	a = er.test(etiqueta.val());
	if (a) {
		etiquetamensaje.text(""); // Limpia el mensaje de error
		return 1; // Retorna 1 si es válido
	} else {
		etiquetamensaje.text(mensaje); // Muestra el mensaje de error
		return 0; // Retorna 0 si no es válido
	}
}

function pone(pos, accion) {
    // Función para llenar el formulario con los datos del cliente seleccionado
	linea = $(pos).closest('tr');
	if (accion == 0) {
		$("#proceso").text("MODIFICAR");
		$("#tipo_documento").prop("disabled", true);
		$("#numero_documento").prop("disabled", true);
		$("#nombre").prop("disabled", false);
		$("#apellido").prop("disabled", false);
		$("#correo").prop("disabled", false);
		$("#telefono").prop("disabled", false);
		$("#direccion").prop("disabled", false);
	} else {
		$("#proceso").text("ELIMINAR");
		$("#tipo_documento").prop("disabled", true);
		$("#numero_documento").prop("disabled", true);
		$("#nombre").prop("disabled", true);
		$("#apellido").prop("disabled", true);
		$("#correo").prop("disabled", true);
		$("#telefono").prop("disabled", true);
		$("#direccion").prop("disabled", true);		
	}


    // Separar el tipo de documento y el número de documento
    var documentoCompleto = $(linea).find("td:eq(1)").text().trim();
    var partes = documentoCompleto.split(":");
    var tipoDocumento = partes[0];
    var numeroDocumento = partes[1];

	// Llena los campos del formulario con los datos de la fila seleccionada
    $("#tipo_documento").val(tipoDocumento);
    $("#numero_documento").val(numeroDocumento);
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#apellido").val($(linea).find("td:eq(3)").text());
    $("#correo").val($(linea).find("td:eq(4)").text());
    $("#telefono").val($(linea).find("td:eq(5)").text());
    $("#direccion").val($(linea).find("td:eq(6)").text());				
	$("#modal1").modal("show"); // Muestra el modal
}

function enviaAjax(datos) {
    // Función para enviar datos a través de AJAX
	$.ajax({
		async: true,
		url: "", // URL del servidor
		type: "POST",
		contentType: false,
		data: datos,
		processData: false,
		cache: false,
		beforeSend: function () {},
		timeout: 10000, // Tiempo de espera
		success: function (respuesta) {
			try {
				var lee = JSON.parse(respuesta); // Intenta parsear la respuesta JSON
				if (lee.resultado == "consultar") {
					destruyeDT();	
					$("#resultadoconsulta").html(lee.mensaje);
					crearDT(); // Crea la tabla con los nuevos datos
				} else if (lee.resultado == "incluir") {
					if (lee.mensaje == '¡Registro guardado con exito!') {
						Swal.fire({
							title: "¡Incluido!",
							text: "El cliente ha sido incluido con éxito.",
							icon: "success"
						});
						$("#modal1").modal("hide");
						consultar(); // Actualiza la lista de clientes
					} else {
						Swal.fire({
							title: "Error",
							text: lee.mensaje,
							icon: "error"
						});
					}
				} else if (lee.resultado == "modificar") {
					Swal.fire({
						title: lee.mensaje.includes('éxito') ? "¡Modificado!" : "Error",
						text: lee.mensaje,
						icon: lee.mensaje.includes('éxito') ? "success" : "error"
					});
					if (lee.mensaje.includes('éxito')) {
						$("#modal1").modal("hide");
						consultar(); // Actualiza la lista de clientes
					}
				} else if (lee.resultado == "eliminar") {
					Swal.fire({
						title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
						text: lee.mensaje,
						icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
					});
					if (lee.mensaje == '¡Registro eliminado con exito!') {
						$("#modal1").modal("hide");
						consultar(); // Actualiza la lista de clientes
					}
				} else if (lee.resultado == "error") {
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
		complete: function () {},
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
    beforeSend: function () {
        $("#loader").show(); // Mostrar loader
    },
    timeout: 10000, 
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "consultar") {
          destruyeDT();	
          $("#resultadoconsulta").html(lee.mensaje);
          crearDT();
      }
      else if (lee.resultado == "incluir") {
          if (lee.mensaje == '¡Registro guardado con exito!') {
            Swal.fire({
              title: "¡Incluido!",
              text: "El cliente ha sido incluido con éxito.",
              icon: "success"
          });
            $("#modal1").modal("hide");
            consultar();
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
      if(lee.mensaje.includes('éxito')){
        $("#modal1").modal("hide");
        consultar();
    }
}
else if (lee.resultado == "eliminar") {
  Swal.fire({
    title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
    text: lee.mensaje,
    icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
});
  if(lee.mensaje == '¡Registro eliminado con exito!'){
    $("#modal1").modal("hide");
    consultar();
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
    // Función para limpiar los campos del formulario
    $("#tipo_documento").prop("selectedIndex", 0);
    $("#numero_documento").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#correo").val("");
    $("#telefono").val("");
    $("#direccion").val("");
    // Habilita los campos del formulario
    $("#tipo_documento").prop("disabled", false);
    $("#numero_documento").prop("disabled", false); 
    $("#nombre").prop("disabled", false);   
    $("#apellido").prop("disabled", false); 
    $("#correo").prop("disabled", false);   
    $("#telefono").prop("disabled", false); 
    $("#direccion").prop("disabled", false);                    
}