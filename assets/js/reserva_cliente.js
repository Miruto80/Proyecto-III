document.addEventListener("DOMContentLoaded", () => {

    // -------------------------
    // Funciones de validación
    // -------------------------
    function mostrarError(campo, mensaje) {
        if (!campo) return;
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
        if (!campo) return;
        campo.classList.remove("is-invalid");
        const span = campo.nextElementSibling;
        if (span && span.classList.contains("invalid-feedback")) {
            span.innerText = "";
        }
    }

    function validarReferenciaBancaria(input) {
        if (!input) return true;
        const valor = input.value.trim();
        const valido = /^[0-9]{4,6}$/.test(valor);
        valido ? limpiarError(input) : mostrarError(input, "Debe tener entre 4 y 6 dígitos.");
        return valido;
    }

    function validarTelefonoEmisor(input) {
        if (!input) return true;
        const valor = input.value.trim();
        const valido = /^(0414|0424|0412|0416|0426)[0-9]{7}$/.test(valor);
        valido ? limpiarError(input) : mostrarError(input, "Formato válido: 04141234567");
        return valido;
    }

    function validarSelect(select, mensaje) {
        if (!select) return true;
        const valido = select.value !== "" && !select.value.includes("Seleccione");
        valido ? limpiarError(select) : mostrarError(select, mensaje);
        return valido;
    }

    function validarCheckbox(checkbox, mensaje) {
        if (!checkbox) return false;
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
        const imagen = document.getElementById("imagen");

        return [
            validarReferenciaBancaria(ref),
            validarTelefonoEmisor(tel),
            validarSelect(pago, "Seleccione un método de pago válido."),
            validarSelect(banco, "Seleccione un banco de origen."),
            validarSelect(bancoDestino, "Seleccione un banco de destino."),
            validarCheckbox(checkTerminos, "Debe aceptar los términos y condiciones."),
            validarCheckbox(imagen, "Debe adjuntar un comprobante")
        ].every(v => v);
    }

    // -------------------------
    // Inputs y límites
    // -------------------------
    const ref = document.getElementById("referencia_bancaria");
    const tel = document.getElementById("telefono_emisor");

    if (ref) {
        ref.addEventListener("input", e => {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6);
            validarReferenciaBancaria(ref);
        });
    }

    if (tel) {
        tel.addEventListener("input", e => {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 11);
            validarTelefonoEmisor(tel);
        });
    }

    // -------------------------
    // Preview de imagen
    // -------------------------
    const imagen = document.getElementById("imagen");
    if (imagen) {
        imagen.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowed.includes(file.type)) {
                alert('Formato no permitido. Solo JPG o PNG.');
                e.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = ev => {
                const preview = document.getElementById('preview');
                if (preview) {
                    preview.src = ev.target.result;
                    preview.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(file);
        });
    }

    // -------------------------
    // Botón de enviar reserva
    // -------------------------
    const form = document.getElementById("formReserva");
    const btn = document.getElementById("btn-guardar-reserva");
    const checkTerminos = document.getElementById("check_terminos");

    if (form && btn && checkTerminos) {
        btn.disabled = !checkTerminos.checked;

        // Habilitar/deshabilitar botón
        checkTerminos.addEventListener("change", () => {
            btn.disabled = !checkTerminos.checked;
        });

        // Click enviar
        btn.addEventListener("click", async (e) => {
            e.preventDefault();

            if (!validarFormulario()) return Swal.fire("Error", "Complete correctamente el formulario", "warning");

            const result = await Swal.fire({
                title: "¿Confirmar Pago?",
                text: "Se procesará su orden y pago.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sí, pagar",
                cancelButtonText: "Cancelar"
            });

            if (!result.isConfirmed) return;

            const fd = new FormData(form);
            try {
                const res = await fetch("controlador/reserva_cliente.php", {
                    method: "POST",
                    body: fd
                });

                const data = await res.json();

                if (!data.success) {
                    Swal.fire("Error", data.message || "Error al procesar la reserva", "error");
                } else {
                    Swal.fire({
                        title: "¡Listo!",
                        text: data.message,
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        if (data.redirect) window.location.href = data.redirect;
                    });
                }
            } catch (err) {
                Swal.fire("Error", "Comunicación fallida o sesión expirada", "error");
                console.error(err);
            }
        });
    }
});
