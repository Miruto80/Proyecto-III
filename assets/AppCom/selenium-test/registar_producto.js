// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = 'f7d719e9854d347e622d9914d7d90b4d';  // tu API Key
const TEST_PLAN_ID = 2; // ✅ tu test plan ID real
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: REGISTRAR PRODUCTOS (Adaptado a tu vista) ===
async function runTestRegistrarProductos() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Ir al login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/Lovemakeup/?pagina=login');
    await driver.sleep(2000);

    // === Paso 2: Iniciar sesión ===
    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 3: Ir a la página de productos ===
    console.log('📦 Navegando a la sección de productos...');
    await driver.get('http://localhost:8080/Lovemakeup/?pagina=producto');
    await driver.wait(until.elementLocated(By.id('btnAbrirRegistrar')), 10000);

    // === Paso 4: Abrir modal de registro ===
    console.log('🧾 Abriendo modal de registro...');
    await driver.findElement(By.id('btnAbrirRegistrar')).click();
    await driver.sleep(1000);

    // === Paso 5: Llenar los campos del formulario ===
    console.log('✍️ Llenando los datos del producto...');
    await driver.findElement(By.id('nombre')).sendKeys('Bálsamo Premium');
    await driver.findElement(By.id('marca')).sendKeys('Salome');
    await driver.findElement(By.id('descripcion')).sendKeys('Bálsamo labial hidratante de larga duración.');
    await driver.findElement(By.id('cantidad_mayor')).sendKeys('10');
    await driver.findElement(By.id('precio_detal')).sendKeys('12.50');
    await driver.findElement(By.id('precio_mayor')).sendKeys('10.00');
    await driver.findElement(By.id('stock_maximo')).sendKeys('100');
    await driver.findElement(By.id('stock_minimo')).sendKeys('10');

    // Seleccionar la primera categoría disponible
    const categoria = await driver.findElement(By.id('categoria'));
    await categoria.findElement(By.css('option:nth-child(2)')).click();

    // === Paso 6: Guardar el producto ===
    console.log('💾 Guardando producto...');
    await driver.findElement(By.id('btnEnviar')).click();

    // === Paso 7: Confirmar registro (esperar mensaje, alerta o recarga) ===
    await driver.sleep(3000);
    const pageSource = await driver.getPageSource();

    if (pageSource.includes('Producto') || pageSource.includes('registrado')) {
      console.log('✅ Producto registrado exitosamente.');
      status = 'p';
      notes = 'El producto fue registrado correctamente.';
    } else {
      notes = 'No se detectó mensaje de éxito en la página.';
    }

  } catch (error) {
    console.error('❌ Error durante la prueba de registro:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLinkRegistrar(status, notes);
  }
}

// === FUNCIÓN: Reportar resultado a TestLink ===
async function reportResultToTestLinkRegistrar(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });

    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: '1-1', // según tu archivo XML
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

// === Ejecutar test ===
runTestRegistrarProductos();
