// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'R-1-32'; // ⚠️ ID del caso en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Ver información del proveedor ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === LOGIN ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 8000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 8000);
    console.log('✅ Login exitoso.');

    // === ENTRAR A PROVEEDOR ===
    console.log('📂 Navegando al módulo Proveedor...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor');
    await driver.wait(until.urlContains('pagina=proveedor'), 8000);
    console.log('✅ Página de proveedor cargada.');

    // === ABRIR MODAL DE INFORMACIÓN ===
    console.log('👁️ Buscando botón "Ver información"...');
    const btnVerInfo = await driver.findElement(By.css('button.btn-info[data-bs-target^="#verDetallesModal"]'));
    await driver.executeScript("arguments[0].click();", btnVerInfo);
    console.log('✅ Botón "Ver información" clickeado.');

    // Esperar que el modal aparezca
    await driver.wait(until.elementLocated(By.css('.modal.show .modal-content')), 8000);
    console.log('✅ Modal de información abierto.');

    // Esperar unos segundos para apreciar los datos
    console.log('⏳ Mostrando información del proveedor...');
    await driver.sleep(5000);

    status = 'p';
    notes = 'El modal de información del proveedor se abrió correctamente y los datos fueron visibles.';
    console.log('✅ Información del proveedor mostrada correctamente.');

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === REPORTAR RESULTADO A TESTLINK ===
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
