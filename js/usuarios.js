function consultar(){
    var datos = new FormData();
    datos.append('accion','consultar');
    enviaAjax(datos);   
}
function destruyeDT(){
    if ($.fn.DataTable.isDataTable("#tablausuario")) {
        $("#tablausuario").DataTable().destroy();
    }
}
function crearDT(){
    if (!$.fn.DataTable.isDataTable("#tablausuario")) {
        $("#tablausuario").DataTable({
          language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron usuarios",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay usuarios registrados",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "<i class='bi bi-search'></i>",
            searchPlaceholder: "Buscar usuario...",
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
$(document).ready(function(){
    consultar();
    $("#cedula").on("keypress",function(e){
        validarkeypress(/^[0-9]{1,8}$/,e);
    });
    $("#cedula").on("keyup",function(){
        validarkeyup(/^[0-9]{7,8}$/, $(this),$("#scedula"),"El formato de CI debe ser 1234567 o 12345678");
    });
    $("#nombre").on("keypress",function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
    });
    $("#nombre").on("keyup",function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this),$("#snombre"),"Solo letras  entre 3 y 30 caracteres");
    });
    $("#apellido").on("keypress",function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
    }); 
    $("#apellido").on("keyup",function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this),$("#sapellido"),"Solo letras  entre 3 y 30 caracteres");
    }); 
    $("#correo").on("keypress", function(e) {
     validarkeypress(/^[A-Za-z0-9\s,.\-@]*$/, e);
 });
    $("#correo").on("keyup", function() {
     validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $(this), $("#scorreo"), "El formato de correo electrónico debe ser ejemplo@correo.com");
 });
    $("#telefono").on("keypress", function(e) {
     validarkeypress(/^[0-9\b]*$/, e);
 });
    $("#telefono").on("keyup", function() {
     validarkeyup(/^04[0-9]{9}$/, $(this), $("#stelefono"), "El formato de teléfono debe ser 04120000000");
 });
    $("#nombre_usuario").on("keypress", function(e) {
     validarkeypress(/^[A-Za-z0-9\s,.]*$/, e);
 });
    $("#nombre_usuario").on("keyup", function() {
     validarkeyup(/^[A-Za-z0-9\s,.]{3,100}$/, $(this), $("#snombre_usuario"), "Debe tener entre 3 y 100 caracteres y solo contener letras, números, espacios, comas y puntos");
 });
    $("#contraseña").on("keyup", function() {
        validarkeyup(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/, $(this), $("#scontraseña"), "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número");
    });
    $("#repetir_contraseña").on("keyup", function() {
        if ($(this).val() !== $("#contraseña").val()) {
            $("#srepetir_contraseña").text("Las contraseñas no coinciden");
        } else {
            $("#srepetir_contraseña").text("");
        }
    });


    $("#proceso").on("click",function(){
        if($(this).text()=="INCLUIR"){
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo usuario?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, incluir",
                    cancelButtonText: "No, Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var datos = new FormData();
                        datos.append('accion','incluir');
                        datos.append('cedula',$("#cedula").val());
                        datos.append('nombre',$("#nombre").val());
                        datos.append('apellido',$("#apellido").val());
                        datos.append('correo',$("#correo").val());
                        datos.append('telefono',$("#telefono").val());
                        datos.append('nombre_usuario',$("#nombre_usuario").val());
                        datos.append('tipo_usuario',$("#tipo_usuario").val());
                        datos.append('contraseña',$("#contraseña").val());
                        datos.append('imagen', $("#imagen")[0].files[0]);
                        enviaAjax(datos);
                    }
                });
            }
        }
        else if($(this).text()=="MODIFICAR"){
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
                    text: "¿Deseas modificar la información de este usuario?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Sí, modificar",
                    cancelButtonText: "No, cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        var datos = new FormData();
                        datos.append('accion','modificar');
                        datos.append('cedula',$("#cedula").val());
                        datos.append('nombre',$("#nombre").val());
                        datos.append('apellido',$("#apellido").val());
                        datos.append('correo',$("#correo").val());
                        datos.append('telefono',$("#telefono").val());
                        datos.append('nombre_usuario',$("#nombre_usuario").val());
                        datos.append('tipo_usuario',$("#tipo_usuario").val());
                        datos.append('contraseña',$("#contraseña").val());
                        datos.append('imagen', $("#imagen")[0].files[0]);
                        enviaAjax(datos);
                    }
                });
            }
        }
        if($(this).text()=="ELIMINAR"){
            if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula"),
                $("#scedula"),"El formato debe ser el solicitado")==0){
               muestraMensaje("la cedula debe coincidir con el formato solicitado <br/>"+ 
                  "12345678");  
       }
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
           datos.append('cedula', $("#cedula").val());
           enviaAjax(datos);
       } else if (result.dismiss === Swal.DismissReason.cancel) {
           swalWithBootstrapButtons.fire({
              title: "Cancelado",
              text: "Tu usuario está a salvo :)",
              icon: "error"
          });
       }
   });
    
}
});
});

$("#incluir").on("click",function(){
    limpia();
    $("#proceso").text("INCLUIR");
    $("#modal1").modal("show");
}); 

function validarenvio(){
    if(validarkeyup(/^[0-9]{7,8}$/, $("#cedula"),$("#scedula"),"El formato de CI debe ser 1234567 o 12345678")==0){
        Swal.fire({
            title: "¡ERROR!",
            text: "La cedula del usuario es obligatoria",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }
    if ($("#nombre").val().trim() === "") {
      Swal.fire({
        title: "¡ERROR!",
        text: "El nombre del usuario es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });    
      return false;
  }
  if ($("#apellido").val().trim() === "") {
      Swal.fire({
        title: "¡ERROR!",
        text: "El apellido del usuario es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });    
      return false;
  }
  if ($("#nombre_usuario").val().trim() === "") {
      Swal.fire({
        title: "¡ERROR!",
        text: "El nombre de usuario es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });    
      return false;
  }
  else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $("#nombre"),$("#snombre"),"Solo letras  entre 3 y 30 caracteres")==0){
      Swal.fire({
        title: "¡ERROR!",
        text: "El nombre debe contener solo letras y tener entre 3 y 30 caracteres",
        icon: "error",
        confirmButtonText: "Aceptar"
    });         
  return false;
}
else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,   
  $("#apellido"),$("#sapellido"),"Solo letras  entre 3 y 30 caracteres")==0){
  Swal.fire({
    title: "¡ERROR!",
    text: "El apellido debe contener solo letras y tener entre 3 y 30 caracteres",
    icon: "error",
    confirmButtonText: "Aceptar"
}); 
return false;
}
if($("#correo").val().trim() !== "" && 
 validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $("#correo"), $("#scorreo"), "El formato de correo electrónico debe ser ejemplo@correo.com") == 0){
  Swal.fire({
    title: "¡ERROR!",
    text: "El formato de correo electrónico debe ser ejemplo@correo.com",
    icon: "error",
    confirmButtonText: "Aceptar"
});  
return false;
}
else if($("#telefono").val().trim() !== "" && 
  validarkeyup(/^04[0-9]{9}$/, $("#telefono"), $("#stelefono"), "El formato de teléfono debe ser 04120000000") == 0){
  Swal.fire({
    title: "¡ERROR!",
    text: "El formato de teléfono debe ser 04120000000",
    icon: "error",
    confirmButtonText: "Aceptar"
});     
return false;
}
else if(validarkeyup(/^[A-Za-z0-9\s,.]{3,100}$/, $("#nombre_usuario"), $("#snombre_usuario"), "El nombre de usuario debe tener entre 1 y 100 caracteres y solo contener letras, números, espacios, comas y puntos") == 0){
 Swal.fire({
    title: "¡ERROR!",
    text: " El nombre de usuario debe tener entre 3 y 100 caracteres y solo contener letras, números, espacios, comas y puntos",
    icon: "error",
    confirmButtonText: "Aceptar"
});   
 return false;
}

else if(validarkeyup(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/, $("#contraseña"), $("#scontraseña"), "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número") == 0){
  Swal.fire({
    title: "¡ERROR!",
    text: "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número",
    icon: "error",
    confirmButtonText: "Aceptar"
});  
  return false;
}
else if($("#repetir_contraseña").val() !== $("#contraseña").val()){
  Swal.fire({
    title: "¡ERROR!",
    text: "Las contraseñas no coinciden :(",
    icon: "error",
    confirmButtonText: "Aceptar"
}); 
  return false;
}
else if ($("#tipo_usuario").val() === "ninguno") {
  Swal.fire({
    title: "¡ERROR!",
    text: "El tipo de usuario es obligatorio",
    icon: "error",
    confirmButtonText: "Aceptar"
});    
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
function validarkeyup(er,etiqueta,etiquetamensaje,
    mensaje){
    a = er.test(etiqueta.val());
    if(a){
        etiquetamensaje.text("");
        return 1;
    }
    else{
        etiquetamensaje.text(mensaje);
        return 0;
    }
}
function pone(pos,accion){  
    linea=$(pos).closest('tr');
    if(accion==0){
        $("#proceso").text("MODIFICAR");
        $("#cedula").prop("disabled",true);
        $("#nombre").prop("disabled",false);
        $("#apellido").prop("disabled",false);
        $("#correo").prop("disabled",false);
        $("#telefono").prop("disabled",false);
        $("#nombre_usuario").prop("disabled",false);
        $("#tipo_usuario").prop("disabled",true);
        $("#contraseña").prop("disabled",false);
        $("#repetir_contraseña").prop("disabled",false);
        $("#imagen").prop("disabled",false);
        $("#contraseña").show();
        $("#repetir_contraseña").show();
        $("label[for='contraseña']").show();
        $("label[for='repetir_contraseña']").show();
    }
    else{
        $("#proceso").text("ELIMINAR");
        $("#cedula").prop("disabled",true);
        $("#nombre").prop("disabled",true);
        $("#apellido").prop("disabled",true);
        $("#correo").prop("disabled",true);
        $("#telefono").prop("disabled",true);
        $("#nombre_usuario").prop("disabled",true);
        $("#tipo_usuario").prop("disabled",true);
        $("#contraseña").prop("disabled",true);
        $("#repetir_contraseña").prop("disabled",true);
        $("#imagen").prop("disabled",true);
        $("#contraseña").hide();
        $("#repetir_contraseña").hide();
        $("label[for='contraseña']").hide();
        $("label[for='repetir_contraseña']").hide();
    }
    $("#cedula").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#apellido").val($(linea).find("td:eq(3)").text());
    $("#correo").val($(linea).find("td:eq(4)").text());
    $("#telefono").val($(linea).find("td:eq(5)").text());
    $("#nombre_usuario").val($(linea).find("td:eq(6)").text());
    $("#tipo_usuario").val($(linea).find("td:eq(7)").text());
    $("#contraseña").val("");
    $("#repetir_contraseña").val("");
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
                    text: "El usuario ha sido incluido con éxito.",
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
function limpia(){
    $("#cedula").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#correo").val("");
    $("#telefono").val("");
    $("#nombre_usuario").val("");       
    $("#tipo_usuario").prop("selectedIndex",0);
    $("#contraseña").val("");
    $("#repetir_contraseña").val("");       
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#cedula").prop("disabled",false);
    $("#nombre").prop("disabled",false);
    $("#apellido").prop("disabled",false);
    $("#correo").prop("disabled",false);
    $("#telefono").prop("disabled",false);
    $("#nombre_usuario").prop("disabled",false);
    $("#tipo_usuario").prop("disabled",false);
    $("#contraseña").prop("disabled",false);
    $("#repetir_contraseña").prop("disabled",false);
    $("#imagen").prop("disabled",false); 
    $("#contraseña").show();
    $("#repetir_contraseña").show();
    $("label[for='contraseña']").show();
    $("label[for='repetir_contraseña']").show();        
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