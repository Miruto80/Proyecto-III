/*||| Funcion para cambiar el boton a loader |||*/
function activarLoaderBoton(idBoton, texto = 'Cargando...') {
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

document.addEventListener("DOMContentLoaded", function() {
  var editarModal = document.getElementById("editarModal");
  editarModal.addEventListener("show.bs.modal", function(event) {
    var button = event.relatedTarget; // Botón que activó el modal
    var idPersona = button.getAttribute("data-id");
    var cedula = button.getAttribute("data-cedula");
    var correo = button.getAttribute("data-correo");
    var estatus = button.getAttribute("data-estatus"); 

    // Asignar valores al modal
    document.getElementById("modalIdPersona").value = idPersona;
    document.getElementById("modalCedula").value = cedula;
    document.getElementById("modalCorreo").value = correo;
    document.getElementById("modalce").value = cedula;
    document.getElementById("modalco").value = correo;
    document.getElementById("modalestatus").value = estatus;
    document.getElementById("modalestatus").textContent = estatus == "1" ? "Activo - Actual" : estatus == "2" ? "Inactivo - Actual" : "Desconocido";
  });
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

$(document).ready(function() {

  $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Actualizar cliente?',
      text: 'Confirma los cambios.',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#50c063ff',
      cancelButtonColor: '#42515A',
      confirmButtonText: ' Si, Actualizar ',
      cancelButtonText: 'NO'
    }).then((result) => {
     if (result.isConfirmed) {
        // Validación de los campos antes de enviar
            let cedulaValida = /^[0-9]{7,8}$/.test($("#modalCedula").val());
            let correoValido = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/.test($("#modalCorreo").val());

            if (!cedulaValida) {
                $("#modalCedula").addClass("is-invalid");
                $("#textocedulamodal").show();
            } else {
                $("#modalCedula").removeClass("is-invalid").addClass("is-valid");
                $("#textocedulamodal").hide();
            }

            if (!correoValido) {
                $("#modalCorreo").addClass("is-invalid");
                $("#textocorreomodal").show();
            } else {
                $("#modalCorreo").removeClass("is-invalid").addClass("is-valid");
                $("#textocorreomodal").hide();
            }

            // Si todos los campos son válidos, enviar el formulario
            if (cedulaValida && correoValido) {
            activarLoaderBoton('#actualizar');
            var datos = new FormData($('#formdatosactualizar')[0]);
            datos.append('actualizar', 'actualizar');
            enviaAjax(datos);
          }
      }
    });
 }); 


    $("#modalCedula").on("keypress",function(e){
      validarkeypress(/^[0-9\b]*$/,e);
    });

    $("#modalCedula").on("keyup", function () {
      validarCampo($(this),/^[0-9]{7,8}$/,
    $("#textocedulamodal"),"El formato debe ser 1222333");
    });
    
    $("#modalCorreo").on("keypress", function (e) {
          validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
    });

     $("#modalCorreo").on("keyup", function () {
      validarCampo($(this), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/,
       $("#textocorreomodal"), "El formato debe incluir @ y ser válido .");
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
  
           if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                  desactivarLoaderBoton('#actualizar'); 
                  setTimeout(function () {
                     location = '?pagina=cliente';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                  desactivarLoaderBoton('#actualizar'); 
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
  

   $('#ayudacliente').on("click", function () {
  
  const driver = window.driver.js.driver;
  
  const driverObj = new driver({
    nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior',
        doneBtnText: 'Listo',
    popoverClass: 'driverjs-theme',
    closeBtn:false,
    steps: [
      { element: '.table-color', popover: { title: 'Tabla de cliente', description: 'Aqui es donde se guardaran los registros de los clientes', side: "left", }},
      { element: '.modificar', popover: { title: 'Modificar datos del cliente', description: 'Este botón te permite editar la cedula y el correo de un cliente registrado.', side: "left", align: 'start' }},
      { element: '.dt-search', popover: { title: 'Buscar', description: 'Te permite buscar un cliente en la tabla', side: "right", align: 'start' }},
      { popover: { title: 'Eso es todo', description: 'Este es el fin de la guia espero hayas entendido'} }
    ]
  });
  
  // Iniciar el tour
  driverObj.drive();
});