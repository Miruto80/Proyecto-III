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


$(document).ready(function() {


   $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Desea guardar los cambios?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#58c731',
      cancelButtonColor: '#42515A',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData($('#datos')[0]);
        datos.append('actualizar', 'actualizar');
        enviaAjax(datos);
      }
    });
 });
 
  $("#cedula").on("keypress",function(e){
    validarkeypress(/^[0-9-\b]*$/,e);
  });
  
  $("#cedula").on("keyup",function(){
    validarkeyup(/^[0-9]{7,8}$/,$(this),
    $("#textocedula"),"El formato debe ser 1222333");
  });


   $("#telefono").on("keypress", function (e) {
      validarkeypress(/^[0-9-\-]*$/, e);
    });
  
    $("#telefono").on("keyup", function () {
      validarkeyup(/^[0-9]{4}[-]{1}[0-9]{7}$/, $(this),
        $("#textotelefono"), "El formato debe ser 0000-0000000");
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
      validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/, $(this),
          $("#textocorreo"), "El formato debe incluir @ y ser válido.");
    });

    $("#nombre").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
    validarkeyup(/^[a-zA-Z]{3,50}$/, $(this),
      $("#textonombre"), "El formato debe ser solo letras");
    });

    $("#apellido").on("keypress", function (e) {
      validarkeypress(/^[a-z-A-Z-\b]*$/, e);
    });

    $("#apellido").on("keyup", function () {
    validarkeyup(/^[a-z-A-Z]{3,50}$/, $(this),
      $("#textoapellido"), "El formato debe ser solo letras");
    });

    $("#clave").on("keypress", function(e) {
       validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#clave").on("keyup", function() {
      validarkeyup(/^.{8,16}$/, $(this), $("#textoclave"), "El formato debe ser entre 8 y 16 caracteres");
    })




    function validarFormulario() {
    let validar = true;

    // Validar cada campo con su expresión regular
    if (!/^.{8,16}$/.test($("#clave").val())) {
        $("#textoclave").text("El formato debe ser entre 8 y 16 caracteres");
        validar = false;
    }

    if (!/^.{8,16}$/.test($("#clavenueva").val())) {
        $("#textoclavenueva").text("El formato debe ser entre 8 y 16 caracteres");
        validar = false;
    }

    if (!/^.{8,16}$/.test($("#clavenuevac").val())) {
        $("#textoclavenuevac").text("El formato debe ser entre 8 y 16 caracteres");
        validar = false;
    }

    // Validar que clavenueva y clavenuevac sean iguales
    if ($("#clavenueva").val() !== $("#clavenuevac").val()) {
        $("#textoclavenuevac").text("Las contraseñas no coinciden").css("color", "red");
        validar = false;
    }

    // Si alguna validación falla, mostrar la alerta
    if (!validar) {
        Swal.fire({
            icon: "error",
            title: "Validaciones incorrectas",
            text: "Corrige los errores antes de enviar.",
            toast: true,
            position: "top",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    }

    return validar;
}

// Modificar el evento del botón para validar antes de enviar
$('#actualizarclave').on("click", function () {
    if (validarFormulario()) {
        Swal.fire({
            title: '¿Desea Cambiar la Clave?',
            text: '',
            icon: 'question',
            showCancelButton: true,
            color: "#00000",
            confirmButtonColor: '#58c731',
            cancelButtonColor: '#42515A',
            confirmButtonText: ' Si, Cambiar ',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData($('#formclave')[0]);
                datos.append('actualizarclave', 'actualizarclave');
                enviaAjax(datos);
            }
        });
    }
});

});


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
        var lee = JSON.parse(respuesta);
        try {
  
           if (lee.accion == 'clave') {
                if (lee.respuesta == 1) {  
                  muestraMensaje("success", 1500, "Se ha Cambiado su clave con exito", "");
                  setTimeout(function () {
                    location = '?pagina=datos';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, lee.text, "");
                }
              } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                  setTimeout(function () {
                    location = '?pagina=datos&m=a';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, lee.text, "");
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
  