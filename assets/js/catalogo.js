
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


