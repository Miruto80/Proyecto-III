function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaproducto")) {
        $("#tablaproducto").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaproducto")) {
        $("#tablaproducto").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron productos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay productos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar producto...",
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
            order: [[0, "asc"]], // Ordena por la segunda columna
        });
    }
}

$(document).ready(function () {
    consultar(); // Llama a la función consultar al cargar el documento
    cargarOpciones(); // Carga las opciones para selectores

    // Validaciones para el campo de código
    $("#codigo").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#codigo").on("keyup", function () {
        validarkeyup(/^[^"']{1,50}$/, $(this), $("#scodigo"), "El Código debe tener entre 1 y 50 caracteres");
    });

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombre"), "Solo letras  entre 3 y 50 caracteres");
    });


    // Validaciones para el campo de precio de compra
    $("#precio_venta").on("keypress", function (e) {
        validarkeypress(/^[0-9.\b]*$/, e);
    });

    $("#precio_venta").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,10}$/, $(this), $("#sprecio_venta"), "El precio de compra debe ser mayor a 0");
    });

    // Validaciones para el campo de precio de venta
    $("#precio_compra").on("keypress", function (e) {
        validarkeypress(/^[0-9.\b]*$/, e);
    });

    $("#precio_compra").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,10}$/, $(this), $("#sprecio_compra"), "El precio de compra debe ser mayor a 0");
    });

    // Validaciones para el campo de stock total
    $("#stock_total").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#stock_total").on("keyup", function () {
        validarkeyup(/^[0-9]{0,10}$/, $(this), $("#sstock_total"), "El stock debe ser numeros enteros");
    });

    // Validaciones para el campo de stock mínimo
    $("#stock_minimo").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#stock_minimo").on("keyup", function () {
        validarkeyup(/^[0-9]{0,10}$/, $(this), $("#sstock_minimo"), "El stock mínimo debe ser números enteros");
    });

    // Validaciones para el campo de marca
    $("#marca").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#marca").on("keyup", function () {
        validarkeyup(/^[^"']{1,35}$/, $(this), $("#smarca"), "La marca debe tener un máximo de 35 caracteres");
    });

    // Validaciones para el campo de modelo
    $("#modelo").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#modelo").on("keyup", function () {
        validarkeyup(/^[^"']{1,35}$/, $(this), $("#smodelo"), "El modelo debe tener un máximo de 35 caracteres");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo producto
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo producto?",
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
                        // Se agregan los datos del formulario
                        datos.append('codigo', $("#codigo").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('precio_compra', $("#precio_compra").val());
                        datos.append('precio_venta', $("#precio_venta").val());
                        datos.append('stock_total', $("#stock_total").val());
                        datos.append('stock_minimo', $("#stock_minimo").val());
                        datos.append('marca', $("#marca").val());
                        datos.append('modelo', $("#modelo").val());
                        datos.append('tipo_unidad', $("#tipo_unidad").val());
                        datos.append('categoria_id', $("#categoria").val());
                        datos.append('ubicacion_id', $("#ubicacion").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                }
            });
            } }

            else if ($(this).text() == "MODIFICAR") {
            // Confirmación para modificar un producto existente
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
                        text: "¿Deseas modificar este producto?",
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
                        // Se agregan los datos del formulario
                        datos.append('codigo', $("#codigo").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('precio_compra', $("#precio_compra").val());
                        datos.append('precio_venta', $("#precio_venta").val());
                        datos.append('stock_total', $("#stock_total").val());
                        datos.append('stock_minimo', $("#stock_minimo").val());
                        datos.append('marca', $("#marca").val());
                        datos.append('modelo', $("#modelo").val());
                        datos.append('tipo_unidad', $("#tipo_unidad").val());
                        datos.append('categoria_id', $("#categoria").val());
                        datos.append('ubicacion_id', $("#ubicacion").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Mensaje de cancelación
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El producto no ha sido modificado",
                        icon: "error"
                    });
                }
            });
                } }
                else if ($(this).text() == "ELIMINAR") {
            // Validación antes de eliminar un producto
                    if (validarkeyup(/^[^"']{1,50}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 50 caracteres") == 0) {
                        muestraMensaje("El Código debe tener entre 1 y 50 caracteres");
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
                        datos.append('accion', 'eliminar'); // Acción para eliminar
                        datos.append('codigo', $("#codigo").val()); // Se agrega el código del producto
                        enviaAjax(datos); // Envía los datos al servidor
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Mensaje de cancelación
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "Producto no eliminado",
                            icon: "error"
                        });
                    }
                });
                    }
                }
            });

    // Manejo del clic en el botón incluir
$("#incluir").on("click", function () {
        limpia(); // Limpia los campos del formulario
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón a 'INCLUIR'
        $("#modal1").modal("show"); // Muestra el modal
    });

    // Inicializar Select2 en el select de tipo_unidad
$('#tipo_unidad').select2({
    placeholder: "Seleccione una opción",
    allowClear: true,
    width: '100%',
    language: {
        noResults: function() {
            return "No se encontraron resultados";
        },
        searching: function() {
            return "Buscando...";
        }
    }
});

$('#categoria').select2({
    placeholder: "Seleccione una opción",
    allowClear: true,
    width: '100%',
    language: {
        noResults: function() {
            return "No se encontraron resultados";
        },
        searching: function() {
            return "Buscando...";
        }
    }
});

$('#ubicacion').select2({
    placeholder: "Seleccione una opción",
    allowClear: true,
    width: '100%',
    language: {
        noResults: function() {
            return "No se encontraron resultados";
        },
        searching: function() {
            return "Buscando...";
        }
    }
});

});

// Función para validar el envío de datos
function validarenvio() {
    // Validaciones para cada campo del formulario
    if (validarkeyup(/^[^"']{1,50}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El código del producto es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombre"), $("#snombre"), "Solo letras  entre 3 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del producto es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[0-9.]{1,10}$/, $("#precio_compra"), $("#sprecio_compra"), "El precio de compra debe ser mayor a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El precio de compra del producto es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }     
    if (validarkeyup(/^[0-9.]{1,10}$/, $("#precio_venta"), $("#sprecio_venta"), "El precio de venta debe ser mayor a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El precio de venta del producto es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[0-9]{0,10}$/, $("#stock_total"), $("#sstock_total"), "El stock debe ser mayor a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El stock del producto es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[0-9]{1,10}$/, $("#stock_minimo"), $("#sstock_minimo"), "El stock mínimo debe ser mayor a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El stock mínimo del producto es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }
    if ($("#marca").val().trim() !== "" &&
        validarkeyup(/^[^"']{1,35}$/, $("#marca"), $("#smarca"), "La marca debe tener entre 1 y 35 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La marca debe tener entre 1 y 35 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
    return false;
}
if ($("#modelo").val().trim() !== "" &&
    validarkeyup(/^[^"']{1,35}$/, $("#modelo"), $("#smodelo"), "El modelo debe tener entre 1 y 35 caracteres") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El modelo debe tener entre 1 y 35 caracteres",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
return false;
} 

if ($("#tipo_unidad").val() === null) {
    Swal.fire({
        title: "¡ERROR!",
        text: "Debe seleccionar una presentación del producto",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
}

if ($("#categoria").val() === null) {
    Swal.fire({
        title: "¡ERROR!",
        text: "Debe seleccionar una categoría",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
}

if ($("#ubicacion").val() === null) {
    Swal.fire({
        title: "¡ERROR!",
        text: "Debe seleccionar una ubicación",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
}

if (!validarPrecios()) {
    return false;
}

return true;
}

// Función para validar la tecla presionada
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla); // Verifica si la tecla es válida
    if (!a) {
        e.preventDefault(); // Previene la acción si no es válida
    }
}

// Función para validar el campo al soltar la tecla
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val()); // Verifica el valor del campo
    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}
// Función para llenar el formulario con datos de un producto
function pone(pos, accion) {
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#precio_compra").prop("disabled", false);
        $("#precio_venta").prop("disabled", false);
        $("#stock_total").prop("disabled", false);
        $("#stock_minimo").prop("disabled", false);
        $("#marca").prop("disabled", false);
        $("#modelo").prop("disabled", false);
        $("#tipo_unidad").prop("disabled", false);
        $("#categoria").prop("disabled", false);
        $("#ubicacion").prop("disabled", false);         
        $("#imagen").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#precio_compra").prop("disabled", true);
        $("#precio_venta").prop("disabled", true);
        $("#stock_total").prop("disabled", true);
        $("#stock_minimo").prop("disabled", true);
        $("#marca").prop("disabled", true);
        $("#modelo").prop("disabled", true);
        $("#tipo_unidad").prop("disabled", true);
        $("#categoria").prop("disabled", true);
        $("#ubicacion").prop("disabled", true);                       
        $("#imagen").prop("disabled", true);
    }

    var pc = $(linea).find("td:eq(3)").text().trim();
    var pcp = pc.split("$");
    var precio_compra = pcp[1];
    var pv = $(linea).find("td:eq(4)").text().trim();
    var pvp = pc.split("$");
    var precio_venta = pcp[1];


    $("#codigo").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#precio_compra").val(precio_compra);
    $("#precio_venta").val(precio_venta);
    $("#stock_total").val($(linea).find("td:eq(5)").text().replace(/\D/g, ''));
    $("#stock_minimo").val($(linea).find("td:eq(6)").text());
    $("#marca").val($(linea).find("td:eq(7)").text());
    $("#modelo").val($(linea).find("td:eq(8)").text());
    $("#tipo_unidad").val($(linea).find("td:eq(9)").text()).trigger('change');
    
    // Seleccionar la categoría correcta
    var categoriaId = $(linea).find("td:eq(10)").attr("data-categoria-id");
    $("#categoria").val(categoriaId).trigger('change');
    
    // Seleccionar la ubicación correcta
    var ubicacionId = $(linea).find("td:eq(11)").attr("data-ubicacion-id");
    $("#ubicacion").val(ubicacionId).trigger('change');
    

    // Cargar imagen
    var imagenSrc = $(linea).find("td:eq(12) img").attr("src");
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show();// Muestra la imagen actual
        $("#imagen_url").val(imagenSrc);
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
                    if (lee.productos_bajo_stock && lee.productos_bajo_stock.length > 0) {
                        mostrarAlertaStockBajo(lee.productos_bajo_stock);
                    }
                } else if (lee.resultado == "incluir" || lee.resultado == "modificar") {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Éxito!" : "Error",
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
    $("#codigo").val("");
    $("#nombre").val("");
    $("#precio_compra").val("");
    $("#precio_venta").val("");
    $("#stock_total").val("");
    $("#stock_minimo").val("");
    $("#marca").val("");
    $("#modelo").val("");
    $("#tipo_unidad").val("").trigger('change');
    $("#categoria").val("").trigger('change');
    $("#ubicacion").val("").trigger('change');
    
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#codigo").prop("disabled", false);
    $("#nombre").prop("disabled", false);
    $("#precio_compra").prop("disabled", false);
    $("#precio_venta").prop("disabled", false);
    $("#stock_total").prop("disabled", false);
    $("#stock_minimo").prop("disabled", false);
    $("#marca").prop("disabled", false);
    $("#modelo").prop("disabled", false);
    $("#tipo_unidad").prop("disabled", false);
    $("#imagen").prop("disabled", false);
    $("#categoria").prop("disabled", false);
    $("#ubicacion").prop("disabled", false);
    
}

function verificarStock(stockTotal, stockMinimo) {
    if (parseInt(stockTotal) <= parseInt(stockMinimo)) {
        return '<span class="badge bg-danger">Stock bajo</span>';
    }
    return '';
}

function mostrarAlertaStockBajo(productosBajoStock) {
    let mensaje = "Los siguientes productos tienen stock bajo:\n";
    productosBajoStock.forEach(producto => {
        mensaje += `- ${producto.nombre} (Stock actual: ${producto.stock_total}, Stock mínimo: ${producto.stock_minimo})\n`;
    });

    Swal.fire({
        title: "¡Alerta de Stock Bajo!",
        text: mensaje,
        icon: "warning",
        confirmButtonText: "Entendido"
    });
}

function cargarOpciones() {
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'cargarOpciones' },
        success: function(respuesta) {
            var datos = JSON.parse(respuesta);
            
            // Cargar categorías
            datos.categorias.forEach(function(categoria) {
                $('#categoria').append($('<option>', {
                    value: categoria.id,
                    text: categoria.nombre
                }));
            });
            
            // Cargar ubicaciones
            datos.ubicaciones.forEach(function(ubicacion) {
                $('#ubicacion').append($('<option>', {
                    value: ubicacion.id,
                    text: ubicacion.nombre
                }));
            }); 
        }
    });
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

function validarPrecios() {
    const precioCompra = parseFloat($("#precio_compra").val());
    const precioVenta = parseFloat($("#precio_venta").val());
    if (precioVenta < precioCompra ) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El precio de venta debe ser mayor al precio de compra",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }    
    return true;
}