// ||||||||||||||| OJITO ||||||||||||||||||||
const passwordInput = document.getElementById('pid');
const showPasswordButton = document.getElementById('show-password');

showPasswordButton.addEventListener('click', () => {
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    showPasswordButton.classList.remove('fa-eye');
    showPasswordButton.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    showPasswordButton.classList.remove('fa-eye-slash');
    showPasswordButton.classList.add('fa-eye'); 
  }
});

/*||| Funcion para cambiar el boton a loader |||*/
function activarLoaderBoton(idBoton, texto) {
    const $boton = $(idBoton);
    const textoActual = $boton.html();
    $boton.data('texto-original', textoActual); // Guarda el texto original
    $boton.prop('disabled', true);
    $boton.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${texto}`);
}

function desactivarLoaderBoton(idBoton) {
    const $boton = $(idBoton);
    const textoOriginal = $boton.data('texto-original');
    $boton.prop('disabled', false);
    $boton.html(textoOriginal);
}

//Función para validar por Keypress
function validarkeypress(er,e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
        e.preventDefault();
    }
}
//Función para validar por keyup
function validarkeyup(er,etiqueta,etiquetamensaje,mensaje){
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

function muestraMensajetost(icono, titulo, mensaje, tiempo) {
  Swal.fire({
    icon: icono,
    title: titulo,
    text: mensaje,
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: tiempo,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
}

/*||| Funcion para validar compas de formulario |||*/
function validarCampo(campo, regex, textoError, mensaje) {
  const valor = campo.val();

  if (campo.is("select")) {
   
    if (valor === "") {
      campo.removeClass("is-valid").addClass("is-invalid");
      textoError.text(mensaje);
    } else {
      campo.removeClass("is-invalid").addClass("is-valid");
      textoError.text("");
    }
  } else {
   
    if (regex.test(valor)) {
      campo.removeClass("is-invalid").addClass("is-valid");
      textoError.text("");
    } else {
      campo.removeClass("is-valid").addClass("is-invalid");
      textoError.text(mensaje);
    }
  }
}

function validarFormulario() {
    validarCampo($("#cedula"), /^[0-9]{7,8}$/, $("#textocedula"), "Formato incorrecto o vacio.");
    validarCampo($("#telefono"), /^[0-9]{4}[-]{1}[0-9]{7}$/, $("#textotelefono"), "Formato incorrecto o vacio.");
    validarCampo($("#nombre"), /^[a-zA-Z]{3,50}$/, $("#textonombre"), "Formato incorrecto o vacio.");
    validarCampo($("#apellido"), /^[a-zA-Z]{3,50}$/, $("#textoapellido"), "Formato incorrecto o vacio.");
    validarCampo($("#correo"), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/, $("#textocorreo"), "Formato incorrecto o vacio.");
    validarCampo($("#recontrasena"), /^.{8,16}$/, $("#textorecontrasena"), "Debe tener entre 8 y 16 caracteres.");
    validarCampo($("#clave"), /^.{8,16}$/, $("#textoclave"), "Debe tener entre 8 y 16 caracteres.");

    const clave = $("#clave").val();
    const recontrasena = $("#recontrasena").val();
    if (clave !== recontrasena) {
        $("#textoclave").text("Las contraseñas no coinciden.");
        $("#clave, #recontrasena").removeClass("is-valid").addClass("is-invalid");
    }
    return $(".is-invalid").length === 0;
}

//|||||||||||||| VALIDAR ENVIO cedula ||||||||||||||||||||||
function validarFor() {
    validarCampo($("#cedulac"), /^[0-9]{7,8}$/, $("#textocedulac"), "Formato incorrecto o vacio.");
    return $(".is-invalid").length === 0;
}


//|||||||||||||| VALIDAR ENVIO login ||||||||||||||||||||||
function validarForlogin() {
    let valido = true;

     if (!/^[0-9]{7,8}$/.test($("#usuario").val())) {
        $("#textousuario").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textousuario").text("");
    }

    if (!/^.{8,16}$/.test($("#pid").val())) {
        $("#textop").text("Debe tener entre 8 y 16 caracteres.");
        valido = false;
    } else {
        $("#textop").text("");
    }

    return valido;
}


$(document).ready(function() {
    //|||||| ENVIO OLVIDO CLAVE FORM
    $('#validarolvido').on("click", function(event) {
        event.preventDefault(); 
        if (validarFor()) {
            activarLoaderBoton('#validarolvido',"Validando...");

            var datos = new FormData($('#olvidoclave')[0]);
            datos.append('validarclave', 'validarclave');
            enviaAjax(datos);
        } else {
            muestraMensajetost("error","Error", "Debe Colocar el nro de cedula.", "2000");
        }
    });

    //|||||| ENVIO lOGIN FORM
    $('#ingresar').on("click", function(event) {
        event.preventDefault(); 
        if (validarForlogin()) {
          activarLoaderBoton('#ingresar',"Iniciando...");  
          
          var datos = new FormData($('#login')[0]);
          datos.append('ingresar', 'ingresar');
          enviaAjax(datos);
        } else {
          muestraMensajetost("error","Formato incorrecto o Vacio", "Debe colocar el formato correcto.", "2000");
        }
    });

    //|||||| ENVIO REGISTRO CLIENTE FORM
    $('#registrar').on("click", function(event) {
        event.preventDefault();

        if (validarFormulario()) {
            activarLoaderBoton('#registrar',"Guardando...");  
            
            var datos = new FormData($('#registrocliente')[0]);
            datos.append('registrar', 'registrar');
            enviaAjax(datos);
        } else {
           muestraMensajetost("error","Validaciones incorrectas", "Corrige los errores antes de enviar el formulario.", "3000");
        }
    });

  // EXPRESIONES REGULARES 
  $("#usuario").on("keypress",function(e){
    validarkeypress(/^[0-9\b]*$/,e);
  });
  
  $("#usuario").on("keyup",function(){
    validarkeyup(/^[0-9]{6,8}$/,$(this),
    $("#textousuario"),"El formato debe ser 1222333");
  });

  $("#pid").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
  });

  $("#pid").on("keyup", function() {
    validarkeyup(/^.{8,16}$/, $(this), $("#textop"), "El formato debe ser entre 8 y 16 caracteres");
  });
 
  $("#cedulac").on("keypress",function(e){
    validarkeypress(/^[0-9\b]*$/,e);
  });

   $("#cedulac").on("keyup", function () {
    validarCampo($(this),/^[0-9]{7,8}$/,
    $("#textocedulac"),"El formato debe ser 1222333");
  });

  $("#cedula").on("keypress",function(e){
    validarkeypress(/^[0-9\b]*$/,e);
  });

  $("#cedula").on("keyup", function () {
    validarCampo($(this),/^[0-9]{7,8}$/,
    $("#textocedula"),"El formato debe ser 1222333");
  });

   $("#telefono").on("keypress", function (e) {
      validarkeypress(/^[0-9-\-]*$/, e);
  });
  
   $("#telefono").on("keyup", function () {
      validarCampo($(this),/^[0-9]{4}[-]{1}[0-9]{7}$/,
      $("#textotelefono"), "El formato debe ser 0414-0000000");
    });
  
    $("#telefono").on("input", function () {
      var input = $(this).val().replace(/[^0-9]/g, '');
      if (input.length > 4) {
        input = input.substring(0, 4) + '-' + input.substring(4, 11);
      }
      $(this).val(input);
    });

     $("#correo").on("keypress", function (e) {
      validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
    });

    $("#correo").on("keyup", function () {
      validarCampo($(this), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/,
       $("#textocorreo"), "El formato debe incluir @ y ser válido.");
    });

    $("#nombre").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
      validarCampo($(this),/^[a-zA-Z]{3,30}$/,
      $("#textonombre"), "El formato debe ser solo letras");
    });

    $("#apellido").on("keypress", function (e) {
      validarkeypress(/^[a-z-A-Z-\b]*$/, e);
    });

    $("#apellido").on("keyup", function () {
      validarCampo($(this),/^[a-z-A-Z]{3,30}$/,
      $("#textoapellido"), "El formato debe ser solo letras");
    });

    $("#clave").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#clave").on("keyup", function() {
      validarCampo($(this),/^.{8,16}$/, 
      $("#textoclave"), "El formato debe ser entre 8 y 16 caracteres");
    });

    $("#recontrasena").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#recontrasena").on("keyup", function() {
      validarCampo($(this),/^.{8,16}$/, 
      $("#textorecontrasena"), "El formato debe ser entre 8 y 16 caracteres");
    });
    
});

// AJAX
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
      beforeSend: function () { },
      timeout: 10000,
      success: function (respuesta) {
        console.log(respuesta);
        try {
        var respuestaLimpiada = respuesta.split("<!DOCTYPE html>")[0].trim();
        var lee = JSON.parse(respuestaLimpiada);
        console.log("JSON parseado correctamente:", lee);
        } catch (error) {
        console.error("Error al parsear JSON:", error.message); 
        }
        try {

           if (lee.accion == 'incluir') {  // Registro Cliente
                if (lee.respuesta == 1) {  
                  muestraMensaje("success", 2000, "Se ha registrado con éxito", "Ya puede Iniciar Session con Exitosamente");
                  desactivarLoaderBoton('#registrar');
                  setTimeout(() => {
                     location = '?pagina=login';
                  }, 2000); 
              } else {
                  muestraMensaje("error", 2500, lee.text, "revise o cambialo y lo vuelve a intentar");
                  
                    $('#registrar').prop("disabled", false).html('<i class="fa-solid fa-user-plus"></i> Registrar');
                }
              
              } else if (lee.accion == 'ingresar') { // Login
                if (lee.respuesta == 1) {
                  muestraMensajetost("success","Inicio de Session", "Exitosamente", "1000"); 
                  desactivarLoaderBoton('#ingresar');
                    setTimeout(function () {
                     location = '?pagina=catalogo';
                  }, 1000);
              
                } else if (lee.respuesta == 2) {
                   muestraMensajetost("success","Inicio de Session - Personal", "Exitosamente", "1000");
                   desactivarLoaderBoton('#ingresar');
                   setTimeout(function () {
                      location = '?pagina=home';
                    }, 1000);
                
                }else{
                  muestraMensaje("error", 2000, lee.text);
                   desactivarLoaderBoton('#ingresar');
                }
              
              } else if (lee.accion == 'validarclave') { // olvido de clave
                if (lee.respuesta == 1) {
                     muestraMensaje("success", 1000, "Verificado con Exito", "");
                     desactivarLoaderBoton('#validarolvido');
                  setTimeout(function () {
                     location = '?pagina=olvidoclave';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                  desactivarLoaderBoton('#validarolvido');
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
      },
      complete: function () {
      }
    });
  }
  


