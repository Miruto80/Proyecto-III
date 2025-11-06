// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'R-1-10'; // ⚠️ cambia al ID real en tu TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al modificar una categoría por incumplimiento del rango permitido de caracteres ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(1000);
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 2: Ir a Categoría ===
    console.log('🧭 Navegando a Categoría...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=categoria');
    await driver.wait(until.urlContains('pagina=categoria'), 10000);

    // === Paso 3: Seleccionar una categoría y abrir modal de Modificar ===
    console.log('🖱️ Abriendo modal de Modificar...');
    const btnModificar = await driver.findElement(By.css('.btnModif')); // primer botón de modificar
    await btnModificar.click();

    await driver.wait(until.elementIsVisible(driver.findElement(By.id('nombre'))), 8000);

    // === Paso 4: Intentar asignar un nombre demasiado corto ===
    console.log('✏️ Ingresando nombre demasiado corto...');
    const inputNombre = await driver.findElement(By.id('nombre'));
    await inputNombre.clear();
    await inputNombre.sendKeys('Po'); // menos de 3 caracteres
    await driver.sleep(500);

    // === Paso 5: Verificar mensaje de error debajo del input ===
    console.log('⏳ Verificando mensaje de error debajo del input (#snombre)...');
    const errorMsgEl = await driver.wait(until.elementLocated(By.id('snombre')), 7000);
    const errorText = await errorMsgEl.getText();

    if (errorText.toLowerCase().includes('3 a 30 caracteres')) {
      console.log('✅ Mensaje de error mostrado correctamente.');
      notes = 'El sistema mostró el error esperado al intentar modificar una categoría con menos de 3 caracteres.';
      status = 'p';
    } else {
      throw new Error('El mensaje de error no coincide con lo esperado. Texto encontrado: ' + errorText);
    }

    // Pausa breve para apreciar el mensaje
    await driver.sleep(3000);

    // Importante: NO enviar el formulario (no se debe guardar nada en esta prueba)

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
