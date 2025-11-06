// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = 'f7d719e9854d347e622d9914d7d90b4d'; // tu API key real
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: ELIMINAR PRODUCTO ===
async function runTestEliminarProducto() {
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
    await driver.wait(until.elementLocated(By.css('.eliminar')), 10000);
    console.log('✅ Página de productos cargada.');

    console.log('🗑️ Haciendo clic en el botón eliminar...');
    const botonEliminar = await driver.findElement(By.css('.eliminar'));
    await botonEliminar.click();

    // Esperar a que aparezca el modal de SweetAlert2
    console.log('⚠️ Esperando confirmación de SweetAlert...');
    const botonConfirmar = await driver.wait(
      until.elementLocated(By.css('.swal2-confirm')),
      7000
    );

    // Confirmar eliminación
    console.log('✅ Confirmando eliminación...');
    await botonConfirmar.click();

    // Esperar el mensaje de éxito (SweetAlert de confirmación)
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 7000);
    const alerta = await driver.findElement(By.css('.swal2-popup')).getText();

    if (/eliminado|exitos/i.test(alerta)) {
      console.log('✅ Producto eliminado exitosamente.');
      status = 'p';
      notes = 'El producto fue eliminado correctamente (SweetAlert detectado).';
    } else {
      console.log('⚠️ No se detectó texto de confirmación.');
      notes = 'No se encontró mensaje de éxito, pero no hubo errores.';
    }

  } catch (error) {
    console.error('❌ Error durante la prueba de eliminación:', error.message);
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
      testcaseexternalid: '1-14', // ⚠️ cambia este valor al ID real del caso “Eliminar Producto”
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
  console.log('🚀 Iniciando prueba: Eliminar Producto...');
  await runTestEliminarProducto();
  console.log('✅ Prueba finalizada.');
})();
