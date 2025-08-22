$(document).ready(function() {
    // Inicializar presets de fechas
    inicializarPresetsFechas();
    
    // Inicializar filtros dependientes
    inicializarFiltrosDependientes();
    
    // Validaciones en tiempo real
    inicializarValidaciones();
    
    // Restaurar funcionalidad original de validación y conteo
    inicializarValidacionOriginal();
    
    // Inicializar tooltips de ayuda
    inicializarTooltips();
    
    // Mejorar funcionalidad de modales
    mejorarModales();
});

// ===========================================
// PRESETS DE FECHAS
// ===========================================
function inicializarPresetsFechas() {
    $('.preset-btn').on('click', function() {
        const preset = $(this).data('preset');
        const modal = $(this).closest('.modal');
        
        // Remover clase active de todos los botones
        modal.find('.preset-btn').removeClass('active');
        // Agregar clase active al botón clickeado
        $(this).addClass('active');
        
        const hoy = new Date();
        let fechaInicio, fechaFin;
        
        switch(preset) {
            case 'hoy':
                fechaInicio = fechaFin = formatearFecha(hoy);
                break;
            case 'ayer':
                const ayer = new Date(hoy);
                ayer.setDate(hoy.getDate() - 1);
                fechaInicio = fechaFin = formatearFecha(ayer);
                break;
            case 'semana':
                const inicioSemana = new Date(hoy);
                inicioSemana.setDate(hoy.getDate() - hoy.getDay());
                fechaInicio = formatearFecha(inicioSemana);
                fechaFin = formatearFecha(hoy);
                break;
            case 'mes':
                const inicioMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                fechaInicio = formatearFecha(inicioMes);
                fechaFin = formatearFecha(hoy);
                break;
            case 'personalizado':
                modal.find('input[name="f_start"]').val('').focus();
                modal.find('input[name="f_end"]').val('');
                return;
        }
        
        modal.find('input[name="f_start"]').val(fechaInicio);
        modal.find('input[name="f_end"]').val(fechaFin);
    });
}

function formatearFecha(fecha) {
    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, '0');
    const day = String(fecha.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// ===========================================
// FILTROS AVANZADOS
// ===========================================
function toggleFiltrosAvanzados(tipo) {
    const filtrosDiv = $(`#filtrosAvanzados${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);
    const boton = $(`button[onclick="toggleFiltrosAvanzados('${tipo}')"]`);
    
    if (filtrosDiv.is(':visible')) {
        filtrosDiv.slideUp(300);
        boton.html('<i class="fas fa-filter"></i>Filtros Avanzados');
    } else {
        filtrosDiv.slideDown(300);
        boton.html('<i class="fas fa-chevron-up"></i>Ocultar Filtros');
    }
}

// ===========================================
// FILTROS DEPENDIENTES
// ===========================================
function inicializarFiltrosDependientes() {
    // Filtro categoría → productos (en reporte de productos)
    $('#categoriaProducto').on('change', function() {
        const categoriaId = $(this).val();
        const productoSelect = $('#productoFiltrado');
        
        if (categoriaId) {
            // Ocultar productos que no pertenecen a la categoría seleccionada
            productoSelect.find('option').each(function() {
                const producto = $(this);
                const productoCategoria = producto.data('categoria');
                
                if (productoCategoria == categoriaId || producto.val() === '') {
                    producto.show();
                } else {
                    producto.hide();
                }
            });
            
            // Resetear selección de producto
            productoSelect.val('');
        } else {
            // Mostrar todos los productos
            productoSelect.find('option').show();
            productoSelect.val('');
        }
    });
}

// ===========================================
// VALIDACIONES EN TIEMPO REAL
// ===========================================
function inicializarValidaciones() {
    // Validar fechas
    $('input[type="date"]').on('change', function() {
        const fecha = $(this).val();
        const maxFecha = $(this).attr('max');
        
        if (fecha > maxFecha) {
            $(this).val(maxFecha);
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'La fecha no puede ser mayor a hoy',
                confirmButtonText: 'Aceptar'
            });
        }
    });
    
    // Validar rangos de montos
    $('input[name="monto_min"], input[name="monto_max"], input[name="precio_min"], input[name="precio_max"]').on('input', function() {
        const valor = parseFloat($(this).val());
        if (valor < 0) {
            $(this).val(0);
        }
    });
    
    // Validar stock mínimo
    $('input[name="stock_min"], input[name="stock_max"]').on('input', function() {
        const valor = parseInt($(this).val());
        if (valor < 0) {
            $(this).val(0);
        }
    });
}

// ===========================================
// VALIDACIÓN ORIGINAL RESTAURADA
// ===========================================
function inicializarValidacionOriginal() {
    let isSubmitting = false;

    // Limpiar filtros al cerrar cualquier modal
    $('.modal').on('hidden.bs.modal', function() {
        if (!isSubmitting) {
            const form = $(this).find('form.report-form');
            if (form.length) form[0].reset();
        }
        isSubmitting = false;
    });

    // Validación + AJAX conteo (funcionalidad original)
    const countMap = {
        compra: 'countCompra',
        producto: 'countProducto',
        venta: 'countVenta',
        pedidoWeb: 'countPedidoWeb'
    };

    $('.report-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const data = new FormData(form[0]);
        const start = data.get('f_start') || '';
        const end = data.get('f_end') || '';
        
        // Validar fechas
        if (start && end && start > end) {
            Swal.fire({
                icon: 'error',
                title: 'Rango inválido',
                text: 'La fecha de inicio no puede ser mayor que la fecha de fin.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Validar rangos de montos
        const montoMin = parseFloat(data.get('monto_min')) || 0;
        const montoMax = parseFloat(data.get('monto_max')) || 0;
        if (montoMin > 0 && montoMax > 0 && montoMin > montoMax) {
            Swal.fire({
                icon: 'error',
                title: 'Rango inválido',
                text: 'El monto mínimo no puede ser mayor al monto máximo.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Validar rangos de precios
        const precioMin = parseFloat(data.get('precio_min')) || 0;
        const precioMax = parseFloat(data.get('precio_max')) || 0;
        if (precioMin > 0 && precioMax > 0 && precioMin > precioMax) {
            Swal.fire({
                icon: 'error',
                title: 'Rango inválido',
                text: 'El precio mínimo no puede ser mayor al precio máximo.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Validar rangos de stock
        const stockMin = parseInt(data.get('stock_min')) || 0;
        const stockMax = parseInt(data.get('stock_max')) || 0;
        if (stockMin > 0 && stockMax > 0 && stockMin > stockMax) {
            Swal.fire({
                icon: 'error',
                title: 'Rango inválido',
                text: 'El stock mínimo no puede ser mayor al stock máximo.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Determinar acción de conteo
        const action = new URL(form.attr('action'), location.origin).searchParams.get('accion');
        const countAction = countMap[action];
        
        if (!countAction) {
            console.error('Acción inválida en countMap:', action);
            return;
        }

        // Armar parámetros para el conteo
        const params = new URLSearchParams();
        for (let [k, v] of data.entries()) {
            if (['f_start', 'f_end', 'f_id', 'f_prov', 'f_cat', 'monto_min', 'monto_max', 'precio_min', 'precio_max', 'stock_min', 'stock_max', 'f_mp', 'metodo_pago', 'estado'].includes(k) && v) {
                params.append(k, v);
            }
        }

        // Indicar que empieza el envío
        isSubmitting = true;

        // Cerrar modal
        const modal = form.closest('.modal');
        if (modal.length) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal[0]);
            bootstrapModal?.hide();
        }

        // AJAX GET para verificar datos
        fetch(`?pagina=reporte&accion=${countAction}&${params}`)
            .then(r => r.json())
            .then(json => {
                if (json.count > 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Generando reporte',
                        text: `Se encontraron ${json.count} registros. El PDF se generará en una nueva ventana.`,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        form[0].submit();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sin datos',
                        text: 'No hay registros para generar el PDF.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo verificar los datos.',
                    confirmButtonText: 'Aceptar'
                });
            });
    });
}

// ===========================================
// LIMPIAR FORMULARIOS
// ===========================================
function limpiarFormulario(modalId) {
    const modal = $(`#${modalId}`);
    const form = modal.find('form');
    
    // Limpiar campos
    form[0].reset();
    
    // Remover clases active de presets
    modal.find('.preset-btn').removeClass('active');
    
    // Ocultar filtros avanzados
    modal.find('.filtros-avanzados').slideUp(300);
    modal.find('.toggle-filtros').html('<i class="fas fa-filter"></i>Filtros Avanzados');
    
    // Mostrar todos los productos (si aplica)
    modal.find('#productoFiltrado option').show();
}

// ===========================================
// AYUDAS CONTEXTUALES
// ===========================================
$('#btnAyuda').on('click', function() {
    const DriverClass = window.driver?.js?.driver;
    if (typeof DriverClass !== 'function') {
        return console.error('Driver.js no detectado');
    }

    const steps = [
        {
            element: '#cardCompra',
            popover: {
                title: 'Reporte de Compras',
                description: 'Genera reportes detallados de todas las compras realizadas a proveedores, incluyendo productos adquiridos, costos y fechas.',
                side: 'bottom'
            }
        },
        {
            element: '#cardProducto',
            popover: {
                title: 'Reporte de Productos',
                description: 'Muestra el inventario completo con detalles de productos, precios, stock disponible y categorías.',
                side: 'bottom'
            }
        },
        {
            element: '#cardVentas',
            popover: {
                title: 'Reporte de Ventas',
                description: 'Presenta las ventas realizadas en tienda física, incluyendo métodos de pago y montos totales.',
                side: 'bottom'
            }
        },
        {
            element: '#cardPedidoWeb',
            popover: {
                title: 'Reporte de Pedidos Web',
                description: 'Detalla los pedidos realizados a través de la plataforma online, estados de envío y pagos web.',
                side: 'bottom'
            }
        },
        {
            popover: {
                title: '¡Listo!',
                description: 'Ahora conoces qué tipo de reporte genera cada tarjeta.'
            }
        }
    ];

    const driver = new DriverClass({
        nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior',
        doneBtnText: 'Listo',
        closeBtn: false,
        popoverClass: 'driverjs-theme',
        steps
    });

    driver.drive();
});

// ===========================================
// CONTADORES EN TIEMPO REAL
// ===========================================
function actualizarContador(tipo, parametros) {
    $.get(`?pagina=reporte&accion=count${tipo}`, parametros)
        .done(function(data) {
            const count = data.count || 0;
            $(`#card${tipo} .card-text`).text(`${count} registros encontrados`);
        })
        .fail(function() {
            $(`#card${tipo} .card-text`).text('-');
        });
}

// Actualizar contadores cuando cambian los filtros
$('.report-form select, .report-form input').on('change', function() {
    const form = $(this).closest('form');
    const modal = form.closest('.modal');
    const modalId = modal.attr('id');
    
    // Determinar tipo de reporte
    let tipo = '';
    switch(modalId) {
        case 'modalCompra':
            tipo = 'Compra';
            break;
        case 'modalProducto':
            tipo = 'Producto';
            break;
        case 'modalVenta':
            tipo = 'Venta';
            break;
        case 'modalPedidoWeb':
            tipo = 'PedidoWeb';
            break;
    }
    
    if (tipo) {
        const formData = new FormData(form[0]);
        const parametros = {};
        for (let [key, value] of formData.entries()) {
            if (value) parametros[key] = value;
        }
        
        // Actualizar contador
        actualizarContador(tipo, parametros);
    }
});

// ===========================================
// TOOLTIPS DE AYUDA
// ===========================================
function inicializarTooltips() {
    // Inicializar todos los tooltips de Bootstrap
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, {
        html: true,
        placement: 'auto',
        delay: { show: 500, hide: 200 },
        container: 'body',
        trigger: 'hover focus',
        customClass: 'custom-tooltip'
    }));
    
    // Asegurar que los tooltips se reinicialicen cuando se abra un modal
    $('.modal').on('shown.bs.modal', function() {
        $(this).find('[data-bs-toggle="tooltip"]').each(function() {
            if (!$(this).hasClass('tooltip-initialized')) {
                new bootstrap.Tooltip(this, {
                    html: true,
                    placement: 'auto',
                    delay: { show: 500, hide: 200 },
                    container: 'body',
                    trigger: 'hover focus',
                    customClass: 'custom-tooltip'
                });
                $(this).addClass('tooltip-initialized');
            }
        });
    });
    
    // Destruir tooltips cuando se cierre el modal para evitar problemas
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('[data-bs-toggle="tooltip"]').each(function() {
            const tooltip = bootstrap.Tooltip.getInstance(this);
            if (tooltip) {
                tooltip.dispose();
            }
            $(this).removeClass('tooltip-initialized');
        });
        
        // Limpiar cualquier tooltip residual
        $('.tooltip').remove();
    });
}

// ===========================================
// MEJORAS DE MODALES
// ===========================================
function mejorarModales() {
    // Mejorar el comportamiento del botón de cerrar
    $('.modal').on('click', '.btn-close', function() {
        const modal = $(this).closest('.modal');
        const bootstrapModal = bootstrap.Modal.getInstance(modal[0]);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    });
    
    // Cerrar modal con tecla Escape
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            const modalAbierto = $('.modal.show');
            if (modalAbierto.length) {
                const bootstrapModal = bootstrap.Modal.getInstance(modalAbierto[0]);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }
            }
        }
    });
    
    // Mejorar accesibilidad
    $('.modal').attr('role', 'dialog');
    $('.modal').attr('aria-modal', 'true');
    
    // Focus management
    $('.modal').on('shown.bs.modal', function() {
        $(this).attr('tabindex', -1).focus();
    });
}

// ===========================================
// AYUDA RÁPIDA
// ===========================================
function mostrarAyudaRapida() {
    Swal.fire({
        icon: 'info',
        title: '💡 Ayuda Rápida - Filtros de Reportes',
        html: `
            <div style="text-align: left; font-size: 14px;">
                <p><strong>📅 Filtros de Fechas (MUY IMPORTANTE):</strong></p>
                <ul style="margin-left: 20px;">
                    <li><strong>Sin fechas:</strong> Reporte COMPLETO de todos los registros</li>
                    <li><strong>Solo fecha inicio:</strong> Desde esa fecha hasta hoy</li>
                    <li><strong>Solo fecha fin:</strong> Desde el primer registro hasta esa fecha</li>
                    <li><strong>Ambas fechas:</strong> Rango específico entre las fechas</li>
                    <li><strong>Botones rápidos:</strong> Hoy, Ayer, Esta Semana, Este Mes</li>
                    <li><strong>Personalizado:</strong> Limpia las fechas para elegir manualmente</li>
                </ul>
                
                <p><strong>⚙️ Filtros Avanzados:</strong></p>
                <ul style="margin-left: 20px;">
                    <li>Haz clic en "Filtros Avanzados" para ver más opciones</li>
                    <li>Cada filtro tiene un botón con ayuda específica</li>
                    <li>Puedes combinar múltiples filtros para mayor precisión</li>
                    <li>Campos vacíos = incluir todos los elementos</li>
                </ul>
                
                <p><strong>💡 Consejos Prácticos:</strong></p>
                <ul style="margin-left: 20px;">
                    <li>Para reporte general: No selecciones fechas</li>
                    <li>Para un día específico: Usa los botones rápidos</li>
                    <li>💰 Los rangos de montos son opcionales pero útiles</li>
                    <li>✅ El sistema validará antes de generar el PDF</li>
                </ul>
            </div>
        `,
        width: '550px',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#f6c5b4'
    });
}

$('#btnAyudaRapida').on('click', function() {
    mostrarAyudaRapida();
});
