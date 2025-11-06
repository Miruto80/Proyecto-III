// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = 'f7d719e9854d347e622d9914d7d90b4d'; // Tu API Key de TestLink
const TEST_PLAN_ID = 2; // ID del plan de pruebas
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: MODIFICAR PRODUCTOS CON DATOS INVÁLIDOS ===
async function runTestModificarProductosFallo() {
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
    await driver.wait(until.elementLocated(By.css('.modificar')), 8000);

    // === Paso 1: Abrir el modal de modificación del primer producto ===
    console.log('🧾 Abriendo modal de modificación...');
    const botonModificar = await driver.findElement(By.css('.modificar'));
    await botonModificar.click();
    await driver.sleep(1500);

    // === Paso 2: Editar los campos con valores inválidos o duplicados ===
    console.log('✍️ Modificando con datos inválidos...');
    const nombre = await driver.findElement(By.id('nombre'));
    await nombre.clear();
    await nombre.sendKeys('123Producto@@@'); // nombre inválido

    const marca = await driver.findElement(By.id('marca'));
    await marca.clear();
    await marca.sendKeys('!@#'); // marca inválida

    const precioDetal = await driver.findElement(By.id('precio_detal'));
    await precioDetal.clear();
    await precioDetal.sendKeys('-15'); // precio inválido

    const stockMax = await driver.findElement(By.id('stock_maximo'));
    await stockMax.clear();
    await stockMax.sendKeys('-5'); // stock inválido

    console.log('💾 Intentando guardar cambios inválidos...');
    await driver.findElement(By.id('btnEnviar')).click();
    await driver.sleep(3000);

    // === Paso 3: Verificar si aparecen mensajes de error ===
    const pageSource = await driver.getPageSource();
    if (
      pageSource.includes('error') ||
      pageSource.includes('válido') ||
      pageSource.includes('solo letras') ||
      pageSource.includes('debe ser') ||
      pageSource.includes('incorrecto') ||
      pageSource.includes('ya existe')
    ) {
      console.log('✅ Validaciones de modificación mostradas correctamente.');
      status = 'p';
      notes = 'Se detectaron correctamente las validaciones al modificar un producto.';
    } else {
      notes = 'No se mostraron los mensajes de validación esperados al modificar.';
    }

  } catch (error) {
    console.error('❌ Error durante la prueba de modificación:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLinkModificar(status, notes);
  }
}

// === REPORTAR RESULTADO A TESTLINK ===
async function reportResultToTestLinkModificar(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });
    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: '1-11', // ID del caso en TestLink (ajústalo según tu XML)
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
  console.log('🚀 Iniciando prueba: Modificar producto con error en validaciones...');
  await runTestModificarProductosFallo();
  console.log('✅ Prueba finalizada.');
})();
