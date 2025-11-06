// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'R-1-5'; // ⚠️ cambia al ID real en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al registrar categoría existente ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Entrar al login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(2000);

    // === Paso 2: Ingresar credenciales ===
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    // === Paso 3: Verificar redirección al home ===
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 4: Ir a Categoría ===
    console.log('🧭 Navegando a Categoría...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=categoria');
    await driver.wait(until.urlContains('pagina=categoria'), 10000);

    // === Paso 5: Abrir modal de registro ===
    console.log('🖱️ Abriendo modal de registro...');
    await driver.findElement(By.id('btnAbrirRegistrar')).click();

    // Esperar a que el modal esté visible
    await driver.wait(until.elementIsVisible(driver.findElement(By.id('nombre'))), 7000);

    // === Paso 6: Ingresar categoría existente ===
    console.log('✏️ Ingresando categoría duplicada...');
    let inputNombre = await driver.findElement(By.id('nombre'));
    await inputNombre.clear();
    await inputNombre.sendKeys('Polvo');
    await driver.findElement(By.id('btnEnviar')).click();

    // === Paso 7: Verificar mensaje de error en SweetAlert2 ===
    console.log('⏳ Verificando mensaje de error en SweetAlert2...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 7000);

    let errorText = await driver.findElement(By.css('.swal2-html-container')).getText();

    if (errorText.toLowerCase().includes('existe')) {
      console.log('✅ Mensaje de error mostrado correctamente en SweetAlert2.');
      notes = 'El sistema mostró el error esperado al registrar categoría duplicada.';
      status = 'p';
    } else {
      throw new Error('El mensaje de error no coincide con lo esperado. Texto encontrado: ' + errorText);
    }

    // Opcional: cerrar el SweetAlert2 (si no se cierra solo)
    await driver.findElement(By.css('body')).sendKeys('\uE00C'); // ESCAPE

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
