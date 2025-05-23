document.addEventListener("DOMContentLoaded", function() {
  var editarModal = document.getElementById("editarModal");
  editarModal.addEventListener("show.bs.modal", function(event) {
    var button = event.relatedTarget; // Botón que activó el modal
    var idPersona = button.getAttribute("data-id");
    var cedula = button.getAttribute("data-cedula");
    var correo = button.getAttribute("data-correo");

    // Asignar valores al modal
    document.getElementById("modalIdPersona").value = idPersona;
    document.getElementById("modalCedula").value = cedula;
    document.getElementById("modalCorreo").value = correo;
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

$(document).on("click", ".favorito, .clienteactivo, .malcliente", function () {
    var idPersona = $(this).data("id"); 
    var tipoAccion = $(this).hasClass("favorito") ? "favorito" : 
                     $(this).hasClass("clienteactivo") ? "clienteactivo" : 
                     $(this).hasClass("malcliente") ? "malcliente" : "";

    // Mostrar alerta de confirmación antes de enviar los datos
    Swal.fire({
        title: `¿Desea cambiar el estado a ${tipoAccion}?`,
        text: "Esta acción actualizará el estado del cliente.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#58c731",
        cancelButtonColor: "#42515A",
        confirmButtonText: "Sí",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            $("#id_persona_hidden").val(idPersona);

            var datos = new FormData();
            datos.append(tipoAccion, tipoAccion);
            datos.append("id_persona", idPersona);
            enviaAjax(datos);
        }
    });
});


$(document).ready(function() {

  $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Desea Cambiar estos datos del cliente?',
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
       
        var datos = new FormData($('#formdatosactualizar')[0]);
        datos.append('actualizar', 'actualizar');
        enviaAjax(datos);
      }
    });
 });


   $("#modalCorreo").on("keypress", function (e) {
      validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
    });

    $("#modalCorreo").on("keyup", function () {
      validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/, $(this),
          $("#textocorreomodal"), "El formato debe incluir @ y ser válido.");
    });


    $("#modalCedula").on("keypress", function(e) {
    validarkeypress(/^[0-9\b]*$/, e); // Permitir solo números y la tecla de retroceso
    });
    
    $("#modalCedula").on("keyup", function() {
    validarkeyup(/^[0-9]{7,8}$/, $(this), $("#textocedulamodal"), "El formato debe ser 1222333");
    })


});


$

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
  
           if (lee.accion == 'favorito') {
                if (lee.respuesta == 1) {  
                  muestraMensaje("success", 1500, "Se ha Cambio  Existosamente", "Cliente Favorito");
                  setTimeout(function () {
                    location = '?pagina=cliente';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                }
              } else if (lee.accion == 'malcliente') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha Cambio  Existosamente", "Mal Clientes");
                  setTimeout(function () {
                    location = '?pagina=cliente';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                }
              } else if (lee.accion == 'clienteactivo') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha Cambio  Existosamente", "Cliente Activo Frecuente");
                  setTimeout(function () {
                     location = '?pagina=cliente';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                }
              } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                  setTimeout(function () {
                     location = '?pagina=cliente';
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
  