async function obtenerTasaDolarApi() {
    try {
        const respuesta = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }

        const datos = await respuesta.json();
        const tasaBCV = datos.promedio.toFixed(2); // Redondea la tasa a 2 decimales

        document.getElementById("bcv").textContent = "Tasa del Día: " + tasaBCV + " Bs";
    } catch (error) {
        document.getElementById("bcv").textContent = "Error al cargar la tasa";
    }
}

document.addEventListener("DOMContentLoaded", obtenerTasaDolarApi);
