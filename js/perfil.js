$(document).ready(function(){
    cargarPerfil(); // Carga el perfil del usuario al iniciar

    $("#proceso").click(function(){
        if(validarFormulario()){ // Valida el formulario antes de enviar
            var datos = new FormData();
            datos.append('accion', 'modificar'); // Acción para modificar datos
            // Agrega los datos del formulario a FormData
            datos.append('cedula', $("#cedula").val());
            datos.append('nombre', $("#nombre").val());
            datos.append('apellido', $("#apellido").val());
            datos.append('correo', $("#correo").val());
            datos.append('telefono', $("#telefono").val());
            datos.append('nombre_usuario', $("#nombre_usuario").val());
            if($("#contraseña").val()){
                datos.append('contraseña', $("#contraseña").val()); // Agrega contraseña si se proporciona
            }
            if($("#imagen")[0].files[0]){
                datos.append('imagen', $("#imagen")[0].files[0]); // Agrega imagen si se selecciona
            }
            enviarDatos(datos); // Envía los datos al servidor
        }
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
});

// Función para validar la tecla presionada
function validarkeypress(er,e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
      e.preventDefault(); // Previene la entrada si no es válida
  }    
}

// Función para validar el contenido del campo al soltar la tecla
function validarkeyup(er,etiqueta,etiquetamensaje, mensaje){
    a = er.test(etiqueta.val());
    if(a){
        etiquetamensaje.text(""); // Limpia el mensaje de error si es válido
        return 1;
    }
    else{
        etiquetamensaje.text(mensaje); // Muestra el mensaje de error si no es válido
        return 0;
    }
}

// Función para cargar el perfil del usuario
function cargarPerfil(){
    var datos = new FormData();
    datos.append('accion', 'cargar'); // Acción para cargar datos
    $.ajax({
        url: '?pagina=perfil',
        type: 'POST',
        data: datos,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(respuesta){
            if(respuesta.resultado === 'ok'){
                // Rellena los campos del formulario con los datos recibidos
                $("#cedula").val(respuesta.datos.cedula);
                $("#nombre").val(respuesta.datos.nombre);
                $("#apellido").val(respuesta.datos.apellido);
                $("#correo").val(respuesta.datos.correo);
                $("#telefono").val(respuesta.datos.telefono);
                $("#nombre_usuario").val(respuesta.datos.nombre_usuario);
                $("#tipo_usuario").val(respuesta.datos.tipo_usuario);
                $("#imagen_actual").attr("src", respuesta.datos.imagen);
                $("#cedula").prop("disabled", true); // Desactiva el campo cédula
                $("#tipo_usuario").prop("disabled", true); // Desactiva el campo tipo de usuario
            } else {
                mostrarError(respuesta.mensaje); // Muestra error si no se carga correctamente
            }
        },
        error: function(){
            mostrarError("Error en la conexión"); // Muestra error de conexión
        }
    });
}

// Función para enviar los datos al servidor
function enviarDatos(datos){
    $.ajax({
        url: '?pagina=perfil',
        type: 'POST',
        data: datos,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(respuesta){
            if(respuesta.resultado === 'ok'){
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: respuesta.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                
                // Actualizar las imágenes en el menú lateral y barra superior
                $('.navLateral-body-img, .navBar-options-img').attr('src', respuesta.nueva_imagen);
                
                cargarPerfil();
            } else {
                mostrarError(respuesta.mensaje); // Muestra error si no se envían correctamente
            }
        },
        error: function(){
            mostrarError("Error en la conexión"); // Muestra error de conexión
        }
    });
}

// Función para mostrar mensajes de error
function mostrarError(mensaje){
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje
    });
}

// Función para validar el formulario antes de enviar
function validarFormulario(){
    if ($("#nombre").val().trim() === "") {
      Swal.fire({
        title: "¡ERROR!",
        text: "El nombre del usuario es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });    
      return false; // Retorna falso si el nombre está vacío
  }
  if ($("#apellido").val().trim() === "") {
      Swal.fire({
        title: "¡ERROR!",
        text: "El apellido del usuario es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });    
      return false; // Retorna falso si el apellido está vacío
  }
  if ($("#nombre_usuario").val().trim() === "") {
      Swal.fire({
        title: "¡ERROR!",
        text: "El nombre de usuario es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });    
      return false; // Retorna falso si el nombre de usuario está vacío
  }
  else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $("#nombre"),$("#snombre"),"Solo letras  entre 3 y 30 caracteres")==0){
      Swal.fire({
        title: "¡ERROR!",
        text: "El nombre debe contener solo letras y tener entre 3 y 30 caracteres",
        icon: "error",
        confirmButtonText: "Aceptar"
    });         
  return false; // Retorna falso si el nombre no es válido
}
else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,   
  $("#apellido"),$("#sapellido"),"Solo letras  entre 3 y 30 caracteres")==0){
  Swal.fire({
    title: "¡ERROR!",
    text: "El apellido debe contener solo letras y tener entre 3 y 30 caracteres",
    icon: "error",
    confirmButtonText: "Aceptar"
}); 
return false; // Retorna falso si el apellido no es válido
}
if($("#correo").val().trim() !== "" && 
 validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $("#correo"), $("#scorreo"), "El formato de correo electrónico debe ser ejemplo@correo.com") == 0){
  Swal.fire({
    title: "¡ERROR!",
    text: "El formato de correo electrónico debe ser ejemplo@correo.com",
    icon: "error",
    confirmButtonText: "Aceptar"
});  
return false; // Retorna falso si el correo no es válido
}
else if($("#telefono").val().trim() !== "" && 
  validarkeyup(/^04[0-9]{9}$/, $("#telefono"), $("#stelefono"), "El formato de teléfono debe ser 04120000000") == 0){
  Swal.fire({
    title: "¡ERROR!",
    text: "El formato de teléfono debe ser 04120000000",
    icon: "error",
    confirmButtonText: "Aceptar"
});     
return false; // Retorna falso si el teléfono no es válido
}
else if(validarkeyup(/^[A-Za-z0-9\s,.]{3,100}$/, $("#nombre_usuario"), $("#snombre_usuario"), "El nombre de usuario debe tener entre 3 y 100 caracteres y solo contener letras, números, espacios, comas y puntos") == 0){
 Swal.fire({
    title: "¡ERROR!",
    text: " El nombre de usuario debe tener entre 3 y 100 caracteres y solo contener letras, números, espacios, comas y puntos",
    icon: "error",
    confirmButtonText: "Aceptar"
});   
 return false; // Retorna falso si el nombre de usuario no es válido
}





else if($("#contraseña").val().trim() !== "" && 
    validarkeyup(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/, $("#contraseña"), $("#scontraseña"), "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número") == 0){
  Swal.fire({
    title: "¡ERROR!",
    text: "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número",
    icon: "error",
    confirmButtonText: "Aceptar"
});  
  return false; // Retorna falso si la contraseña no es válida
}
else if($("#repetir_contraseña").val() !== $("#contraseña").val()){
  Swal.fire({
    title: "¡ERROR!",
    text: "Las contraseñas no coinciden :(",
    icon: "error",
    confirmButtonText: "Aceptar"
}); 

  return false; // Retorna falso si las contraseñas no coinciden
}

return true; // Retorna verdadero si todas las validaciones son correctas
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