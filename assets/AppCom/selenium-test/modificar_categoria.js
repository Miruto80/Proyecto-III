// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACION TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-3';
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

async function runTest() {

  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {

    // ✅ LOGIN
    console.log("🧭 Ingresando al login...");
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');

    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 8000);
    console.log("✅ Login exitoso");

    // ✅ IR A CATEGORÍA
    console.log("📂 Abriendo módulo Categoría...");
    let categoriaBtn = await driver.findElement(By.xpath("//*[contains(text(),'Categ')]"));
    await driver.executeScript("arguments[0].click()", categoriaBtn);
    await driver.wait(until.urlContains('pagina=categoria'), 8000);
    console.log("✅ Categoría abierta");

    // ✅ ABRIR MODAL DE MODIFICAR
    console.log("✏️ Abriendo categoría para modificar...");
    let btnModif = await driver.findElement(By.css(".btnModif"));
    await driver.executeScript("arguments[0].click()", btnModif);

    await driver.sleep(1000);
    console.log("✅ Modal de edición abierto");

    // ✅ MODIFICAR INPUT NOMBRE
    console.log("📝 Modificando nombre...");
    let inputNombre = await driver.findElement(By.id("nombre"));
    await driver.executeScript("arguments[0].scrollIntoView(true)", inputNombre);
    await driver.sleep(300);

    await driver.executeScript("arguments[0].value=''", inputNombre);
    await inputNombre.clear();
    await driver.sleep(300);
    await inputNombre.sendKeys("Categoria Modificada");

    // ✅ GUARDAR
    console.log("💾 Guardando...");
    let btnGuardar = await driver.findElement(By.id("btnEnviar"));
    await driver.executeScript("arguments[0].click()", btnGuardar);

    // ✅ CONFIRMAR MENSAJE EXITO (SweetAlert2)
    console.log("⏳ Verificando confirmación...");
    await driver.wait(
      until.elementLocated(By.xpath("//*[contains(text(),'Modificada') or contains(text(),'actualizad') or contains(text(),'Actualizada')]")),
      8000
    );

    console.log("🎉 Modificación exitosa");
    notes = "✅ Modificación realizada correctamente";
    status = "p";

  } catch (error) {
    console.log("❌ Error:", error.message);
    notes = "Error: " + error.message;
  } finally {

    await driver.quit();
    await reportToTestlink(status, notes);
  }
}

// === REPORTAR A TESTLINK ===
async function reportToTestlink(status, notes) {

  const client = xmlrpc.createClient({ url: TESTLINK_URL });

  notes = String(notes).replace(/<br>/gi, "\n");

  client.methodCall("tl.reportTCResult", [{
    devKey: DEV_KEY,
    testcaseexternalid: TEST_CASE_EXTERNAL_ID,
    testplanid: TEST_PLAN_ID,
    buildname: BUILD_NAME,
    notes: notes,
    status: status
  }], function(err, val) {
    if (err) console.error("⚠️ Error TestLink:", err);
    else console.log("📤 Resultado enviado:", val);
  });
}

runTest();
