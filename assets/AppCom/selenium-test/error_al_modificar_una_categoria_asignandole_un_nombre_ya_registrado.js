// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'R-1-8'; // ⚠️ cambia al ID real en tu TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al modificar una categoría asignándole un nombre ya registrado ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(1000);
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Ir a Categoría ===
    console.log('🧭 Navegando a Categoría...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=categoria');
    await driver.wait(until.urlContains('pagina=categoria'), 10000);

    // === Seleccionar cualquier fila existente y abrir modal de Modificar ===
    console.log('🖱️ Abriendo modal de Modificar...');
    const btnModificar = await driver.findElement(By.css('.btnModif'));
    await btnModificar.click();

    await driver.wait(until.elementIsVisible(driver.findElement(By.id('nombre'))), 8000);

    // === Intentar renombrar a "Polvo" (duplicado) ===
    console.log('✏️ Asignando nombre duplicado "Polvo"...');
    const inputNombre = await driver.findElement(By.id('nombre'));
    await inputNombre.clear();
    await inputNombre.sendKeys('Polvo');

    await driver.findElement(By.id('btnEnviar')).click();

    // === Validar SweetAlert2 de error por duplicado ===
    console.log('⏳ Esperando SweetAlert2...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 10000);

    let errorText = await driver.findElement(By.css('.swal2-html-container')).getText();
    console.log('📋 Texto del alerta:', errorText);

    if (
      errorText.toLowerCase().includes('ya existe') ||
      errorText.toLowerCase().includes('existe una categoria') ||
      errorText.toLowerCase().includes('existe una categoría')
    ) {
      console.log('✅ Error por nombre duplicado mostrado correctamente.');
      notes = 'El sistema impidió la modificación con nombre “Polvo” ya registrado y mostró el alerta esperado.';
      status = 'p';
    } else {
      throw new Error('El mensaje del alerta no indica duplicado. Texto: ' + errorText);
    }

    // Esperar unos segundos para apreciar el mensaje
    await driver.sleep(2000);

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === FUNCIÓN: Reportar resultado a TestLink ===
async function reportResultToTestLink(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });

    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: TEST_CASE_EXTERNAL_ID,
      testplanid: TEST_PLAN_ID,
      buildname: BUILD_NAME,
      notes: notes,
      status: status,
    };

    client.methodCall('tl.reportTCResult', [params], function (error, value) {
      if (error) {
        console.error('⚠️ Error al enviar resultado a TestLink:', error);
      } else {
        console.log('📤 Resultado enviado a TestLink:', value);
      }
    });
  } catch (error) {
    console.error('⚠️ No se pudo conectar con TestLink:', error);
  }
}

// === Ejecutar test ===
runTest();
