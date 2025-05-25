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
                <h4 class="text-center color-g mb-1">Olvido de Contraseña</h4>
                <hr class="bg-dark">
              
                <form action="?pagina=olvidoclave" method="POST" id="forcambio_clave" autocomplete="off">
                    <div class="mb-3 text-center">
                        <div class="alert alert-secondary text-white" role="alert">
                          <strong>Importante!</strong> colocar el codigo que se envio al correo!
                        </div>
                        <label for="input" class="form-label fw-bold text-g">Ingrese el código de verificación</label>
                        <input type="text" id="codigo" name="codigo" class="form-control text-center" placeholder="codigo de verificación">
                        <span id="textocorreo"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="cerrarolvido" class="btn btn-danger">Cancelar</button>
                        <button type="button" class="btn btn-success" id="validarNuevo">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    `;

    vistaActual.innerHTML = nuevaVista;

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
                <h4 class="text-center color-g mb-1">Código de Confirmación</h4>
                <hr class="bg-dark">
          
                <form action="?pagina=confirmar_codigo" method="POST" id="forconfirmacion" autocomplete="off">
                    <div class="mb-3 text-center">
                        <label for="codigo" class="form-label fw-bold text-g">Ingrese el código de verificación</label>
                        <input type="text" id="codigo" name="codigo" class="form-control text-center" placeholder="Código de confirmación">
                        <span id="textocodigo"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="cerrarconfirmacion" class="btn btn-danger">Cancelar</button>
                        <button type="button" class="btn btn-success" id="validarCodigo">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    `;

    vistaActual.innerHTML = nuevaVista;

    // Agregar event listener al nuevo botón
    $(document).on("click", "#validarCodigo", function(event) {
        event.preventDefault();
        alert("Código de confirmación ingresado correctamente.");
    });
}


$(document).ready(function() {
 

 $('#validar').on("click", function(event) {
        event.preventDefault(); // Evita la recarga de la página

        if (validarFormulario()) {
             var datos = new FormData($('#forclave')[0]);
             datos.append('validar', 'validar');
             enviaAjax(datos);
        } else {
             Swal.fire({
            icon: "info",
            title: "Dato Vacio",
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



});

$(document).on("click", "#validarNuevo", function(event) {
    event.preventDefault(); // Evita la recarga de la página

    
        var datos = new FormData($('#forcambio_clave')[0]);
        datos.append('validarcodigo', 'validarcodigo');
        enviaAjax(datos);
   
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
  
           if (lee.accion == 'validar') {
              if (lee.respuesta == 1) {  
                muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");

        // Espera un momento antes de cambiar la vista
        setTimeout(() => {
            cambiarVista();
        }, 1200);
    } else {
        muestraMensaje("error", 1000, lee.text, "");
    }
  } else if (lee.accion == 'validarcodigo') {
    if (lee.respuesta == 1) {
        muestraMensaje("success", 2000, "Se ha Modificado con éxito", "Su registro se ha actualizado exitosamente");

        // Después de la alerta, espera un momento y cambia la vista
        setTimeout(() => {
            cambiarVistaConfirmacion();
        }, 2200);
    } else {
        muestraMensaje("error", 2000, lee.text, "");
    }
} else if (lee.accion == 'eliminar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
                  setTimeout(function () {
                     location = '?pagina=usuario';
                  }, 1000);
                } else {
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
  


