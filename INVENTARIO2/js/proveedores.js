function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaproveedor")) {
        $("#tablaproveedor").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaproveedor")) {
        $("#tablaproveedor").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron proveedores",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay proveedores registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar proveedor...",
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
            fixedHeader: false,
            order: [[0, "asc"]],
        });
    }
}

$(document).ready(function() {
    consultar(); // Llama a la función consultar al cargar el documento

    // Validaciones para el campo número de documento
    $("#numero_documento").on("keypress", function(e) {
        if ($("#tipo_documento").val() === "RIF") {
            validarkeypress(/^[VJE0-9-\b]*$/, e); // Permite V,J,E,números y guión
        } else {
            validarkeypress(/^[0-9-V\b]*$/, e); // Valida cédula
        }
    });

    // Validaciones para el campo número de documento al soltar la tecla
    $("#numero_documento").on("keyup", function() {
        var tipoDocumento = $("#tipo_documento").val();
        if (tipoDocumento === "Cédula") {
            validarkeyup(/^[0-9]{7,8}$/, $(this), $("#snumero_documento"), "El formato de CI debe ser 1234567 o 12345678");
        } else if (tipoDocumento === "RIF") {
            validarkeyup(/^[VJE]{1}-[0-9]{9}$/, $(this), $("#snumero_documento"), "El formato de RIF debe ser V/J/E-123456789");
        } else {
            validarkeyup(/^[0]{0}[0]{0}$/, $(this), $("#snumero_documento"), "Debe seleccionar el tipo de documento");
        }
    });

    // Validaciones para el campo nombre
    $("#nombre").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e); // Solo letras y espacios
    });

    $("#nombre").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });

    // Validaciones para el campo dirección
    $("#direccion").on("keypress", function(e) {
        validarkeypress(/^[^"']*$/, e); // Letras, números, espacios, comas y puntos
    });

    $("#direccion").on("keyup", function() {
        validarkeyup(/^[^"']{1,100}$/, $(this), $("#sdireccion"), "La dirección debe tener entre 1 y 100 caracteres");
    });

    // Validaciones para el campo correo
    $("#correo").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z0-9\s,.\-@]*$/, e); // Formato de correo
    });

    $("#correo").on("keyup", function() {
        validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $(this), $("#scorreo"), "El formato de correo electrónico debe ser ejemplo@correo.com");
    });

    // Validaciones para el campo teléfono
    $("#telefono").on("keypress", function(e) {
        validarkeypress(/^[0-9\b]*$/, e); // Solo números
    });

    $("#telefono").on("keyup", function() {
        validarkeyup(/^0[0-9]{10}$/, $(this), $("#stelefono"), "El formato de teléfono debe ser 04120000000");
    });

    // Validaciones para el campo catálogo
    $("#catalogo").on("keypress", function(e) {
        validarkeypress(/^[^"']*$/, e); // Solo letras
    });

    $("#catalogo").on("keyup", function() {
        validarkeyup(/^[^"']{1,100}$/, $(this), $("#scatalogo"), "El catalogo debe tener un maximo de 100 caracteres");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function() {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo proveedor
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo proveedor?",
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
                        datos.append('accion', 'incluir'); // Acción de incluir
                        // Se agregan los datos del proveedor
                        datos.append('tipo_documento', $("#tipo_documento").val());
                        datos.append('numero_documento', $("#numero_documento").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('direccion', $("#direccion").val());
                        datos.append('correo', $("#correo").val());
                        datos.append('telefono', $("#telefono").val());
                        datos.append('catalogo', $("#catalogo").val());	
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]);
                        }
                        if ($("#catalogo_archivo")[0].files[0]) {
                            datos.append('catalogo_archivo', $("#catalogo_archivo")[0].files[0]);
                        }					
                        enviaAjax(datos);
                    }
                }
            });
            }} 
            else if ($(this).text() == "MODIFICAR") {
                if (validarenvio()) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                    });
                    swalWithBootstrapButtons.fire({
                        title: "¿Estás seguro?",
                        text: "¿Deseas modificar la información de este proveedor?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Sí, modificar",
                        cancelButtonText: "No, cancelar",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (validarenvio()) {
                                var datos = new FormData();
                        datos.append('accion', 'modificar'); // Acción de modificar
                        // Se agregan los datos del proveedor
                        datos.append('tipo_documento', $("#tipo_documento").val());
                        datos.append('numero_documento', $("#numero_documento").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('direccion', $("#direccion").val());
                        datos.append('correo', $("#correo").val());
                        datos.append('telefono', $("#telefono").val());
                        datos.append('catalogo', $("#catalogo").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]);
                        }
                        if ($("#catalogo_archivo")[0].files[0]) {
                            datos.append('catalogo_archivo', $("#catalogo_archivo")[0].files[0]);
                        } else if ($("#catalogo_archivo_actual").val()) {
                            datos.append('catalogo_archivo', $("#catalogo_archivo_actual").val());
                        }									
                        enviaAjax(datos);
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "La información de este proveedor no ha sido modificada",
                        icon: "error"
                    });
                }
            });
                }}
                if ($(this).text() == "ELIMINAR") {
                    var tipoDocumento = $("#tipo_documento").val();
                    var numeroDocumento = $("#numero_documento").val();
                    var validacion;
                    if (tipoDocumento === "Cédula") {
                        validacion = validarkeyup(/^[0-9]{7,8}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de CI debe ser 1234567 o 12345678");
                    } else if (tipoDocumento === "RIF") {
                        validacion = validarkeyup(/^[VJE]{1}[-]{1}[0-9]{9}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de RIF debe ser V/J/E-123456789");
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
                        datos.append('accion', 'eliminar'); // Acción de eliminar
                        datos.append('numero_documento', numeroDocumento);
                        enviaAjax(datos); // Envío de datos al servidor
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "Proveedor no eliminado",
                            icon: "error"
                        });
                    }
                });
                    }
                }
            });

$("#incluir").on("click", function() {
        limpia(); // Limpia los campos
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón
        $("#modal1").modal("show"); // Muestra el modal
    });
});

function validarenvio() {
    if ($("#tipo_documento").val() === "Cédula") {
        if (validarkeyup(/^[0-9]{7,8}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de CI debe ser 1234567 o 12345678") == 0) {
            Swal.fire({
                title: "¡ERROR!",
                text: "La Cédula del proveedor es obligatoria",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            return false;
        }
    } else if ($("#tipo_documento").val() === "RIF") {
        if (validarkeyup(/^[VJE]{1}-[0-9]{9}$/, $("#numero_documento"), $("#snumero_documento"), "El formato de RIF debe ser V/J/E-123456789") == 0) {
            Swal.fire({
                title: "¡ERROR!",
                text: "El RIF del proveedor es obligatorio",
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
    if ($("#nombre").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del proveedor es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if (validarkeyup(/^[^"']{1,100}$/, $("#direccion"), $("#sdireccion"), "La dirección debe tener entre 1 y 100 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La dirección es obligatoria, debe tener un maximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });   
        return false;
    }
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
if (validarkeyup(/^0[0-9]{10}$/, $("#telefono"), $("#stelefono"), "El formato de teléfono debe ser 04120000000") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El formato de teléfono debe ser 04120000000",
        icon: "error",
        confirmButtonText: "Aceptar"
    });   
    return false;
}
if ($("#catalogo").val().trim() !== "" && 
    validarkeyup(/^[^"']{1,100}$/,
        $("#catalogo"), $("#scatalogo"), "El catalogo debe tener un maximo de 100 caracteres") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El catalogo debe tener un maximo de 100 caracteres",
        icon: "error",
        confirmButtonText: "Aceptar"
    });  
return false;
}
return true;
}

function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }    
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

function pone(pos, accion) {
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#tipo_documento").prop("disabled", true);
        $("#numero_documento").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#direccion").prop("disabled", false);
        $("#correo").prop("disabled", false);
        $("#telefono").prop("disabled", false);
        $("#catalogo").prop("disabled", false);
        $("#imagen").prop("disabled", false);
        $("#catalogo_archivo").prop("disabled", false);
        $("#ver_archivo").prop("disabled", false);
        $("#check_catalogo").hide();
    } else {
        $("#proceso").text("ELIMINAR");
        $("#tipo_documento").prop("disabled", true);
        $("#numero_documento").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#direccion").prop("disabled", true);
        $("#correo").prop("disabled", true);
        $("#telefono").prop("disabled", true);
        $("#catalogo").prop("disabled", true);
        $("#imagen").prop("disabled", true);
        $("#catalogo_archivo").prop("disabled", true);
        $("#ver_archivo").prop("disabled", true);
        $("#check_catalogo").hide();
    }
    
    // Separar el tipo de documento y el número de documento
    var documentoCompleto = $(linea).find("td:eq(1)").text().trim();
    var partes = documentoCompleto.split(":");
    var tipoDocumento = partes[0];
    var numeroDocumento = partes[1];

    $("#tipo_documento").val(tipoDocumento);
    $("#numero_documento").val(numeroDocumento);
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#direccion").val($(linea).find("td:eq(3)").text());
    $("#correo").val($(linea).find("td:eq(4)").text());
    $("#telefono").val($(linea).find("td:eq(5)").text());
    $("#catalogo").val($(linea).find("td:eq(6)").text());
    
    var catalogoHref = $(linea).find("td:eq(7) a").attr("href");
    if (catalogoHref && catalogoHref !== "#") {
        $("#ver_catalogo").attr("href", catalogoHref).show();
        $("#catalogo_archivo_actual").val(catalogoHref);
        fetch(catalogoHref)
        .then(res => res.blob())
        .then(blob => {
            const file = new File([blob], catalogoHref.split('/').pop(), { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            $("#catalogo_archivo")[0].files = dataTransfer.files;
        });
    } else {
        $("#ver_catalogo").attr("href", "").hide();
        $("#catalogo_archivo_actual").val("");
        $("#catalogo_archivo").val("");
    }

    var imagenSrc = $(linea).find("td:eq(8) img").attr("src");
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show();
        $("#imagen_url").val(imagenSrc);
        fetch(imagenSrc)
        .then(res => res.blob())
        .then(blob => {
            const file = new File([blob], imagenSrc.split('/').pop(), { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            $("#imagen")[0].files = dataTransfer.files;
        });
    } else {
        $("#imagen_actual").attr("src", "").hide();
        $("#imagen_url").val("");
    }

    $("#modal1").modal("show");
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
        beforeSend: function() { 
            $("#loader").show(); // Mostrar loader 
        },
        timeout: 10000,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar") {
                    destruyeDT();
                    $("#resultadoconsulta").html(lee.mensaje);
                    crearDT();
                } else if (lee.resultado == "incluir") {
                    if (lee.mensaje == '¡Registro guardado con exito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "El proveedor ha sido incluido con éxito.",
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
                } else if (lee.resultado == "modificar") {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Modificado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje.includes('éxito') ? "success" : "error"
                    });
                    if (lee.mensaje.includes('éxito')) {
                        $("#modal1").modal("hide");
                        consultar();
                    }
                } else if (lee.resultado == "eliminar") {
                    Swal.fire({
                        title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
                    });
                    if (lee.mensaje == '¡Registro eliminado con exito!') {
                        $("#modal1").modal("hide");
                        consultar();
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
        error: function(request, status, err) {
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
        complete: function() {
            $("#loader").hide(); // Ocultar loader al completar
        }
    });
}

function limpia() {
    $("#tipo_documento").prop("selectedIndex", 0);
    $("#numero_documento").val("");
    $("#nombre").val("");
    $("#direccion").val("");
    $("#correo").val("");
    $("#telefono").val("");
    $("#catalogo").val("");
    $("#imagen").val("");
    $("#catalogo_archivo").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#ver_catalogo").attr("href", "").hide();
    $("#tipo_documento").prop("disabled", false);
    $("#numero_documento").prop("disabled", false);
    $("#nombre").prop("disabled", false);
    $("#direccion").prop("disabled", false);
    $("#correo").prop("disabled", false);
    $("#telefono").prop("disabled", false);
    $("#catalogo").prop("disabled", false);
    $("#imagen").prop("disabled", false);
    $("#catalogo_archivo").prop("disabled", false);
    $("#ver_archivo").prop("disabled", false);
    $("#check_catalogo").hide();
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

$("#catalogo_archivo").on("change", function() {
    const file = this.files[0];
    if (file) {
        // Mostrar el check
        $("#check_catalogo").show();
    } else {
        // Ocultar el check si no hay archivo seleccionado
        $("#check_catalogo").hide();
    }
});