// === DEPENDENCIAS ===
const { Builder, By, until } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = "http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "11ec259b8ac7c56e5d7a47814a33f639";
const TEST_CASE_EXTERNAL_ID = "R-1-20"; // Cambia si es necesario
const TEST_PLAN_ID = 2;
const BUILD_NAME = "v.1";

async function runTest() {
  const driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f";
  let notes = "";

  try {
    // === Paso 1: Login ===
    console.log("🧭 Navegando al login...");
    await driver.get("http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login");
    await driver.wait(until.elementLocated(By.id("usuario")), 8000);
    await driver.findElement(By.id("usuario")).sendKeys("10200300");
    await driver.findElement(By.id("pid")).sendKeys("love1234");
    await driver.findElement(By.id("ingresar")).click();
    await driver.wait(until.urlContains("pagina=home"), 8000);
    console.log("✅ Login exitoso.");

    // === Paso 2: Ir al módulo Proveedor ===
    console.log("📂 Navegando al módulo Proveedor...");
    await driver.get("http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor");
    await driver.wait(until.urlContains("pagina=proveedor"), 8000);
    console.log("✅ Página de proveedor cargada.");

    // === Paso 3: Abrir modal Registrar ===
    console.log("🪟 Abriendo modal de registro...");
    const btnRegistrar = await driver.findElement(By.id("btnAbrirRegistrar"));
    await driver.executeScript("arguments[0].click();", btnRegistrar);
    await driver.wait(until.elementLocated(By.id("formProveedor")), 5000);
    console.log("✅ Modal de registro abierto.");

    // === Paso 4: Llenar formulario con datos inválidos ===
    console.log("📝 Ingresando datos fuera de rango...");
    await driver.findElement(By.id("tipo_documento")).sendKeys("V");
    await driver.findElement(By.id("numero_documento")).sendKeys("30753995");
    await driver.findElement(By.id("nombre")).sendKeys("Rh"); // Muy corto (mínimo 3)
    await driver.findElement(By.id("correo")).sendKeys("virguezrhichard11@gmail.com");
    await driver.findElement(By.id("telefono")).sendKeys("04245"); // Incompleto (debe tener 11)
    await driver.findElement(By.id("direccion")).sendKeys("Cab"); // Muy corta (mínimo 3-70 caracteres)

    // === Paso 5: Hacer clic en Registrar ===
    console.log("💾 Intentando registrar...");
    const btnEnviar = await driver.findElement(By.id("btnEnviar"));
    await driver.executeScript("arguments[0].click();", btnEnviar);

    // === Paso 6: Esperar SweetAlert de error o mensajes debajo de inputs ===
    console.log("⏳ Esperando validaciones...");
    let swalVisible = false;

    try {
      const swal = await driver.wait(until.elementLocated(By.css(".swal2-popup")), 5000);
      await driver.wait(until.elementIsVisible(swal), 4000);
      const texto = await swal.getText();
      if (texto.toLowerCase().includes("error") || texto.toLowerCase().includes("campos")) {
        swalVisible = true;
      }
    } catch (e) {
      // si no aparece SweetAlert, validamos por mensajes en los spans
    }

    // Revisar mensajes debajo de los inputs
    const snombre = await driver.findElement(By.id("snombre")).getText();
    const stelefono = await driver.findElement(By.id("stelefono")).getText();
    const sdireccion = await driver.findElement(By.id("sdireccion")).getText();

    const hayErrores = snombre.trim() !== "" || stelefono.trim() !== "" || sdireccion.trim() !== "";

    if (swalVisible || hayErrores) {
      console.log("✅ Validaciones mostradas correctamente (SweetAlert o spans visibles).");
      notes = `swal=${swalVisible}, errores=[${snombre}, ${stelefono}, ${sdireccion}]`;
      status = "p";
    } else {
      throw new Error("No se detectó mensaje de error ni validaciones visibles.");
    }

    // Esperar unos segundos para visualizar la alerta
    await driver.sleep(4000);

  } catch (error) {
    console.error("❌ Error durante la prueba:", error.message);
    notes = "Error: " + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === Reportar resultado a TestLink ===
async function reportResultToTestLink(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });
    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: TEST_CASE_EXTERNAL_ID,
      testplanid: TEST_PLAN_ID,
      buildname: BUILD_NAME,
      notes,
      status,
    };
    client.methodCall("tl.reportTCResult", [params], (err, val) => {
      if (err) console.error("⚠️ Error al enviar resultado a TestLink:", err);
      else console.log("📤 Resultado enviado a TestLink:", val);
    });
  } catch (err) {
    console.error("⚠️ No se pudo conectar con TestLink:", err);
  }
}

runTest();
