// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '55387a68ad480af2c9f640e71f955f57';  // tu API Key
const TEST_CASE_EXTERNAL_ID = '1-1'; // cambia al ID real en tu TestLink
const TEST_PLAN_ID = 3; // ✅ tu test plan ID real
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: LOGIN CORRECTO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f'; // f = failed | p = passed
  let notes = '';

  try {
    // === Paso 1: Entrar al login ===
    console.log('🧭 Navegando al formulario de login...');
    await driver.get('https://lovemakeuptienda.com/?pagina=login');

    // Esperar un poco para verificar que la página carga
    await driver.sleep(2000);


       // Esperar que cargue el campo de usuario
       await driver.wait(until.elementLocated(By.id('usuario')), 10000);
       console.log('✅ Página de login cargada correctamente.');
   
       // === Paso 2: Ingresar cedula y contraseña ===
       console.log('✏️ Ingresando cédula y contraseña...');
       await driver.findElement(By.id('usuario')).sendKeys('30559878');
       await driver.findElement(By.id('pid')).sendKeys('25002100');
   
       // === Paso 3: Hacer clic en "Ingresar" ===
       console.log('🖱️ Haciendo clic en "Ingresar"...');
       await driver.findElement(By.id('ingresar')).click();
   
       // === Paso 4: Verificar redirección al home ===
       console.log('⏳ Esperando redirección...');
       await driver.wait(until.urlContains('pagina=catalogo'), 10000);

    console.log('✅ Página de login cargada correctamente');
    notes = 'Página de login cargada correctamente.';
    status = 'p';

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
      testplanid: TEST_PLAN_ID, // ✅ usamos directamente el número 3
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
