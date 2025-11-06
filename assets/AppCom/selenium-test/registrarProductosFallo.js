// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = 'f7d719e9854d347e622d9914d7d90b4d'; // tu API Key
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: REGISTRO CON DATOS INVÁLIDOS ===
async function runTestRegistrarProductosFallo() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/Lovemakeup/?pagina=login');
    await driver.sleep(1500);

    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 8000);
    console.log('✅ Login exitoso.');

    console.log('📦 Navegando a productos...');
    await driver.get('http://localhost:8080/Lovemakeup/?pagina=producto');
    await driver.wait(until.elementLocated(By.id('btnAbrirRegistrar')), 8000);

    console.log('🧾 Abriendo modal de registro...');
    await driver.findElement(By.id('btnAbrirRegistrar')).click();
    await driver.sleep(1000);

    console.log('✍️ Llenando campos con datos inválidos...');
    await driver.findElement(By.id('nombre')).sendKeys('Bal1s*a premium');
    await driver.findElement(By.id('marca')).sendKeys('123456');
    await driver.findElement(By.id('descripcion')).sendKeys('Descripción inválida ***');
    await driver.findElement(By.id('cantidad_mayor')).sendKeys('-10');
    await driver.findElement(By.id('precio_detal')).sendKeys('-5');
    await driver.findElement(By.id('precio_mayor')).sendKeys('-2');
    await driver.findElement(By.id('stock_maximo')).sendKeys('0');
    await driver.findElement(By.id('stock_minimo')).sendKeys('-1');

    console.log('💾 Intentando guardar producto inválido...');
    await driver.findElement(By.id('btnEnviar')).click();

    await driver.sleep(2500);
    const pageSource = await driver.getPageSource();

    // Verifica si aparecen mensajes de validación (ajusta texto según tus mensajes reales)
    if (
      pageSource.includes('error') ||
      pageSource.includes('válido') ||
      pageSource.includes('debe ser') ||
      pageSource.includes('incorrecto') ||
      pageSource.includes('solo letras')
    ) {
      console.log('✅ Validaciones mostradas correctamente. Caso de fallo controlado.');
      status = 'p';
      notes = 'Las validaciones de error funcionaron correctamente.';
    } else {
      notes = 'No se mostraron mensajes de error esperados.';
    }

  } catch (error) {
    console.error('❌ Error durante la prueba de validación:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLinkFallo(status, notes);
  }
}

// === REPORTAR RESULTADO A TESTLINK ===
async function reportResultToTestLinkFallo(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });
    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: '1-3', // de tu XML
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

// === EJECUTAR TEST ===
(async () => {
  console.log('🚀 Iniciando prueba: Registro con datos inválidos...');
  await runTestRegistrarProductosFallo();
  console.log('✅ Prueba finalizada.');
})();
