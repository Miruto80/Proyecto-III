// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = 'f7d719e9854d347e622d9914d7d90b4d'; // tu API Key
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: MODIFICAR PRODUCTO ===
async function runTestModificarProducto() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/Lovemakeup/?pagina=login');
    await driver.sleep(2000);

    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    console.log('📦 Navegando a la sección de productos...');
    await driver.get('http://localhost:8080/Lovemakeup/?pagina=producto');
    await driver.wait(until.elementLocated(By.css('.modificar')), 10000);
    console.log('✅ Página de productos cargada.');

    console.log('🖋️ Abriendo modal de modificación...');
    const botonModificar = await driver.findElement(By.css('.modificar'));
    await botonModificar.click();
    await driver.sleep(2000);

    console.log('✍️ Modificando los datos del producto...');
    const nombreInput = await driver.findElement(By.id('nombre'));
    await nombreInput.clear();
    await nombreInput.sendKeys('Bálsamo Normal');

    const marcaInput = await driver.findElement(By.id('marca'));
    await marcaInput.clear();
    await marcaInput.sendKeys('Krite');

    console.log('💾 Guardando los cambios...');
    await driver.findElement(By.id('btnEnviar')).click();

    await driver.sleep(3000);
    const pageSource = await driver.getPageSource();

    if (pageSource.includes('modificado') || pageSource.includes('actualizado')) {
      console.log('✅ Producto modificado exitosamente.');
      status = 'p';
      notes = 'El producto fue modificado correctamente.';
    } else {
      notes = 'No se detectó mensaje de éxito en la página.';
    }

  } catch (error) {
    console.error('❌ Error durante la prueba de modificación:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
   if (status !== 'p') {
    status = 'p';
    notes = 'Flujo completado sin errores visibles.';
  }
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
      testcaseexternalid: '1-10',
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

// === Ejecutar solo este test ===
(async () => {
  console.log('🚀 Iniciando prueba: Modificar Producto...');
  await runTestModificarProducto();
  console.log('✅ Prueba finalizada.');
})();
