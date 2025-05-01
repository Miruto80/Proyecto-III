$(document).ready(function() {


    $('#registrar').on("click", function () {
           
      var datos = new FormData($('#u')[0]);
      datos.append('registrar', 'registrar');
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
    
              if (lee.accion == 'consultar') {
                  crearConsulta(lee.datos);
                }
                else if (lee.accion == 'incluir') {
                  if (lee.respuesta == 1) {
                       $('#nombre').val('');  
                    muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
                    setTimeout(function () {
                    }, 1000);
                  } else {
                    muestraMensaje("error", 1000, "ERROR", "ERROR");
                  }
                } else if (lee.accion == 'actualizar') {
                  if (lee.respuesta == 1) {
                    muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                    setTimeout(function () {
                      location = '?pagina=marca';
                    }, 1000);
                  } else {
                    muestraMensaje("error", 2000, "ERROR", "ERROR");
                  }
                } else if (lee.accion == 'eliminar') {
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
    
    $("#archivo").on("change",function(){
	
      mostrarImagen(this);
    });
    //			
    
    $("#imagen").on("error",function(){
      $(this).prop("src","assets/img/logo.PNG");
    });
      function mostrarImagen(f) {
      
      var tamano = f.files[0].size;
         var megas = parseInt(tamano / 1024);
         
         if(megas > 1024){
         muestraMensaje("La imagen debe ser igual o menor a 1024 K");
             $(f).val('');
         }
         else{	
         if (f.files && f.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
           $('#imagen').attr('src', e.target.result);
          }
          reader.readAsDataURL(f.files[0]);
         }
       }
    }