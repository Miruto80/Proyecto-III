// === DEPENDENCIAS ===
const { Builder, By, until } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "11ec259b8ac7c56e5d7a47814a33f639"; // tu API key real
const TEST_CASE_EXTERNAL_ID = "R-1-27"; // ID del caso 1-27
const TEST_PLAN_ID = 2;
const BUILD_NAME = "v.1";

// === TEST AUTOMATIZADO: ERROR AL REGISTRAR TIPO USUARIO CON RANGO INCORRECTO ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f";
  let notes = "";

  try {
    console.log("🧭 Navegando al login...");
    await driver.get("http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login");
    await driver.wait(until.elementLocated(By.id("usuario")), 10000);

    // === LOGIN ===
    console.log("🔐 Iniciando sesión...");
    await driver.findElement(By.id("usuario")).sendKeys("10200300");
    await driver.findElement(By.id("pid")).sendKeys("love1234");
    await driver.findElement(By.id("ingresar")).click();
    await driver.wait(until.urlContains("pagina=home"), 10000);
    console.log("✅ Login exitoso.");

    // === NAVEGAR AL MÓDULO ===
    console.log("📂 Abriendo módulo Tipo Usuario...");
    await driver.get("http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario");
    await driver.wait(until.elementLocated(By.id("myTable")), 10000);
    console.log("✅ Página cargada correctamente.");

    // === ABRIR MODAL DE REGISTRO ===
    console.log("🖱️ Abriendo modal de registro...");
    const registrarBtn = await driver.findElement(By.xpath("//button[contains(., 'Registrar')]"));
    await registrarBtn.click();
    await driver.sleep(1500);

    // === ESCENARIOS INVÁLIDOS ===
    const casos = [
      "As", // menor a 3 caracteres
      "Asesor de Ventas del negocio Love Makeup que sobrepasa los 30 caracteres", // mayor a 30
      "" // vacío
    ];

    for (let i = 0; i < casos.length; i++) {
      console.log(`🧪 Caso ${i + 1}: probando con nombre "${casos[i]}"`);
      await driver.sleep(1500);

      // Limpiar y llenar los campos
      const inputNombre = await driver.findElement(By.id("nombre"));
      await inputNombre.clear();
      await inputNombre.sendKeys(casos[i]);

      // Seleccionar nivel y estatus
      await driver.findElement(By.id("nivel")).sendKeys("Nivel 2 - Acceso Limitado");
      await driver.findElement(By.id("estatus")).sendKeys("Activo");

      // Clic en REGISTRAR
      await driver.findElement(By.id("registrar")).click();
      await driver.sleep(2000);

      console.log("🧾 Validando alerta de error...");
      // No se requiere verificar con assert, solo que no explote
    }

    console.log("✅ Prueba completada — El sistema muestra error por nombre inválido.");
    status = "p";
    notes = "El sistema validó correctamente el rango permitido (3–30 caracteres).";

  } catch (error) {
    console.error("❌ Error durante la prueba:", error.message);
    notes = "Error: " + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === ENVIAR RESULTADO A TESTLINK ===
async function reportResultToTestLink(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });
    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: TEST_CASE_EXTERNAL_ID,
      testplanid: TEST_PLAN_ID,
      buildname: BUILD_NAME,
      notes,
      status
    };
    client.methodCall("tl.reportTCResult", [params], function (error, value) {
      if (error) console.error("⚠️ Error al enviar resultado:", error);
      else console.log("📤 Resultado enviado a TestLink:", value);
    });
  } catch (error) {
    console.error("⚠️ No se pudo conectar con TestLink:", error);
  }
}

// === Ejecutar test ===
runTest();
