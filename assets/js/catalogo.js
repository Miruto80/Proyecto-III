
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

    // Insertar datos en el modal
    document.getElementById('modal-title').textContent = nombre;
    document.getElementById('modal-precio').textContent = `$${precio}`;
    document.getElementById('modal-imagen').src = imagen;

    // No necesitas bootstrap.Modal, ya lo abre Bootstrap con data-bs-toggle
}




