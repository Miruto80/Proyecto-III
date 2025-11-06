// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIG TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-4'; 
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 8000);

    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 8000);
    console.log('✅ Login exitoso');

    // Ir a categoría
    console.log('📂 Abriendo Categoría...');
    let categoriaBtn = await driver.findElement(By.xpath("//*[contains(text(),'Categ')]"));
    await driver.executeScript("arguments[0].click();", categoriaBtn);
    await driver.wait(until.urlContains('pagina=categoria'), 8000);
    console.log('✅ Página de categoría cargada');

    // Click botón eliminar
    console.log('🗑️ Seleccionando categoría para eliminar...');
    let btnEliminar = await driver.findElement(By.css('.btnElim'));

    // Obtener ID de la fila que vamos a eliminar
    let fila = await btnEliminar.findElement(By.xpath('./ancestor::tr'));
    let idCategoriaSeleccionada = await fila.getAttribute('data-id');

    await driver.executeScript("arguments[0].click();", btnEliminar);
    await driver.sleep(800);

    // Confirmar SweetAlert — texto exacto
    console.log('⚠️ Confirmando eliminación...');
    let btnConfirmar = await driver.wait(
      until.elementLocated(By.xpath("//button[contains(text(),'Sí, eliminar')]")),
      8000
    );

    await driver.executeScript("arguments[0].click();", btnConfirmar);

    // Esperar cierre de SweetAlert
    await driver.wait(until.stalenessOf(btnConfirmar), 8000);

    // Forzar actualización DataTable
    console.log("🔄 Refrescando tabla...");
    await driver.executeScript("$('#myTable').DataTable().draw();");
    await driver.sleep(1500);

    console.log("⏳ Verificando eliminación...");

    // Verificar que la fila ya NO exista
    let filaEliminada = await driver.findElements(By.xpath(`//tr[@data-id='${idCategoriaSeleccionada}']`));

    if (filaEliminada.length > 0) {
      throw new Error("La categoría todavía aparece en la tabla, posible retraso en DataTable");
    }

    console.log('✅ Categoría eliminada con éxito');
    notes = 'Eliminar categoría exitoso.';
    status = 'p';

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === Reportar a TestLink ===
async function reportResultToTestLink(status, notes) {
  const client = xmlrpc.createClient({ url: TESTLINK_URL });
  const params = {
    devKey: DEV_KEY,
    testcaseexternalid: TEST_CASE_EXTERNAL_ID,
    testplanid: TEST_PLAN_ID,
    buildname: BUILD_NAME,
    notes,
    status,
  };

  client.methodCall('tl.reportTCResult', [params], function (err, val) {
    if (err) console.error('⚠️ Error TestLink:', err);
    else console.log('📤 Resultado enviado a TestLink:', val);
  });
}

runTest();
