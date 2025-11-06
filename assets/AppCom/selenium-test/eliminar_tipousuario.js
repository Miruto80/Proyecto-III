// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639'; // tu clave API
const TEST_CASE_EXTERNAL_ID = 'R-1-24'; // ID de caso en TestLink
const TEST_PLAN_ID = 2; // tu plan de prueba
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: ELIMINAR TIPO DE USUARIO ===
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
    console.log('✏️ Ingresando credenciales...');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');

    // === Paso 3: Ingresar ===
    console.log('🖱️ Haciendo clic en "Ingresar"...');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);

    // === Paso 4: Navegar a Tipo Usuario ===
    console.log('📂 Navegando a módulo Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.urlContains('pagina=tipousuario'), 10000);
    await driver.sleep(1500);

    // === Paso 5: Buscar botón eliminar (que NO sea id=2) ===
    console.log('🗑️ Buscando botón "Eliminar"...');
    const btnEliminar = await driver.wait(
      until.elementLocated(By.css('.eliminar[value]:not([value="2"])')),
      10000
    );
    await btnEliminar.click();

    // === Paso 6: Confirmar SweetAlert ===
    console.log('⚠️ Confirmando eliminación...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 10000);
    await driver.findElement(By.css('.swal2-confirm')).click();

    // === Paso 7: Esperar mensaje de éxito ===
    console.log('⏳ Esperando mensaje de éxito...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 10000);
    const mensaje = await driver.findElement(By.css('.swal2-title')).getText();

    if (mensaje.includes('Rol eliminado con éxito')) {
      console.log('✅ Prueba exitosa: Rol eliminado correctamente.');
      status = 'p';
      notes = 'Rol eliminado correctamente.';
    } else {
      throw new Error('El mensaje de éxito no apareció o fue diferente.');
    }

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.sleep(1500);
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

// === Ejecutar prueba ===
runTest();
