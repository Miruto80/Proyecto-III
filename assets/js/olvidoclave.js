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


function cambiarVista() {
    let vistaActual = document.getElementById("forclave").parentNode.parentNode;
    let nuevaVista = `
        <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 600px;">
        <div class="text-center">
            <img src="assets/img/logo2.png" class="img-fluid mb-1" style="width:100px;">
        </div>
        <h4 class="text-center text-primary mb-1">Olvido de Contraseña</h4>
         <p class="text-center">Paso: 2 de 3</p>      
        <hr class="bg-dark">

        <form action="?pagina=olvidoclave" method="POST" id="forcambio_clave" autocomplete="off">
            <div class="mb-3 text-center">
                
                <p><b>Importante: Por favor, ingresa el código que se envió a tu correo electrónico.</b></p>
                   
               
                <label class="form-label fw-bold text-g">Ingrese el código de verificación</label>
                <div class="d-flex justify-content-center gap-1">
                    <input type="text" maxlength="1" class="form-control text-center codigo" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="form-control text-center codigo" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="form-control text-center codigo" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="form-control text-center codigo" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="form-control text-center codigo" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="form-control text-center codigo" inputmode="numeric" pattern="[0-9]*">
                </div>
                <input type="hidden" id="codigo" name="codigo">
                <span id="textocodigo"></span>
            </div>

            <div class="text-center">
            <p>Te recomendamos revisar también la bandeja de spam o los correos no deseados en caso de que no lo encuentres.</p>   
       <p><b>Reenviar el codigo 1:00 </b></p>

            </div>
            
            <div class="d-flex justify-content-between">
                <button type="submit" name="cerrarolvido" class="btn btn-danger">Cancelar</button>
                <button type="button" class="btn btn-success" id="validarNuevo" disabled>Continuar</button>
            </div>
        </form>
    </div>
</div>

    `;

    vistaActual.innerHTML = nuevaVista;
$("#codigo").on("keypress", function (e) {
  const value = $(this).val();
  const char = String.fromCharCode(e.which);
  // Allow backspace (char code 8)
  if (e.which === 8) {
    return;
  }
  // Prevent input if already 6 digits
  if (value.length >= 6) {
    e.preventDefault();
    return;
  }
  // Allow only digits
  if (!/^[0-9]$/.test(char)) {
    e.preventDefault();
  }
});

$("#codigo").on("keyup", function () {
  
  validarkeyup(/^[0-9]{6}$/, $(this),
      $("#textocodigo"), "Debe contener exactamente 6 dígitos numéricos.");
});



document.querySelectorAll('.codigo').forEach((input, index, inputs) => {
    input.addEventListener('input', (e) => {
       
        input.value = input.value.replace(/[^0-9]/g, '');

        if (input.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }

        validarCodigo();
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

function validarCodigo() {
    const valores = Array.from(document.querySelectorAll('.codigo')).map(i => i.value);
    const completo = valores.every(val => val !== '');
    document.getElementById('validarNuevo').disabled = !completo;

    if (completo) {
        document.getElementById('codigo').value = valores.join('');
    }
}
}



// Función para cambiar a la vista de confirmación
function cambiarVistaConfirmacion() {
    let vistaActual = document.getElementById("forcambio_clave").parentNode.parentNode;
    let nuevaVista = `
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card p-4 shadow-lg" style="width: 600px;">
                <div class="text-center">
                    <img src="assets/img/logo2.png" class="img-fluid mb-1" style="width:100px;">
                </div>
              
                <h4 class="text-center text-primary mb-1">Cambiar la Contraseña</h4>
                 <p class="text-center">Paso: 3 de 3</p>
                <hr class="bg-dark">
          
                <form action="?pagina=olvidoclave" method="POST" id="forconfirmacion" autocomplete="off">
                    <div class="mb-3 text-center mb-5">
                        <label for="clave" class="form-label fw-bold text-g">Constraseña Nueva</label>
                        <input type="text" id="clavenueva" name="clavenueva" class="form-control text-center" placeholder="Constraseña Nuevo">
                        <span id="textoclavenueva" class="text-danger"></span>
                            <br>
                         <label for="clavenueva" class="form-label fw-bold text-g">Confirmar Contraseña</label>
                        <input type="text" id="clavenuevac" name="confirmar" class="form-control text-center" placeholder="Confirmar la constraseña nueva">
                         <span id="textoclavenuevac" class="text-danger"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="cerrarconfirmacion" class="btn btn-danger">Cancelar</button>
                        <button type="button" class="btn btn-success" id="validarclave">Cambiar Clave</button>
                    </div>
                </form>
            </div>
        </div>
    `;

    vistaActual.innerHTML = nuevaVista;
    
    $("#clavenueva").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#clavenueva").on("keyup", function() {
    validarkeyup(/^.{8,16}$/, $(this), $("#textoclavenueva"), "El formato debe ser entre 8 y 16 caracteres");
    });


    $("#clavenuevac").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#clavenuevac").on("keyup", function() {
    validarkeyup(/^.{8,16}$/, $(this), $("#textoclavenuevac"), "El formato debe ser entre 8 y 16 caracteres");
    });
 
}


$(document).ready(function() {
 

 $('#validar').on("click", function(event) {
        event.preventDefault(); // Evita la recarga de la página

        if (validarFormulario()) {
             var datos = new FormData($('#forclave')[0]);
             datos.append('validar', 'validar');
              // Deshabilitar el botón y agregar el spinner
        $('#validar').prop('disabled', true);
        $('#validar').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validando...');

        enviaAjax(datos).then(() => {
            // Restaurar el botón después de la solicitud
            $('#validar').prop('disabled', false);
            $('#validar').html('Validar');
        }).catch(() => {
            // En caso de error, también restaurar el botón
            $('#validar').prop('disabled', false);
            $('#validar').html('Validar');
        });
        } else {
             Swal.fire({
            icon: "error",
            title: "Formato incorrecto",
            text: "Por favor, colocolar el correo electronico",
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

     $("#correo").on("keypress", function (e) {
      validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
    });

    $("#correo").on("keyup", function () {
      validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/, $(this),
          $("#textocorreo"), "El formato debe incluir @ y ser válido.");
    });

  $("#clavenueva").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#clavenueva").on("keyup", function() {
    validarkeyup(/^.{8,16}$/, $(this), $("#textoclavenueva"), "El formato debe ser entre 8 y 16 caracteres");
    });


    $("#clavenuevac").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#clavenuevac").on("keyup", function() {
    validarkeyup(/^.{8,16}$/, $(this), $("#textoclavenuevac"), "El formato debe ser entre 8 y 16 caracteres");
    });

});

$(document).on("click", "#validarNuevo", function(event) {
    event.preventDefault(); // Evita la recarga de la página

     if (validarFormulariocodigo()) {
        var datos = new FormData($('#forcambio_clave')[0]);
        datos.append('validarcodigo', 'validarcodigo');
        enviaAjax(datos);
    } else {
             Swal.fire({
            icon: "error",
            title: "Formato incorrecto",
            text: "Por favor, colocolar en el formato correcto.",
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


   // Agregar event listener al nuevo botón
    $(document).on("click", "#validarclave", function(event) {
        event.preventDefault(); // Evita la recarga de la página

        if (validarFormularioclave()) {
             var datos = new FormData($('#forconfirmacion')[0]);
             datos.append('validarclave', 'validarclave');
             enviaAjax(datos);
        } else {
             Swal.fire({
            icon: "error",
            title: "Formato incorrecto",
            text: "Por favor, colocolar en el formato correcto.",
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


function validarFormulario() {
    let valido = true;

    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/.test($("#correo").val())) {
        $("#textocorreo").text("Formato incorrecto.");
        valido = false;
    } else {
        $("#textocorreo").text("");
    }


    return valido;
}

function validarFormulariocodigo() {
    let valido = true;

   if (!/^[0-9]{6}$/.test($("#codigo").val())) {
    $("#textocodigo").text("Debe contener exactamente 6 dígitos numéricos.");
    valido = false;
} else {
    $("#textocodigo").text("");
}

    return valido;
}




function validarFormularioclave() {
    let validar = true;

    // Validar cada campo con su expresión regular
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

    return validar;
}


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
        // Eliminar contenido HTML en caso de que aparezca
        var respuestaLimpiada = respuesta.split("<!DOCTYPE html>")[0].trim();
        var lee = JSON.parse(respuestaLimpiada);
        console.log("JSON parseado correctamente:", lee);
        } catch (error) {
            console.error("Error al parsear JSON:", error.message);
        }
        
        try {
  
           if (lee.accion == 'validar') {
              if (lee.respuesta == 1) {  
                muestraMensaje("success", 2000, "Se ha enviado un código enviado al correo", "");

                // Espera un momento antes de cambiar la vista
                setTimeout(() => {
                    cambiarVista();
                }, 2200);
            } else {
                muestraMensaje("error", 1000, lee.text, "");
                 $('#validar').prop('disabled', false);
                 $('#validar').html('Validar');
             }
            } else if (lee.accion == 'validarcodigo') {
            if (lee.respuesta == 1) {
                muestraMensaje("success", 2000, "Codigo de verificación correcto", "");
        
                // Después de la alerta, espera un momento y cambia la vista
                setTimeout(() => {
                    cambiarVistaConfirmacion();
                }, 2200);
            } else {
                muestraMensaje("error", 2000, lee.text, "");
            }
            } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2500, "Se ha Cambiado su Constraseña con exito ", "ya puede iniciar su seccion");
                  setTimeout(function () {
                     location = '?pagina=login';
                  }, 2500);
                }else if (lee.respuesta == 2) {
                  muestraMensaje("success", 2500, "Se ha Cambiado su Constraseña con exito ", "ya puede iniciar su seccion");
                  setTimeout(function () {
                     location = '?pagina=login';
                  }, 2500);
                }else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
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
  


