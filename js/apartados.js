$(document).ready(function() {
    consultar(); // Llama a la función consultar al cargar el documento
   carga_clientes();// Cargar la lista de clientes
    carga_productos();//carga la lista de productos

$("#listadodeclientes").on("click",function(){//boton para levantar modal de clientes
    $("#modalclientes").modal("show");
});

$("#listadodeproductos").on("click",function(){//boton para levantar modal de productos
    $("#modalproductos").modal("show");
});

$("#cedulacliente").on("keyup",function(){//evento keyup de input cedulacliente 
    var cedula = $(this).val();
    var encontro = false;
    $("#listadoclientes tr").each(function(){
        var documentoCompleto = $(this).find("td:eq(1)").text();
        var numeroDocumento = documentoCompleto.split(":")[1].trim();
        if(cedula == numeroDocumento){
            colocacliente($(this));
            encontro = true;
        } 
    });
    if(!encontro){
        $("#datosdelcliente").html("");
    }
}); 

$("#codigoproducto").on("keyup",function(){//evento keyup de input codigoproducto
    var codigo = $(this).val();
    $("#listadoproductos tr").each(function(){
        if(codigo == $(this).find("td:eq(1)").text()){
            colocaproducto($(this));
        }
    });
}); 

$("#apartar").on("click",function(){//evento click de boton apartar
    if(validarenvio()){
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Deseas procesar este apartado?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, aceptar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#accion').val('apartar');
                var datos = new FormData($('#f')[0]);       
                enviaAjax(datos);
                $("#modal1").modal("hide");
                consultar();
                carga_productos();
            }
        });
    }
});

$("#incluir").on("click",function(){
    limpia();
    $("#proceso").text("INCLUIR");
    $("#modal1").modal("show");
}); 

// Manejador para el botón de transferir
$(document).on('click', '.transferir-salida', function() {
    var apartadoId = $(this).data('apartado-id');
    
    Swal.fire({
        title: "¿Transferir a Salida?",
        text: "¿Deseas transferir este apartado a una salida?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, transferir",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('accion', 'transferir_a_salida');
            datos.append('apartado_id', apartadoId);
            enviaAjax(datos);
        }
    });
});

// Manejador para el botón de cancelar apartado
$(document).on('click', '.cancelar-apartado', function() {
    var apartadoId = $(this).data('apartado-id');
    
    Swal.fire({
        title: "¿Cancelar Apartado?",
        text: "¿Estás seguro de cancelar este apartado? Los productos volverán al inventario.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, cancelar",
        cancelButtonText: "No, mantener"
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('accion', 'cancelar_apartado');
            datos.append('apartado_id', apartadoId);
            enviaAjax(datos);
        }
    });
});

$("#buscarCliente").on("keyup", function() {
    var valor = $(this).val().toLowerCase();
    $("#listadoclientes tr").filter(function() {
        var texto = $(this).text().toLowerCase();
        $(this).toggle(texto.indexOf(valor) > -1);
    });
});

$("#buscarProducto").on("keyup", function() {
    var valor = $(this).val().toLowerCase();
    $("#listadoproductos tr").filter(function() {
        var texto = $(this).text().toLowerCase();
        $(this).toggle(texto.indexOf(valor) > -1);
    });
});



});

function carga_clientes(){ 
    var datos = new FormData();//a ese datos le añadimos la informacion a enviar
    datos.append('accion','listadoclientes'); //le digo que me muestre un listado de aulas
    enviaAjax(datos);   //ahora se envia el formdata por ajax
}

function carga_productos(){
    var datos = new FormData();
    datos.append('accion','listadoproductos'); //le digo que me muestre un listado de productos
    enviaAjax(datos);
}

function verificaproductos(){//function para saber si selecciono algun productos
    var existe = false;
    if($("#detalledeventa tr").length > 0){
        existe = true;
    }
    return existe;
}

function existecliente(){//function para buscar si existe el cliente
    var cedula = $("#cedulacliente").val();
    var existe = false;
    $("#listadoclientes tr").each(function(){
        var documentoCompleto = $(this).find("td:eq(1)").text();
        var numeroDocumento = documentoCompleto.split(":")[1].trim();
        if(cedula == numeroDocumento){
            existe = true;
        }
    });
    return existe;
}

function colocaproducto(linea){//funcion para colocar los productos
    var id = $(linea).find("td:eq(0)").text();
    var encontro = false;
    var stockDisponible = parseInt($(linea).find("td:eq(4)").text());
    
    if(stockDisponible <= 0) {
        Swal.fire({
            title: "Error",
            text: "Este producto no tiene stock disponible",
            icon: "error"
        });
        return;
    }
    
    $("#detalledeventa tr").each(function(){
        if(id*1 == $(this).find("td:eq(0)").text()*1){
            encontro = true;
            var t = $(this).find("td:eq(3)").children();
            var nuevaCantidad = parseInt(t.val()) + 1;
            if(nuevaCantidad <= stockDisponible) {
                t.val(nuevaCantidad);
                modificasubtotal(t);
            } else {
                Swal.fire({
                    title: "Error",
                    text: "No hay suficiente stock disponible",
                    icon: "error"
                });
            }
        } 
    });
    
    if(!encontro){
        var l = `
        <tr> 
        <td style="display:none"> <input type="text" name="idp[]" style="display:none" value="${$(linea).find("td:eq(0)").text()}"/>${$(linea).find("td:eq(0)").text()}</td>
        <td>${$(linea).find("td:eq(1)").text()}</td>
        <td>${$(linea).find("td:eq(2)").text()}</td>
        <td> <input type="text" value="1" class="btn" name="cant[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)"/></td>
        <td> <input type="text" name="pvp[]" class="btn" value="${$(linea).find("td:eq(3)").text()}" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>
        <td>${redondearDecimales($(linea).find("td:eq(3)").text()*1,2)}</td>
        <td> <button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button> </td>
        </tr>`;
        $("#detalledeventa").append(l);
    }
}

function validarCantidad(input) {
    var cantidad = parseInt(input.value);
    var linea = $(input).closest('tr');
    var productoId = linea.find("td:eq(0) input").val();
    var stockDisponible = 0;
    
    // Validar que sea un número entero positivo
    if (!/^[1-9][0-9]*$/.test(input.value)) {
        Swal.fire({
            title: "Error",
            text: "La cantidad debe ser un número mayor a cero",
            icon: "error"
        });
        input.value = 1;
    }
    
    // Buscar el stock disponible del producto
    $("#listadoproductos tr").each(function() {
        if($(this).find("td:eq(0)").text() == productoId) {
            stockDisponible = parseInt($(this).find("td:eq(4)").text());
            return false;
        }
    });

    // Validar que no exceda el stock disponible
    if (cantidad > stockDisponible) {
        Swal.fire({
            title: "Error",
            text: "La cantidad no puede ser mayor al stock disponible (" + stockDisponible + ")",
            icon: "error"
        });
        input.value = stockDisponible;
    }
    
    modificasubtotal(input);
}




function modificasubtotal(input){
    var linea = $(input).closest('tr');
    var cantidad = linea.find("td:eq(3) input").val()*1;
    var precio = linea.find("td:eq(4) input").val()*1;
    linea.find("td:eq(5)").text(redondearDecimales((cantidad*precio),2));
}



function eliminalineadetalle(boton){//funcion para eliminar linea de detalle de apartado X
    $(boton).closest('tr').remove();
}

function colocacliente(linea){//funcion para colocar datos del cliente en pantalla
    var documentoCompleto = $(linea).find("td:eq(1)").text();
    var numeroDocumento = documentoCompleto.split(":")[1].trim();
    var tipoDocumento= documentoCompleto.split(":")[0].trim();
    $("#cedulacliente").val(numeroDocumento);
    $("#idcliente").val($(linea).find("td:eq(0)").text());
    $("#datosdelcliente").html("<strong>"+tipoDocumento+": </strong> "+numeroDocumento+ ". "+"<strong>Nombre: </strong> "+$(linea).find("td:eq(2)").text()+"  "+ $(linea).find("td:eq(3)").text()+". <strong>Teléfono: </strong>"+ $(linea).find("td:eq(5)").text()+".");
}

function redondearDecimales(numero, decimales) {
    return Number(Math.round(numero +'e'+ decimales) +'e-'+ decimales).toFixed(decimales);
    
}

function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablapartados")) {
        $("#tablapartados").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablapartados")) {
        $("#tablapartados").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron apartados de productos",
                info: "Página _PAGE_ de _PAGES_",
                infoEmpty: "No hay apartados registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar apartado...",
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
            order: [[2, "desc"]],
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [1, 3, 4] }, // Columnas no ordenables
                { searchable: false, targets: [0, 1, 4, 5] } // Columnas no buscables
                ],
            columns: [
                { data: 0 }, // #
                { data: 1 }, // Finalizar
                { data: 2 }, // Fecha
                { data: 3 }, // Cliente
                { data: 4 }, // Productos
                { data: 5 }  // Total
                ]
        });
    }
    $(window).resize(function() {
        $('#tablapartados').DataTable().columns.adjust().draw();
    });
}

function limpia() {
    $("#cedulacliente").val("");
    $("#idcliente").val("");
    $("#codigoproducto").val("");
    $("#idproducto").val("");
    $("#detalledeventa").empty();
    $("#datosdelcliente").html("");
}

function muestraMensaje(mensaje){//Funcion que muestra el modal con un mensaje
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
       $("#mostrarmodal").modal("hide");
   },5000);
}

function validarkeypress(er, e) {// Función para validar la tecla presionada
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault(); // Previene la acción si no coincide con la expresión regular
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {// Función para validar el valor de un campo
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text(""); // Limpia el mensaje si es válido
        return 1; // Retorna 1 si es válido
    } else {
        etiquetamensaje.text(mensaje); // Muestra el mensaje de error
        return 0; // Retorna 0 si no es válido
    }
}

function enviaAjax(datos) {// Función para enviar datos mediante AJAX
    $.ajax({
        async: true,
        url: "", // URL del servidor
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function() {
            $("#loader").show(); // Mostrar loader
        },
        timeout: 10000, // Tiempo de espera
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta); // Intenta parsear la respuesta
                console.log(lee.resultado);
                if (lee.resultado == "consultar") {
                    destruyeDT(); // Destruye la tabla
                    $("#resultadoconsulta").html(lee.mensaje); // Muestra el mensaje
                    crearDT(); // Crea la tabla
                } else if (lee.resultado == "incluir") {
                    // Manejo de respuesta al incluir
                    if (lee.mensaje == '¡Registro guardado con exito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "El apartado ha sido incluido con éxito.",
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

                if(lee.resultado=='listadoclientes'){
                    $('#listadoclientes').html(lee.mensaje);
                }
                else if(lee.resultado=='listadoproductos'){

                    $('#listadoproductos').html(lee.mensaje);
                }
                else if(lee.resultado=='apartar'){   

                 console.log(lee.mensaje);             
                 Swal.fire({
                    title: "¡Incluido!",
                    text: "El apartado ha sido incluido con éxito.",
                    icon: "success"
                });
                    $("#modal1").modal("hide"); // Oculta el modal
                    consultar(); // Actualiza la tabla de apartados
                    carga_productos(); // Recarga la lista de productos
                    limpia(); // Limpia el formulario
                }



                else if (lee.resultado == "error") {
                    Swal.fire({
                        title: "Error",
                        text: lee.mensaje,
                        icon: "error"
                    });
                }

                else if(lee.resultado == 'transferir_a_salida') {
                    if(lee.mensaje == 'ok') {
                        Swal.fire({
                            title: "¡Transferido!",
                            text: "El apartado ha sido transferido a salidas exitosamente.",
                            icon: "success"
                        });
                        consultar(); // Actualiza la tabla de apartados
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: lee.mensaje,
                            icon: "error"
                        });
                    }
                }

                else if(lee.resultado == 'cancelar_apartado') {
                    if(lee.mensaje == 'ok') {
                        Swal.fire({
                            title: "¡Cancelado!",
                            text: "El apartado ha sido cancelado y los productos han vuelto al inventario.",
                            icon: "success"
                        });
                        consultar(); // Actualiza la tabla de apartados
                        carga_productos(); // Recarga la lista de productos
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "No se pudo cancelar el apartado: " + lee.mensaje,
                            icon: "error"
                        });
                    }
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
            // Manejo de errores
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

function validarenvio() {
    if (existecliente() == true) {
        if (verificaproductos()) {
            var cantidadesValidas = true;
            $("#detalledeventa tr").each(function() {
                var cantidad = $(this).find("td:eq(3) input").val();
                if (cantidad <= 0) {
                    cantidadesValidas = false;
                    return false; // Salir del bucle each
                }
            });

            if (!cantidadesValidas) {
                Swal.fire({
                    title: "Error",
                    text: "La cantidad debe ser mayor a 0 para todos los productos",
                    icon: "error"
                });
                return false;
            }

            return true;
        } else {
            Swal.fire({
                title: "Error",
                text: "Debe agregar algún producto para el apartado",
                icon: "error"
            });
            return false;
        }
    } else {
        Swal.fire({
            title: "Error",
            text: "Debe ingresar un cliente registrado",
            icon: "error"
        });
        return false;
    }
}

function validarPrecio(input) {
    // Validar formato de número con 2 decimales
    if (!/^\d+(\.\d{0,2})?$/.test(input.value)) {
        Swal.fire({
            title: "Error",
            text: "El precio debe ser un número con máximo 2 decimales",
            icon: "error"
        });
        if(input.value == ""){
            input.value = "1.00";
        }
        input.value = parseFloat(input.value).toFixed(2);
    }
    // Validar que sea mayor a 0
    if (parseFloat(input.value) <= 0) {
        Swal.fire({
            title: "Error",
            text: "El precio debe ser mayor a 0",
            icon: "error"
        });
        input.value = "1.00";
    }
    modificasubtotal(input);
}