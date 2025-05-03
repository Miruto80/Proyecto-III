
document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll('.filtro-checkbox');
    const productos = document.querySelectorAll('.producto');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const categoriasSeleccionadas = Array.from(checkboxes)
                .filter(c => c.checked)
                .map(c => c.value);

            productos.forEach(prod => {
                const categoria = prod.getAttribute('data-categoria');
                if (categoriasSeleccionadas.length === 0 || categoriasSeleccionadas.includes(categoria)) {
                    prod.style.display = '';
                } else {
                    prod.style.display = 'none';
                }
            });
        });
    });
});


function openModal(element) {
    const nombre = element.getAttribute('data-nombre');
    const precio = element.getAttribute('data-precio');
    const imagen = element.getAttribute('data-imagen');
    const marca = element.getAttribute('data-marca');
    const descripcion = element.getAttribute('data-descripcion');
    const cantidadMayor = element.getAttribute('data-cantidad-mayor');
    const precioMayor = element.getAttribute('data-precio-mayor');
    const stockDisponible = element.getAttribute('data-stock-disponible');

    // Insertar datos en el modal
    document.getElementById('modal-title').textContent = nombre;
    document.getElementById('modal-precio').textContent = `$${precio}`;
    document.getElementById('modal-imagen').src = imagen;
    document.getElementById('modal-marca').textContent = marca || 'N/A';
    document.getElementById('modal-descripcion').textContent = descripcion || 'N/A';
    document.getElementById('modal-cantidad-mayor').textContent = cantidadMayor || 'N/A';
    document.getElementById('modal-precio-mayor').textContent = `$${precioMayor}` || 'N/A';
    document.getElementById('modal-stock-disponible').textContent = stockDisponible || 'N/A';
}





