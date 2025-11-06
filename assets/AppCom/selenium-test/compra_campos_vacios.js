const { Builder, By, Key, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '76133924c3d3f13d8490b26f5d5a7ca5';
const TEST_CASE_EXTERNAL_ID = 'Prueba-11'; // Ajusta al ID real en TestLink
const TEST_PLAN_ID = 104;
const BUILD_ID = 1;

async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === LOGIN ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === MÓDULO COMPRA ===
    console.log('🛒 Abriendo módulo Compra...');
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=entrada');
    await driver.wait(until.elementLocated(By.css('button[data-bs-target="#registroModal"]')), 10000);
    console.log('✅ Módulo Compra cargado.');

    // === ABRIR FORMULARIO ===
    console.log('📄 Abriendo formulario de compra...');
    const registrarBtn = await driver.findElement(By.css('button[data-bs-target="#registroModal"]'));
    await driver.executeScript("arguments[0].scrollIntoView({block: 'center'});", registrarBtn);
    await driver.wait(until.elementIsVisible(registrarBtn), 10000);
    await driver.wait(until.elementIsEnabled(registrarBtn), 10000);
    try {
      await registrarBtn.click();
    } catch {
      await driver.executeScript("arguments[0].click();", registrarBtn);
    }

    const modal = await driver.findElement(By.id('registroModal'));
    await driver.wait(until.elementIsVisible(modal), 10000);
    console.log('✅ Modal abierto.');

    // === DEJAR CAMPOS VACÍOS ===
    console.log('🚫 Dejando proveedor y producto vacíos...');

    // === INTENTAR REGISTRAR COMPRA ===
    console.log('💾 Intentando registrar compra sin datos...');
    const registrarCompraBtn = await driver.findElement(By.css('button[name="registrar_compra"]'));
    await driver.executeScript("arguments[0].scrollIntoView({block: 'center'});", registrarCompraBtn);
    await driver.wait(until.elementIsVisible(registrarCompraBtn), 10000);

    try {
      await registrarCompraBtn.click();
    } catch {
      await driver.executeScript("arguments[0].click();", registrarCompraBtn);
    }

    // Esperar un momento para ver si el sistema responde
    await driver.sleep(2000);

    // === VALIDAR RESULTADO ESPERADO ===
    console.log('🔍 Verificando comportamiento del sistema...');
    let errorDetectado = false;
    let modalVisible = true;

    // 1️⃣ Buscar mensaje de error (si existe)
    try {
      const errorMsg = await driver.findElement(By.xpath("//*[contains(text(),'faltan datos obligatorios')]"));
      if (await errorMsg.isDisplayed()) {
        errorDetectado = true;
        console.log('✅ Mensaje de error visible.');
      }
    } catch {
      console.log('⚠️ No se detectó mensaje visible.');
    }

    // 2️⃣ Verificar si el modal sigue abierto (no se registró la compra)
    try {
      const modalElement = await driver.findElement(By.id('registroModal'));
      modalVisible = await modalElement.isDisplayed();
    } catch {
      modalVisible = false;
    }

    if (errorDetectado || modalVisible) {
      console.log('✅ El sistema bloqueó correctamente el registro con datos vacíos.');
      notes = '✅ Error esperado: el sistema no permitió registrar una compra con campos obligatorios vacíos.';
      status = 'p';
    } else {
      console.log('❌ El sistema permitió continuar o cerró el modal (error real).');
      notes = '❌ El sistema permitió continuar o registrar compra con campos vacíos.';
      status = 'f';
    }

  } catch (error) {
    console.error('❌ Excepción durante la prueba:', error.message);
    notes = '❌ Error inesperado durante la prueba: ' + error.message;
    status = 'f';
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === FUNCIONES AUXILIARES ===
async function reportResultToTestLink(status, notes) {
  const client = xmlrpc.createClient({ url: TESTLINK_URL });
  const cleanNotes = notes.replace(/<[^>]*>/g, '').replace(/\n/g, ' ').trim();

  return new Promise((resolve, reject) => {
    client.methodCall('tl.checkDevKey', [{ devKey: DEV_KEY }], function (error) {
      if (error) {
        console.error('❌ Error con DevKey o conexión:', error);
        reject(error);
        return;
      }

      const params = {
        devKey: DEV_KEY,
        testcaseexternalid: TEST_CASE_EXTERNAL_ID,
        testplanid: TEST_PLAN_ID,
        buildid: BUILD_ID,
        notes: cleanNotes,
        status: status,
      };

      client.methodCall('tl.reportTCResult', [params], function (error, value) {
        if (error) {
          console.error('⚠️ Error al enviar resultado a TestLink:', error);
          reject(error);
        } else {
          console.log('📤 Resultado reportado a TestLink:', value);
          resolve(value);
        }
      });
    });
  });
}

// === Ejecutar prueba ===
runTest().catch(console.error);
