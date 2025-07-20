// Función para mostrar mensajes con SweetAlert
function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

// Mostrar mensaje debajo del campo
function mostrarError(campo, mensaje) {
    campo.classList.add("is-invalid");
    let span = campo.nextElementSibling;
    if (!span || !span.classList.contains('invalid-feedback')) {
        span = document.createElement("span");
        span.classList.add("invalid-feedback");
        span.style.color = "red";
        campo.parentNode.insertBefore(span, campo.nextSibling);
    }
    span.innerText = mensaje;
}

function limpiarError(campo) {
    campo.classList.remove("is-invalid");
    const span = campo.nextElementSibling;
    if (span && span.classList.contains("invalid-feedback")) {
        span.innerText = "";
    }
}

// Validaciones individuales
function validarReferenciaBancaria(input) {
    const valor = input.value.trim();
    const valido = /^[0-9]{4,6}$/.test(valor);
    if (!valido) mostrarError(input, "Debe tener entre 4 y 6 dígitos.");
    else limpiarError(input);
    return valido;
}

function validarTelefonoEmisor(input) {
    const valor = input.value.trim();
    const valido = /^(0414|0424|0412|0416|0426)[0-9]{7}$/.test(valor);
    if (!valido) mostrarError(input, "Formato válido: 04141234567");
    else limpiarError(input);
    return valido;
}

function validarSelect(select, mensaje) {
    const valido = select.value !== "" && !select.value.includes("Seleccione");
    if (!valido) mostrarError(select, mensaje);
    else limpiarError(select);
    return valido;
}

// Validación general del formulario de reserva
function validarFormularioReserva() {
    const ref = document.getElementById("referencia_bancaria");
    const tel = document.getElementById("telefono_emisor");
    const pago = document.getElementById("metodopago");
    const banco = document.getElementById("banco");
    const bancoDestino = document.getElementById("banco_destino");

    const validaciones = [
        validarReferenciaBancaria(ref),
        validarTelefonoEmisor(tel),
        validarSelect(pago, "Seleccione un método de pago válido."),
        validarSelect(banco, "Seleccione un banco de origen."),
        validarSelect(bancoDestino, "Seleccione un banco de destino.")
    ];

    return validaciones.every(v => v);
}

// Evento principal
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('formReserva');
    const ref = document.getElementById("referencia_bancaria");
    const tel = document.getElementById("telefono_emisor");

    // Solo números y máximo 6 dígitos para referencia bancaria
    ref.addEventListener("input", (e) => {
        let valor = e.target.value.replace(/[^\d]/g, '');
        if (valor.length > 6) valor = valor.slice(0, 6);
        e.target.value = valor;
    });

    // Solo números y máximo 11 dígitos para teléfono
    tel.addEventListener("input", (e) => {
        let valor = e.target.value.replace(/[^\d]/g, '');
        if (valor.length > 11) valor = valor.slice(0, 11);
        e.target.value = valor;
    });

    // Botón guardar reserva
    document.getElementById("btn-guardar-reserva").addEventListener("click", async (e) => {
        e.preventDefault();
        if (!validarFormularioReserva()) return;

        const formData = new FormData(form);

        try {
            const res = await fetch("controlador/reserva_cliente.php", {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const rawText = await res.text(); // Primero texto sin procesar

            let data;
            try {
                data = JSON.parse(rawText);
            } catch (jsonError) {
                console.error("⚠️ Respuesta inesperada del servidor (no es JSON):", rawText);

                if (rawText.includes("<!DOCTYPE html>") || rawText.includes("<html")) {
                    throw new Error("Sesión expirada o error inesperado del servidor.");
                } else {
                    throw new Error("Respuesta del servidor no válida.");
                }
            }

            if (!res.ok || !data.success) {
                throw new Error(data.message || "Error al registrar la reserva.");
            } else {
                Swal.fire({
                    icon: "success",
                    title: "Su reserva fue realizada",
                    html: "Recuerde que debe retirarlo en el local.",
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
                setTimeout(() => window.location.href = "?pagina=catalogo", 1500);
                return;
            }

        } catch (error) {
            muestraMensaje("error", 3000, "Error", error.message || "Ocurrió un error inesperado.");
        }
    });

    // Validación en tiempo real
    ref.addEventListener("input", () => validarReferenciaBancaria(ref));
    tel.addEventListener("input", () => validarTelefonoEmisor(tel));
    document.querySelectorAll('select').forEach(sel =>
        sel.addEventListener("change", () => validarFormularioReserva())
    );
});
