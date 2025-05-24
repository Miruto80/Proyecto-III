var deleteButtons = document.querySelectorAll('.eliminar');
// Recorre los botones de eliminar y añade un receptor de eventos a cada uno de ellos
deleteButtons.forEach(function (button) {
  button.addEventListener('click', function (e) {
    e.preventDefault(); // Evita que se envíe el formulario
    // Muestra la alerta de confirmación
    Swal.fire({
      title: '¿Desea eliminar los datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#38b96f',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var form = this.closest('form');
        var datos = new FormData(form);
        enviaAjax(datos);
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function() {
  var editarModal = document.getElementById("editarModal");
  editarModal.addEventListener("show.bs.modal", function(event) {
    var button = event.relatedTarget; // Botón que activó el modal
    var idPersona = button.getAttribute("data-id");
    var cedula = button.getAttribute("data-cedula");
    var correo = button.getAttribute("data-correo");
    var id_tipo = button.getAttribute("data-id_tipo");
    var nombre_rol = button.getAttribute("data-nombre_rol");

    // Asignar valores al modal
    document.getElementById("modalIdPersona").value = idPersona;
    document.getElementById("modalCedula").value = cedula;
    document.getElementById("modalCorreo").value = correo;
    document.getElementById("modalrol").value = id_tipo;
    document.getElementById("modalrol").textContent = nombre_rol;
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


  $('#registrar').on("click", function () {
         
    var datos = new FormData($('#u')[0]);
    datos.append('registrar', 'registrar');
    enviaAjax(datos);
 
  });

  
  $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Desea Cambiar estos datos del Usuario?',
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
  
           if (lee.accion == 'incluir') {
                if (lee.respuesta == 1) {  
                  muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
                  setTimeout(function () {
                    location = '?pagina=usuario';
                  }, 1000);
                } else {
                  muestraMensaje("error", 1000, "ERROR", "ERROR");
                }
              } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                  setTimeout(function () {
                    location = '?pagina=usuario';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, lee.text,"");
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
  