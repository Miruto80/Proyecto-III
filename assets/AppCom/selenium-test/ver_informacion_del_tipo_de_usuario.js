// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-31';
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: VER INFORMACIÓN DEL TIPO DE USUARIO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Navegar al login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(2000);

    // === Paso 2: Ingresar credenciales ===
    console.log('✏️ Ingresando credenciales...');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');

    // === Paso 3: Clic en ingresar ===
    console.log('🖱️ Haciendo clic en "Ingresar"...');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);

    // === Paso 4: Ir al módulo Tipo Usuario ===
    console.log('📂 Navegando al módulo Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.urlContains('pagina=tipousuario'), 10000);
    await driver.sleep(2000);

    // === Paso 5: Buscar botón "Ver información" ===
    console.log('👁️ Buscando botón "Ver información"...');
    const btnVerInfo = await driver.wait(
      until.elementLocated(By.css('.btn-info[data-bs-target="#infoModal"]')),
      10000
    );
    await btnVerInfo.click();

    // === Paso 6: Esperar apertura del modal ===
    console.log('📋 Esperando que se abra el modal de información...');
    await driver.wait(until.elementLocated(By.id('infoModal')), 10000);
    await driver.sleep(1000);

    // === Paso 7: Verificar contenido del modal ===
    const nombre = await driver.findElement(By.id('modalNombre')).getText();
    const nivel = await driver.findElement(By.id('modalNivel')).getText();
    const estatus = await driver.findElement(By.id('modalEstatus')).getText();

    console.log(`🔍 Datos mostrados: Nombre=${nombre} | Nivel=${nivel} | Estatus=${estatus}`);

    if (nombre && nivel && estatus) {
      console.log('✅ Información mostrada correctamente en el modal.');
      status = 'p';
      notes = `Información visible correctamente. Nombre: ${nombre}, Nivel: ${nivel}, Estatus: ${estatus}`;
    } else {
      throw new Error('No se mostraron todos los datos esperados en el modal.');
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

