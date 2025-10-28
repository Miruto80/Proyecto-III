// === DEPENDENCIAS ===
const { Builder, By, Key, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '55387a68ad480af2c9f640e71f955f57';  // tu API Key
const TEST_PLAN_NAME = 'lovemakeup';
const TEST_CASE_EXTERNAL_ID = '1-11';
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f'; // f = failed | p = passed
  let notes = '';

  try {
    // Paso 1: Entrar a la URL del login
    await driver.get('http://localhost:8080/proyectoIII/Proyecto-III/?pagina-login');

    // Paso 2: Ingresar usuario y contraseña
    await driver.findElement(By.name('cedula')).sendKeys('30559878');
    await driver.findElement(By.name('password')).sendKeys('25002100');

    // Paso 3: Hacer clic en "Ingresar"
    await driver.findElement(By.css('button[type="submit"]')).click();

    // Esperar redirección a catálogo
    await driver.wait(until.urlContains('pagina=catalogo'), 5000);

    console.log('✅ Inicio de sesión exitoso, usuario en catálogo');
    notes = 'Inicio de sesión y redirección al catálogo correcta.';
    status = 'p';

  } catch (error) {
    console.error('❌ Error durante la prueba:', error);
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
      testplanname: TEST_PLAN_NAME,
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
