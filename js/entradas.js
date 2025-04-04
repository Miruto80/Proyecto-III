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
    $("#listadoproveedores tr").each(function(){
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

$("#buscarProveedor").on("keyup", function() {
    var valor = $(this).val().toLowerCase();
    $("#listadoproveedores tr").filter(function() {
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

$("#entrada").on("click",function(){//evento click de boton entrada
    if(validarenvio()){
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Deseas procesar esta salida?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, aceptar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#accion').val('entrada');
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
});

function carga_clientes(){ 
    var datos = new FormData();//a ese datos le añadimos la informacion a enviar
    datos.append('accion','listadoproveedores'); //le digo que me muestre un listado de aulas
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
    $("#listadoproveedores tr").each(function(){
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
    
    $("#detalledeventa tr").each(function(){
        if(id*1 == $(this).find("td:eq(0)").text()*1){
            encontro = true;
            var t = $(this).find("td:eq(3)").children();
            t.val(t.val()*1+1);
            modificasubtotal(t);
        } 
    });
    
    if(!encontro){
        var l = `
        <tr> 
        <td style="display:none"> <input type="text" name="idp[]" style="display:none" value="${$(linea).find("td:eq(0)").text()}"/>${$(linea).find("td:eq(0)").text()}</td>
        <td>${$(linea).find("td:eq(1)").text()}</td>
        <td>${$(linea).find("td:eq(2)").text()}</td>
        <td> <input type="text" value="1" class="btn" name="cant[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)" /></td>
        <td> <input type="text" name="pcp[]" class="btn" value="${$(linea).find("td:eq(3)").text()}" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>
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
    modificasubtotal(input);
}



function modificasubtotal(input){
    var linea = $(input).closest('tr');
    var cantidad = linea.find("td:eq(3) input").val()*1;
    var precio = linea.find("td:eq(4) input").val()*1;
    linea.find("td:eq(5)").text(redondearDecimales((cantidad*precio),2));
}

function eliminalineadetalle(boton){//funcion para eliminar linea de detalle de salidas X
    $(boton).closest('tr').remove();
}

function colocacliente(linea){//funcion para colocar datos del cliente en pantalla
    var documentoCompleto = $(linea).find("td:eq(1)").text();
    var numeroDocumento = documentoCompleto.split(":")[1].trim();
    var tipoDocumento= documentoCompleto.split(":")[0].trim();
    $("#cedulacliente").val(numeroDocumento);
    $("#idcliente").val($(linea).find("td:eq(0)").text());
    $("#datosdelcliente").html("<strong>"+tipoDocumento+": </strong> "+numeroDocumento+ ". "+"<strong>Nombre: </strong> "+$(linea).find("td:eq(2)").text()+". <strong>Teléfono: </strong>"+ $(linea).find("td:eq(5)").text()+".");
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
    if ($.fn.DataTable.isDataTable("#tablaentradas")) {
        $("#tablaentradas").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaentradas")) {
        $("#tablaentradas").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron entradas de productos",
                info: "Página _PAGE_ de _PAGES_",
                infoEmpty: "No hay entradas registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar entrada...",
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
            order: [[1, "desc"]],
            responsive: true,
            columnDefs: [
                { targets: 5, orderable: false }
                ]
        });
    }
    $(window).resize(function() {
        $('#tablaentradas').DataTable().columns.adjust().draw();
    });
}

function limpia() {
    $("#cedulacliente").val("");
    $("#idcliente").val("");
    $("#codigoproducto").val("");
    $("#idproducto").val("");
    $("#detalledeventa").empty();
    $("#datosdelcliente").html("");
    $("#nota_entrega").val("");
    $("#check_nota_entrega").hide();
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
                            text: "La categoría ha sido incluida con éxito.",
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

                if(lee.resultado=='listadoproveedores'){
                    $('#listadoproveedores').html(lee.mensaje);
                }
                else if(lee.resultado=='listadoproductos'){

                    $('#listadoproductos').html(lee.mensaje);
                }
                else if(lee.resultado=='entrada'){   

                   console.log(lee.mensaje);             
                   Swal.fire({
                    title: "¡Incluido!",
                    text: "La entrada ha sido incluida con éxito.",
                    icon: "success"
                });
                    $("#modal1").modal("hide"); // Oculta el modal
                    consultar(); // Actualiza la tabla de salidas
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
            var datosValidos = true;
            
            $("#detalledeventa tr").each(function() {
                var cantidad = parseFloat($(this).find("td:eq(3) input").val());
                var precio = parseFloat($(this).find("td:eq(4) input").val());
                
                if (cantidad <= 0 || isNaN(cantidad)) {
                    datosValidos = false;
                    Swal.fire({
                        title: "Error",
                        text: "La cantidad debe ser mayor a 0 para todos los productos",
                        icon: "error"
                    });
                    return false;
                }
                
                if (precio <= 0 || isNaN(precio)) {
                    datosValidos = false;
                    Swal.fire({
                        title: "Error",
                        text: "El precio debe ser mayor a 0 para todos los productos",
                        icon: "error"
                    });
                    return false;
                }
            });

            if (!datosValidos) {
                return false;
            }

            return true;
        } else {
            Swal.fire({
                title: "Error",
                text: "Debe agregar algún producto a la entrada",
                icon: "error"
            });
            return false;
        }
    } else {
        Swal.fire({
            title: "Error",
            text: "Debe ingresar un proveedor registrado",
            icon: "error"
        });
        return false;
    }
}

function enviarFormulario() {
    if (verificaproductos() && existecliente()) {
        var datos = new FormData();
        datos.append('accion', 'entrada');
        datos.append('idcliente', $("#idcliente").val());
        
        // Obtener los arrays de productos
        var idp = $("input[name='idp[]']").map(function(){return $(this).val();}).get();
        var cant = $("input[name='cant[]']").map(function(){return $(this).val();}).get();
        var pvp = $("input[name='pvp[]']").map(function(){return $(this).val();}).get();
        
        datos.append('idp', idp);
        datos.append('cant', cant);
        datos.append('pvp', pvp);
        

        if ($("#nota_entrega")[0].files[0]) {
            datos.append('nota_entrega', $("#nota_entrega")[0].files[0]);
        }
        
        enviaAjax(datos);
    }
}

function validarPrecio(input) {
    var precio = parseFloat(input.value);
    if (precio <= 0 || isNaN(precio)) {
        Swal.fire({
            title: "Error",
            text: "El precio debe ser mayor a 0",
            icon: "error"
        });
        input.value = 1;
    }
    modificasubtotal(input);
}

$("#nota_entrega").on("change", function() {
    const file = this.files[0];
    if (file) {
        // Mostrar el check
        $("#check_nota_entrega").show();
    } else {
        // Ocultar el check si no hay archivo seleccionado
        $("#check_nota_entrega").hide();
    }
});


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