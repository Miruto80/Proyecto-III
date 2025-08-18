// assets/js/tipousuario.js

$(function(){
  // —— Helpers —— 
  function mostrarMensaje(icon, time, title, msg) {
    Swal.fire({ icon, timer: time, title, html: msg, showConfirmButton: false });
  }
  function mensajeOK(texto) {
    Swal.fire({ icon:'success', timer:1000, title:texto, showConfirmButton:false });
    setTimeout(()=> location.reload(), 1000);
  }
  function enviaAjax(fd) {
    $.ajax({
      url: '?pagina=tipousuario',
      method: 'POST',
      data: fd,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      success(res) {
        if (res.accion=='incluir'    && res.respuesta==1) return mensajeOK('Rol registrado con éxito');
        if (res.accion=='actualizar' && res.respuesta==1) return mensajeOK('Rol modificado con éxito');
        if (res.accion=='eliminar'   && res.respuesta==1) return mensajeOK('Rol eliminado con éxito');
        mostrarMensaje('error',2000,'Error',res.mensaje);
      },
      error() {
        mostrarMensaje('error',2000,'Error','Fallo de comunicación');
      }
    });
  }

  // —— 1) DataTables ——  
  $('#myTable').DataTable({
    language: { search:'_INPUT_', searchPlaceholder:'Buscar rol...' },
    paging:true, info:false, lengthChange:false
  });

  // —— 2) Registrar ——  
  $('#registrar').on('click', function(){
    const nombre = $('#nombre').val().trim();
    const nivel  = $('#nivel').val();
    if (!nombre || !/^.{3,30}$/.test(nombre)) {
      mostrarMensaje('info',2000,'Nombre inválido','Debe tener entre 3 y 30 letras');
      return;
    }
    if (nivel!=='2' && nivel!=='3') {
      mostrarMensaje('info',2000,'Nivel inválido','Seleccione nivel 2 o 3');
      return;
    }
    const fd = new FormData($('#u')[0]);
    fd.append('registrar','registrar');
    enviaAjax(fd);
  });

  // —— 3) Mostrar Modal Editar (Bootstrap) ——  
  $('#modificar').on('show.bs.modal', function(e){
    const btn = e.relatedTarget;
    const id  = parseInt(btn.getAttribute('data-id'),10);
    if (id===1 || id===2) {
      e.preventDefault();
      mostrarMensaje('info',3000,'Acción no permitida',
        'Los roles <strong>Administrador</strong> y <strong>Asesora de Ventas</strong><br>no pueden modificarse.');
      return;
    }
    $('#id_tipo_modificar').val(id);
    $('#nombre_modificar').val(btn.getAttribute('data-nombre'));
    $('#nivel_modificar').val(btn.getAttribute('data-nivel'));
    $('#estatus_modificar').val(btn.getAttribute('data-estatus'));
    // limpia validaciones previas
    $('#formModificar .is-valid, #formModificar .is-invalid')
      .removeClass('is-valid is-invalid');
    $('#formModificar span.text-danger').text('');
  });

  // —— 4) Editar ——  
  $('#btnModificar').on('click', function(){
    const nombre = $('#nombre_modificar').val().trim();
    const nivel  = $('#nivel_modificar').val();
    if (!nombre || !/^.{3,30}$/.test(nombre)) {
      mostrarMensaje('info',2000,'Nombre inválido','Debe tener entre 3 y 30 letras');
      return;
    }
    if (nivel!=='2' && nivel!=='3') {
      mostrarMensaje('info',2000,'Nivel inválido','Seleccione nivel 2 o 3');
      return;
    }
    const fd = new FormData($('#formModificar')[0]);
    fd.append('modificar','modificar');
    enviaAjax(fd);
  });

  // —— 5) Eliminar ——  
  $('.eliminar').on('click', function(e){
    const id = parseInt($(this).val(),10);
    if (id===1 || id===2) {
      e.preventDefault();
      mostrarMensaje('info',3000,'Acción no permitida',
        'Los roles <strong>Administrador</strong> y <strong>Asesora de Ventas</strong><br>no pueden eliminarse.');
      return;
    }
    e.preventDefault();
    Swal.fire({
      title:'¿Eliminar rol?',
      text:'Esta acción es irreversible.',
      icon:'warning',
      showCancelButton:true,
      confirmButtonText:'Sí, eliminar',
      cancelButtonText:'No'
    }).then(res=>{
      if (res.isConfirmed) {
        const fd = new FormData();
        fd.append('id_tipo',id);
        fd.append('eliminar','eliminar');
        enviaAjax(fd);
      }
    });
  });

// ——— AYUDA con Driver.js v1 ———
$('#btnAyuda').on("click", function () {
  // instancia el driver (igual que en Proveedor)
  const driver = window.driver.js.driver;
  const driverObj = new driver({
    nextBtnText:  'Siguiente',
    prevBtnText:  'Anterior',
    doneBtnText:  'Listo',
    popoverClass: 'driverjs-theme',
    closeBtn:     false,
    steps: [
      {
        element: '.table-color',
        popover: {
          title:       'Tabla de Tipos de Usuario',
          description: 'Aquí ves la lista de tipos de usuario registrados.',
          side:        'top'
        }
      },
      {
        element: 'button[data-bs-target="#registro"]',
        popover: {
          title:       'Registrar Tipo de Usuario',
          description: 'Abre el modal para crear un nuevo tipo de usuario.',
          side:        'bottom'
        }
      },
      {
        element: 'button[data-bs-target="#infoModal"]',
        popover: {
          title:       'Ver Información',
          description: 'Haz clic aquí para ver los detalles del tipo de usuario.',
          side:        'left'
        }
      },
      {
        element: '.modificar',
        popover: {
          title:       'Editar Tipo de Usuario',
          description: 'Modifica los datos de un tipo de usuario existente.',
          side:        'left'
        }
      },
      {
        element: '.eliminar',
        popover: {
          title:       'Eliminar Tipo de Usuario',
          description: 'Elimina un tipo de usuario del sistema.',
          side:        'left'
        }
      },
      {
        popover: {
          title:       '¡Listo!',
          description: 'Finalizaste la guía de ayuda del módulo Tipo de Usuario.'
        }
      }
    ]
  });

  driverObj.drive();
});

});
