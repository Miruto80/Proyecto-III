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
  


function openModal(element) {
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

$('#btnExtra').on("click", function () {
  
    const driver = window.driver.js.driver;
    
    const driverObj = new driver({
        nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior',
      popoverClass: 'driverjs-theme',
      closeBtn:false,
      steps: [
        { element: '#search-form', popover: { title: 'Buscador', description: 'Aqui puedes buscar cualquier producto de nuestro catalogo', side: "left", }},
        { element: '.section-title', popover: { title: 'Productos mas vendidos', description: 'Nuestra seleccion de los 10 productos mas vendidos', side: "bottom", align: 'start' }},
        { element: '.product-item', popover: { title: 'Productos', description: 'Estas son las cartas de nuestros productos puedes darle click para ver mas detalles del producto', side: "left", align: 'start' }},
        { element: '.categorias', popover: { title: 'Filtrado por categoria', description: 'Aqui podras seleccionar las categorias y te saldran los productos asociados', side: "left", align: 'start' }},
        { element: '.ver-detalles', popover: { title: 'Ver detalles', description: 'Haz clic aquí para ver más información sobre un producto específico.', side: "left", align: 'start' }},
        { element: '.btn-desactivar', popover: { title: 'Cambiar estatus', description: 'Este botón te permite desactivar o activar un producto', side: "left", align: 'start' }},
        { element: '.dt-search', popover: { title: 'Buscar', description: 'Te permite buscar un producto en la tabla', side: "right", align: 'start' }},
        { popover: { title: 'Eso es todo', description: 'Este es el fin de la guia espero hayas entendido'} }
      ]
    });
    
    // Iniciar el tour
    driverObj.drive();
  });
