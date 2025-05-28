// ||||||||||||||| OJITO ||||||||||||||||||||
const passwordInput = document.getElementById('password');
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

// ||||||||||||||| MODAL ||||||||||||||||||||
 document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("myModal");
        let clickCount = 0; // Contador de clics en el fondo

        // Inicialmente ocultar el modal
        modal.style.display = "none";

        // Mostrar el modal solo cuando se presione el botón
        document.getElementById("openModal").addEventListener("click", function() {
            modal.style.display = "flex";
            setTimeout(() => modal.classList.add("show"), 10);
            clickCount = 0; // Reiniciar contador al abrir el modal
        });

        // Función para cerrar el modal solo con doble clic
        function closeModal() {
            modal.classList.remove("show");
            setTimeout(() => modal.style.display = "none", 300);
            clickCount = 0; // Reiniciar el contador
        }

        document.getElementById("closeModal").addEventListener("click", closeModal);
        document.getElementById("closeModalFooter").addEventListener("click", closeModal);

        // Detectar doble clic en el fondo oscuro para cerrar el modal
        modal.addEventListener("click", function(event) {
            if (event.target === modal) {
                clickCount++;
                
                if (clickCount === 2) {
                    closeModal();
                }

                setTimeout(() => { clickCount = 0; }, 500); // Reinicia el contador después de 500ms
            }
        });
    });


 // ||||||||||||||| MODAL ||||||||||||||||||||
 document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("myModalclave");
        let clickCount = 0; // Contador de clics en el fondo

        // Inicialmente ocultar el modal
        modal.style.display = "none";

        // Mostrar el modal solo cuando se presione el botón
        document.getElementById("openModalclave").addEventListener("click", function() {
            modal.style.display = "flex";
            setTimeout(() => modal.classList.add("show"), 10);
            clickCount = 0; // Reiniciar contador al abrir el modal
        });

        // Función para cerrar el modal solo con doble clic
        function closeModal() {
            modal.classList.remove("show");
            setTimeout(() => modal.style.display = "none", 300);
            clickCount = 0; // Reiniciar el contador
        }

        document.getElementById("closeModalclave").addEventListener("click", closeModal);
        document.getElementById("closeModalFooterclave").addEventListener("click", closeModal);

        // Detectar doble clic en el fondo oscuro para cerrar el modal
        modal.addEventListener("click", function(event) {
            if (event.target === modal) {
                clickCount++;
                
                if (clickCount === 2) {
                    closeModal();
                }

                setTimeout(() => { clickCount = 0; }, 500); // Reinicia el contador después de 500ms
            }
        });
    });

//|||||||||||||| VALIDAR ENVIO ||||||||||||||||||||||
function validarFormulario() {
    let valido = true;

    if (!/^[0-9]{7,8}$/.test($("#cedula").val())) {
        $("#textocedula").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textocedula").text("");
    }

    if (!/^[0-9]{4}[-]{1}[0-9]{7}$/.test($("#telefono").val())) {
        $("#textotelefono").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textotelefono").text("");
    }

    if (!/^[a-zA-Z]{3,50}$/.test($("#nombre").val())) {
        $("#textonombre").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textonombre").text("");
    }

    if (!/^[a-zA-Z]{3,50}$/.test($("#apellido").val())) {
        $("#textoapellido").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textoapellido").text("");
    }

    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/.test($("#correo").val())) {
        $("#textocorreo").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textocorreo").text("");
    }

    if (!/^.{8,16}$/.test($("#clave").val())) {
        $("#textoclave").text("Debe tener entre 8 y 16 caracteres.");
        valido = false;
    } else {
        $("#textoclave").text("");
    }

    return valido;
}

$('#cedula').blur(function (e) {
var cedula = $('#cedula').val();
// va al controlador libro.php e instancia el metodo buscar
$.post('?pagina=login', {cedula}, function(response){
// si response es diferente de vacio lo encontro en la tabla 
if (response != '') {
    alert('El Proveedor ya se Encuentra Registrado');
                }
    },'json');
});




//|||||||||||||| VALIDAR ENVIO cedula ||||||||||||||||||||||
function validarFor() {
    let valido = true;

     if (!/^[0-9]{7,8}$/.test($("#cedulac").val())) {
        $("#textocedulac").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textocedulac").text("");
    }

    return valido;
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

    if (!/^.{8,16}$/.test($("#password").val())) {
        $("#textopassword").text("Debe tener entre 8 y 16 caracteres.");
        valido = false;
    } else {
        $("#textopassword").text("");
    }

    return valido;
}

//|||||| ENVIO OLVIDO CLAVE FORM
$(document).ready(function() {
    $('#validarolvido').on("click", function(event) {
        event.preventDefault(); // Evita la recarga de la página

        if (validarFor()) {
            var datos = new FormData($('#olvidoclave')[0]);
            datos.append('validarclave', 'validarclave');
             // Agregar loader al botón
        $('#validarolvido').prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Validanddo...');

        // Enviar los datos solo si todas las validaciones son correctas
        enviaAjax(datos).always(function() {
        
         // Restaurar botón después de completar el proceso
        $('#validarolvido').prop("disabled", false).html('Validar');
         });
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Debe Colocar el nro de cedula",
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
    });

//|||||| ENVIO lOGIN FORM
    $('#ingresar').on("click", function(event) {
        event.preventDefault(); // Evita la recarga de la página

        if (validarForlogin()) {
        var datos = new FormData($('#login')[0]);
        datos.append('ingresar', 'ingresar');

        // Agregar loader al botón
        $('#ingresar').prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Iniciando...');

        // Enviar los datos solo si todas las validaciones son correctas
        enviaAjax(datos).always(function() {
        
         // Restaurar botón después de completar el proceso
        $('#ingresar').prop("disabled", false).html('Ingresar');
        });
        } else {
            Swal.fire({
                icon: "error",
                title: "Formato incorrecto o Vacio",
                text: "Debe colocar el formato correcto.",
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
    });
});


//|||||| ENVIO REGISTRO CLIENTE FORM
$(document).ready(function() {
    $('#registrar').on("click", function(event) {
        event.preventDefault(); // Evita la recarga de la página

        if (validarFormulario()) {
            var datos = new FormData($('#registrocliente')[0]);
            datos.append('registrar', 'registrar');
            
        // Agregar loader al botón
        $('#registrar').prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Cargando...');

        // Enviar los datos solo si todas las validaciones son correctas
        enviaAjax(datos).always(function() {
        
         // Restaurar botón después de completar el proceso
        $('#registrar').prop("disabled", false).html('<i class="fa-solid fa-user-plus"></i> Registrar');
          });
        } else {
            Swal.fire({
                icon: "error",
                title: "Validaciones incorrectas",
                text: "Corrige los errores antes de enviar el formulario.",
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
    });
});




//|||||| VALIDACION DEL FORM LOGIN Y REGISTRO CLIENTE
$(document).ready(function(){
  //VALIDACION DE DATOS  
  $("#usuario").on("keypress",function(e){
    validarkeypress(/^[0-9\b]*$/,e);
  });
  
  $("#usuario").on("keyup",function(){
    validarkeyup(/^[0-9]{6,8}$/,$(this),
    $("#textousuario"),"El formato debe ser 1222333");
  });

  $("#password").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
  });
  $("#password").on("keyup", function() {
    validarkeyup(/^.{8,16}$/, $(this), $("#textopassword"), "El formato debe ser entre 8 y 16 caracteres");
  });
 
  $("#cedulac").on("keypress",function(e){
    validarkeypress(/^[0-9\b]*$/,e);
  });
  
  $("#cedulac").on("keyup",function(){
    validarkeyup(/^[0-9]{7,8}$/,$(this),
    $("#textocedulac"),"El formato debe ser 1222333");
  });

  $("#cedula").on("keypress",function(e){
    validarkeypress(/^[0-9\b]*$/,e);
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



});
  

//||||||||| CERRAR MODAL  
function closeModal() {
    const modal = document.getElementById("myModal");
    modal.classList.remove("show");
    setTimeout(() => modal.style.display = "none", 300);
}


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

function muestraMensajetost(icono, titulo, mensaje, tiempo = 1000) {
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
        // Eliminar contenido HTML en caso de que aparezca
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
                
                   
                  setTimeout(() => {
                  document.getElementById("cedula").value = "";
                  document.getElementById("nombre").value = "";
                  document.getElementById("apellido").value = "";
                  document.getElementById("telefono").value = "";
                  document.getElementById("correo").value = "";
                  document.getElementById("clave").value = "";

               
                  $('#registrar').prop("disabled", false).html('<i class="fa-solid fa-user-plus"></i> Registrar');
                
                  setTimeout(() => {
                     closeModal(); 
                    }, 500);
                  }, 2000); 
              } else {
                  muestraMensaje("error", 2500, lee.text, "revise o cambialo y lo vuelve a intentar");
                  
                    $('#registrar').prop("disabled", false).html('<i class="fa-solid fa-user-plus"></i> Registrar');
                }
              
              } else if (lee.accion == 'ingresar') { // Login
                if (lee.respuesta == 1) {
                  muestraMensajetost("success","Inicio de Session", "Exitosamente");
                     $('#ingresar').prop("disabled", false).html('Ingresar');
                    setTimeout(function () {
                     location = '?pagina=catalogo';
                  }, 1000);
              
                } else if (lee.respuesta == 2) {
                   muestraMensajetost("success","Inicio de Session - Personal", "Exitosamente");
                   $('#ingresar').prop("disabled", false).html('Ingresar');
                setTimeout(function () {
                     location = '?pagina=home';
                  }, 1000);
              
                }else{
                  muestraMensaje("error", 2000, lee.text);
                   $('#ingresar').prop("disabled", false).html('Ingresar');
                }
              
              } else if (lee.accion == 'validarclave') { // olvido de clave
                if (lee.respuesta == 1) {
                     muestraMensaje("success", 1000, "Verificado con Exito", "");
                      $('#validarolvido').prop("disabled", false).html('Validar');
                  setTimeout(function () {
                     location = '?pagina=olvidoclave';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                  $('#validarolvido').prop("disabled", false).html('Validar');
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
  


