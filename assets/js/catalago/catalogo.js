window.onload = function() {
    if (window.location.search.includes("busqueda=")) {
        window.history.replaceState({}, document.title, "index.php?pagina=catalogo_producto");
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll('.filtro-checkbox');
    const productos = document.querySelectorAll('.product-item[data-categoria]');
  
    checkboxes.forEach(cb => {
      cb.addEventListener('change', () => {
        const categoriasSeleccionadas = Array.from(checkboxes)
          .filter(c => c.checked)
          .map(c => c.value);
  
        productos.forEach(prod => {
          // Buscar el contenedor padre que es la columna de la grilla
          const container = prod.closest('.col');
          const categoria = prod.getAttribute('data-categoria');
          if (categoriasSeleccionadas.length === 0 || categoriasSeleccionadas.includes(categoria)) {
            container.style.display = ''; // Se muestra la columna
          } else {
            container.style.display = 'none'; // Se oculta y no ocupa espacio
          }
        });
      });
    });
  });
  
  const checkboxes = document.querySelectorAll('.filtro-checkbox');

checkboxes.forEach(checkbox => {
  checkbox.addEventListener('change', () => {
    const label = checkbox.closest('label');
    if (checkbox.checked) {
      label.style.backgroundColor = 'pink';
    } else {
      label.style.backgroundColor = '';
    }
  });
});



function openModal(element) {
    const id = element.dataset.id;
    const nombre = element.dataset.nombre;
    const precio = element.dataset.precio;
    const imagen = element.dataset.imagen;
    const marca = element.dataset.marca || 'N/A';
    const descripcion = element.dataset.descripcion || 'N/A';
    const cantidadMayor = element.dataset.cantidadMayor || 'N/A';
    const precioMayor = element.dataset.precioMayor || 'N/A';
    const stockDisponible = element.dataset.stockDisponible || 'N/A';

    // Insertar datos en el modal
    document.getElementById('modal-title').textContent = nombre;
    document.getElementById('modal-precio').textContent = "$" + precio;
    document.getElementById('modal-imagen').src = imagen;
    document.getElementById('modal-marca').textContent = marca;
    document.getElementById('modal-descripcion').textContent = descripcion;
    document.getElementById('modal-cantidad-mayor').textContent = cantidadMayor;
    document.getElementById('modal-precio-mayor').textContent = "$" + precioMayor;
    document.getElementById('modal-stock-disponible').textContent = stockDisponible;

    // Rellenar formulario oculto
    document.getElementById('form-id').value = element.dataset.id;
    document.getElementById('form-nombre').value = nombre;
    document.getElementById('form-precio-detal').value = precio;
    document.getElementById('form-precio-mayor').value = precioMayor;
    document.getElementById('form-cantidad-mayor').value = cantidadMayor;
    document.getElementById('form-imagen').value = imagen;
    document.getElementById('form-stock-disponible').value = stockDisponible;
    
    const btnFavorito = document.querySelector('.btn-favorito');
    if (btnFavorito) {
      btnFavorito.dataset.id = id;
    }
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

document.addEventListener("DOMContentLoaded", () => {
    const formCarrito = document.getElementById("form-carrito");
    const btnAgregarCarrito = document.getElementById("btn-agregar-carrito");

    if (btnAgregarCarrito && formCarrito) {
        btnAgregarCarrito.addEventListener("click", (e) => {
            e.preventDefault();

            const formData = new FormData(formCarrito);

            const stockDisponible = parseInt(formData.get("stockDisponible"));
            const idProducto = formData.get("id");
            console.log(stockDisponible)
            console.log(idProducto)

            const itemExistente = document.querySelector(`li[data-id="${idProducto}"]`);
            let cantidadActual = 0;
            if (itemExistente) {
             const textoCantidad = itemExistente.querySelector('.cantidad-texto')?.textContent;
             const match = textoCantidad.match(/^(\d+)/);
              if (match) {
               cantidadActual = parseInt(match[1]);
             }
            }

      if(stockDisponible === 0){
                muestraMensaje('error', 1000, 'Sin stock', 'Este producto no está disponible actualmente.');
        return;
            }
   if (cantidadActual >= stockDisponible) {
             muestraMensaje('error', 1000, 'Stock limitado', 'Ya has agregado el máximo permitido.');
                  return;
            } 

      

            fetch("controlador/carrito.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    muestraMensaje('success', 1000, '¡Agregado!', 'El producto se agregó al carrito.');
                    const ulCarrito = document.querySelector('.carrito-dropdown');
                    if (!ulCarrito) {
                        console.error("No se encontró el <ul> del carrito en el HTML.");
                        return;
                    }
                      

                    const liVacio = ulCarrito.querySelector('li.text-center');
                    if (liVacio) {
                        liVacio.remove();
                    }

                    const id = data.producto.id;
                    let itemExistente = ulCarrito.querySelector(`li[data-id="${id}"]`);

                    if (!itemExistente) {
                       
                        const item = document.createElement('li');
                        item.className = 'list-group-item d-flex justify-content-between lh-sm';
                        item.setAttribute('data-id', id);

                        item.innerHTML = `
                            <div>
                                <h6 class="fs-5 fw-normal my-0">${data.producto.nombre}</h6>
                                <small class="text-muted cantidad-texto">${data.producto.cantidad} x $${data.producto.precio_unitario}</small>
                            </div>
                            <div class="text-end">
                                <span class="text-body-secondary subtotal-texto">$${data.producto.subtotal}</span><br>
                                <button class="btn-eliminar btn btn-sm btn-outline-danger mt-1" data-id="${id}">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                        `;

                        ulCarrito.insertBefore(item, ulCarrito.lastElementChild); 

                      
                        item.querySelector('.btn-eliminar').addEventListener('click', function (e) {
                            e.preventDefault();
                            eliminarProducto(id);
                        });

                        muestraMensaje('success',1000,'¡Agregado!', 'El producto se agregó al carrito.');

                        setTimeout(() => location.reload(), 500);
                    } else {
                      
                        itemExistente.querySelector('small.cantidad-texto').textContent = `${data.producto.cantidad} x $${data.producto.precio_unitario}`;
                        itemExistente.querySelector('span.subtotal-texto').textContent = `$${data.producto.subtotal}`;
                    }

                    // Actualizar contador
                    const contador = document.querySelector('.contador');
                    if (contador) {
                       
                        if (!itemExistente) {
                            contador.textContent = parseInt(contador.textContent) + 1;
                        }
                    }

                    const totalGeneral = document.getElementById('total-general');
                    if (totalGeneral) {
                        totalGeneral.textContent = data.total_general; 
                    }
                } else {
                    alert("Error: " + data.mensaje);
                }
            })
            .catch(error => console.error("Error al procesar la solicitud:", error));
        });
    }
});


document.addEventListener("DOMContentLoaded", () => {
  const formCarrito = document.querySelectorAll("form-carrito-exterior");
  const btnAgregarCarrito = document.querySelectorAll("btn-agregar-carrito-exterior");

  if (btnAgregarCarrito && formCarrito) {
      btnAgregarCarrito.addEventListener("click", (e) => {
          e.preventDefault();

          const formData = new FormData(formCarrito);

          const stockDisponible = parseInt(formData.get("stockDisponible"));
          const idProducto = formData.get("id");
          console.log(stockDisponible)
          console.log(idProducto)

          const itemExistente = document.querySelector(`li[data-id="${idProducto}"]`);
          let cantidadActual = 0;
          if (itemExistente) {
           const textoCantidad = itemExistente.querySelector('.cantidad-texto')?.textContent;
           const match = textoCantidad.match(/^(\d+)/);
            if (match) {
             cantidadActual = parseInt(match[1]);
           }
          }

    if(stockDisponible === 0){
              muestraMensaje('error', 1000, 'Sin stock', 'Este producto no está disponible actualmente.');
      return;
          }
 if (cantidadActual >= stockDisponible) {
           muestraMensaje('error', 1000, 'Stock limitado', 'Ya has agregado el máximo permitido.');
                return;
          } 

    

          fetch("controlador/carrito.php", {
              method: "POST",
              body: formData,
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  muestraMensaje('success', 1000, '¡Agregado!', 'El producto se agregó al carrito.');
                  const ulCarrito = document.querySelector('.carrito-dropdown');
                  if (!ulCarrito) {
                      console.error("No se encontró el <ul> del carrito en el HTML.");
                      return;
                  }
                    

                  const liVacio = ulCarrito.querySelector('li.text-center');
                  if (liVacio) {
                      liVacio.remove();
                  }

                  const id = data.producto.id;
                  let itemExistente = ulCarrito.querySelector(`li[data-id="${id}"]`);

                  if (!itemExistente) {
                     
                      const item = document.createElement('li');
                      item.className = 'list-group-item d-flex justify-content-between lh-sm';
                      item.setAttribute('data-id', id);

                      item.innerHTML = `
                          <div>
                              <h6 class="fs-5 fw-normal my-0">${data.producto.nombre}</h6>
                              <small class="text-muted cantidad-texto">${data.producto.cantidad} x $${data.producto.precio_unitario}</small>
                          </div>
                          <div class="text-end">
                              <span class="text-body-secondary subtotal-texto">$${data.producto.subtotal}</span><br>
                              <button class="btn-eliminar btn btn-sm btn-outline-danger mt-1" data-id="${id}">
                                  <i class="fa-solid fa-x"></i>
                              </button>
                          </div>
                      `;

                      ulCarrito.insertBefore(item, ulCarrito.lastElementChild); 

                    
                      item.querySelector('.btn-eliminar').addEventListener('click', function (e) {
                          e.preventDefault();
                          eliminarProducto(id);
                      });

                      muestraMensaje('success',1000,'¡Agregado!', 'El producto se agregó al carrito.');

                      setTimeout(() => location.reload(), 500);
                  } else {
                    
                      itemExistente.querySelector('small.cantidad-texto').textContent = `${data.producto.cantidad} x $${data.producto.precio_unitario}`;
                      itemExistente.querySelector('span.subtotal-texto').textContent = `$${data.producto.subtotal}`;
                  }

                  // Actualizar contador
                  const contador = document.querySelector('.contador');
                  if (contador) {
                     
                      if (!itemExistente) {
                          contador.textContent = parseInt(contador.textContent) + 1;
                      }
                  }

                  const totalGeneral = document.getElementById('total-general');
                  if (totalGeneral) {
                      totalGeneral.textContent = data.total_general; 
                  }
              } else {
                  alert("Error: " + data.mensaje);
              }
          })
          .catch(error => console.error("Error al procesar la solicitud:", error));
      });
  }
});


document.addEventListener("DOMContentLoaded", () => {
    const btnCart = document.querySelectorAll("button[href='?pagina=login']");

    btnCart.forEach((btnCart) =>{
        btnCart.addEventListener("click", (event) => {
            event.preventDefault();
            
            Swal.fire({
                title: "Registro requerido",
                text: "Necesitas registrarte para realizar esta accion. ¿Deseas continuar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?pagina=login"; 
                }
            });
        });
    }
)});

$('#btnAyuda').on("click", function () {
    const currentURL = window.location.href;
    const driver = window.driver.js.driver;

    let steps = [
        { element: '#search-form', popover: { title: 'Buscador', description: 'Aquí puedes buscar cualquier producto de nuestro catálogo', side: "left" }},
        { element: '[aria-controls="offcanvasCart"]', popover: { title: 'Carrito de compras', description: 'Haz clic aquí para ver los productos que has agregado al carrito.', side: "left", align: 'start' }},
        { element: '[data-bs-target="#cerrar"]', popover: { title: 'Cerrar sesión', description: 'Este botón te permite cerrar sesión en tu cuenta.', side: "left", align: 'start' }},
        { element: '.section-title', popover: { title:'Productos más vendidos', description: 'Un listado de nuestros 10 productos más vendidos.', side: "top", align: 'start' }},
        { element: '.product-item', popover: { title: 'Productos', description: 'Estas son las cartas de nuestros productos. Puedes dar clic en la imagen para ver más detalles del producto.', side: "left", align: 'start' }},
        { element: '.categorias', popover: { title: 'Filtrado por categoría', description: 'Aquí podrás seleccionar las categorías y te saldrán los productos asociados', side: "left", align: 'start' }},
        { element: '#Botonlado', popover: { title: 'Ver todos los productos', description: 'Aquí puedes ver el listado de todos los productos', side: "left", align: 'start' }},
        { popover: { title: 'Eso es todo', description: 'Este es el fin de la guía, espero que hayas entendido' }}
    ];

    // Si la URL contiene "catalogo_producto", modificar ciertos pasos
    if (currentURL.includes("catalogo_producto")) {
        steps = steps.map(step => {
            if (step.element === '.section-title') {
                step.popover.title = 'Lista de productos';
                step.popover.description = 'Nuestra selección completa de productos';
            }
            return step;
        });
        steps = steps.map(step => {
            if (step.element === '#Botonlado') {
                step.popover.title = 'Ir hacia el carrito';
                step.popover.description = 'Este botón te llevará a tu carrito de compras.';
            }
            return step;
        });
    }

    // Si la URL contiene "ver_carrito", mostrar solo los primeros 3 pasos y agregar uno con ".table-light"
    if (currentURL.includes("vercarrito")) {
        steps = [
            { element: '.table-light', popover: { title: 'Lista del carrito', description: 'Aquí puedes ver los productos que has añadido al carrito.', side: "left", align: 'start' }},
            { element: '.Enlacecompra', popover: { title: 'Datos del pago', description: 'Aquí colocaras los datos del pago movil realizado y despues esperaras a la confirmacion', side: "left", align: 'start' }},
        ];
    }
    if (currentURL.includes("verpedidoweb")) {
        steps = [
            { element: '#formPedido', popover: { title: 'Datos del pago', description: 'Aquí colocaras los datos del pago movil realizado y despues esperaras a la confirmacion', side: "left", align: 'start' }},
            { element: '.Enlacecarrito', popover: { title: 'Lista del carrito', description: 'Aquí puedes ver los productos que has añadido al carrito.', side: "top", align: 'start' }},
            { element: '.header2', popover: { title: 'Tu resumen de pedido', description: 'Aqui ves el resumen de compra de los productos con su total y detalles', side: "top", align: 'start' }},
            { element: '.btn-rp', popover: { title: 'Listo para comprar', description: 'Una vez completado el rellenado', side: "left", align: 'start' }}
        ];
    }

    const driverObj = new driver({
        nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior',
        doneBtnText: 'Listo',
        popoverClass: 'driverjs-theme',
        modal: true,
        closeBtn: false,
        steps: steps
    });

    // Iniciar el tour con los pasos actualizados
    driverObj.drive();
});

document.addEventListener('DOMContentLoaded', () => {
  document.body.addEventListener('click', e => {
    const btn = e.target.closest('.btn-favorito');
    if (!btn) return;

    const idProducto = btn.dataset.id;
    if (!idProducto) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'ID de producto no válido',
        timer: 1500,
        showConfirmButton: false,
        willClose: () => location.reload()
      });
      return;
    }

    fetch('?pagina=listadeseo', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'accion=agregar&id_producto=' + encodeURIComponent(idProducto)
    })
    .then(res => res.json())
    .then(data => {
      let icon = 'error';
      let title = 'Error';
      let text = data.message || 'No se pudo agregar.';

      if (data.status === 'success') {
        icon = 'success';
        title = '¡Agregado!';
        text = 'Producto añadido a tu lista de deseos.';
      } else if (data.status === 'exists') {
        icon = 'info';
        title = 'Aviso';
        text = 'Este producto ya está en tu lista.';
      }

      Swal.fire({
        icon,
        title,
        text,
        timer: 1500,
        showConfirmButton: false,
        willClose: () => location.reload()
      });
    })
    .catch(() => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error al procesar la solicitud.',
        timer: 1500,
        showConfirmButton: false,
        willClose: () => location.reload()
      });
    });
  });
});

  