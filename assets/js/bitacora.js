$(document).ready(function () {
  $('.eliminar').on('click', function (e) {
    e.preventDefault(); // Evita que se envíe el formulario

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
        var form = $(this).closest('form');
        var datos = new FormData(form[0]); // Se accede al elemento DOM dentro del objeto jQuery
        enviaAjax(datos);
      }
    });
  });
});




$(document).ready(function() {


  $('#entrar').on("click", function () {
         
    var datos = new FormData($('#u')[0]);
    datos.append('entrar', 'entrar');
    enviaAjax(datos);
 
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
  
            if (lee.accion == 'eliminar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
                  setTimeout(function () {
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




  