document.addEventListener("DOMContentLoaded", () => {

    // Funciones de validación
    function mostrarError(campo, mensaje) {
        campo.classList.add("is-invalid");
        let span = campo.nextElementSibling;
        if (!span || !span.classList.contains('invalid-feedback')) {
            span = document.createElement("span");
            span.classList.add("invalid-feedback");
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

    function validarReferenciaBancaria(input) {
        const valor = input.value.trim();
        const valido = /^[0-9]{4,6}$/.test(valor);
        valido ? limpiarError(input) : mostrarError(input, "Debe tener entre 4 y 6 dígitos.");
        return valido;
    }

    function validarTelefonoEmisor(input) {
        const valor = input.value.trim();
        const valido = /^(0414|0424|0412|0416|0426)[0-9]{7}$/.test(valor);
        valido ? limpiarError(input) : mostrarError(input, "Formato válido: 04141234567");
        return valido;
    }

    function validarSelect(select, mensaje) {
        const valido = select.value !== "" && !select.value.includes("Seleccione");
        valido ? limpiarError(select) : mostrarError(select, mensaje);
        return valido;
    }

    function validarCheckbox(checkbox, mensaje) {
        if (!checkbox.checked) {
            mostrarError(checkbox, mensaje);
            return false;
        } else {
            limpiarError(checkbox);
            return true;
        }
    }

    function validarFormulario() {
        const ref = document.getElementById("referencia_bancaria");
        const tel = document.getElementById("telefono_emisor");
        const pago = document.getElementById("metodopago");
        const banco = document.getElementById("banco");
        const bancoDestino = document.getElementById("banco_destino");
        const checkTerminos = document.getElementById("check_terminos");

        return [
            validarReferenciaBancaria(ref),
            validarTelefonoEmisor(tel),
            validarSelect(pago, "Seleccione un método de pago válido."),
            validarSelect(banco, "Seleccione un banco de origen."),
            validarSelect(bancoDestino, "Seleccione un banco de destino."),
            validarCheckbox(checkTerminos, "Debe aceptar los términos y condiciones.")
        ].every(v => v);
    }

    // Limitar inputs
    const ref = document.getElementById("referencia_bancaria");
    const tel = document.getElementById("telefono_emisor");

    ref.addEventListener("input", e => {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6);
        validarReferenciaBancaria(ref);
    });

    tel.addEventListener("input", e => {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 11);
        validarTelefonoEmisor(tel);
    });

    // Activar botón solo si se aceptan términos
    const checkTerminos = document.getElementById("check_terminos");
    const btn = document.getElementById("btn-guardar-reserva");
    checkTerminos.addEventListener("change", () => btn.disabled = !checkTerminos.checked);

    // Botón guardar reserva
    btn.addEventListener("click", async e => {
        e.preventDefault();

        if (!validarFormulario()) return Swal.fire('Error', 'Complete correctamente el formulario', 'warning');

        Swal.fire({
            title: '¿Confirmar Pago?',
            text: 'Se procesará su orden y pago.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, pagar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (!result.isConfirmed) return;

            const form = document.getElementById('formReserva');
            const fd = new FormData(form);

            try {
                const res = await fetch("controlador/reserva_cliente.php", {
                    method: "POST",
                    body: fd
                });

                const data = await res.json();

                if (!data.success) {
                    Swal.fire('Error', data.message || 'Error al procesar la reserva', 'error');
                } else {
                    Swal.fire({
                        title: '¡Listo!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        if (data.redirect) window.location.href = data.redirect;
                    });
                }
            } catch (error) {
                Swal.fire('Error', 'Comunicación fallida o sesión expirada', 'error');
                console.error(error);
            }
        });
    });
});
