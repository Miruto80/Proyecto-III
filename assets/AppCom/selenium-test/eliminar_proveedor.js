// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-15'; 
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: ELIMINAR PROVEEDOR ===
async function runTest() {
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === LOGIN ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === IR A PROVEEDOR ===
    console.log('📂 Navegando al módulo Proveedor...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor');
    await driver.wait(until.urlContains('pagina=proveedor'), 10000);
    console.log('✅ Página de proveedor cargada.');

    // === CLIC EN EL BOTÓN ELIMINAR ===
    console.log('🗑️ Seleccionando proveedor para eliminar...');
    const btnEliminar = await driver.wait(until.elementLocated(By.css('.eliminar')), 8000);
    await driver.executeScript('arguments[0].scrollIntoView(true);', btnEliminar);
    await driver.sleep(800);
    await driver.executeScript('arguments[0].click();', btnEliminar);

    // === CONFIRMAR SWEETALERT ===
    console.log('⚠️ Confirmando eliminación...');
    const btnConfirmar = await driver.wait(
      until.elementLocated(By.xpath("//button[contains(text(),'Sí, eliminar')]")),
      8000
    );
    await driver.executeScript('arguments[0].click();', btnConfirmar);

    // === ESPERAR ALERTA DE ÉXITO ===
    console.log('⏳ Esperando mensaje de éxito...');
    await driver.wait(
      until.elementLocated(
        By.xpath("//*[contains(text(),'Proveedor') and contains(text(),'eliminado')]")
      ),
      10000
    );

    console.log('🎉 Proveedor eliminado con éxito.');
    notes = 'El sistema eliminó correctamente al proveedor y mostró el mensaje de éxito.';
    status = 'p';

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    // Esperar unos segundos para visualizar la alerta
    await driver.sleep(4000);
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
      notes,
      status,
    };
    client.methodCall('tl.reportTCResult', [params], (error, value) => {
      if (error) console.error('⚠️ Error al enviar resultado a TestLink:', error);
      else console.log('📤 Resultado enviado a TestLink:', value);
    });
  } catch (error) {
    console.error('⚠️ No se pudo conectar con TestLink:', error);
  }
}

// === EJECUTAR TEST ===
runTest();
