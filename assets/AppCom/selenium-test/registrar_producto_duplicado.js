// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = 'f7d719e9854d347e622d9914d7d90b4d';
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: REGISTRAR PRODUCTO DUPLICADO ===
async function runTestRegistrarProductoDuplicado() {
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
    await driver.wait(until.elementLocated(By.id('btnAbrirRegistrar')), 10000);
    console.log('✅ Página de productos cargada.');

    console.log('🧾 Abriendo modal de registro...');
    await driver.findElement(By.id('btnAbrirRegistrar')).click();
    await driver.sleep(1000);

    console.log('✍️ Llenando los datos del producto duplicado...');
    await driver.findElement(By.id('nombre')).sendKeys('Prueba'); // Ya existente
    await driver.findElement(By.id('marca')).sendKeys('Prueba'); // Ya existente
    await driver.findElement(By.id('descripcion')).sendKeys('Intento duplicado del producto existente.');
    await driver.findElement(By.id('cantidad_mayor')).sendKeys('10');
    await driver.findElement(By.id('precio_detal')).sendKeys('12.50');
    await driver.findElement(By.id('precio_mayor')).sendKeys('10.00');
    await driver.findElement(By.id('stock_maximo')).sendKeys('100');
    await driver.findElement(By.id('stock_minimo')).sendKeys('10');

    const categoria = await driver.findElement(By.id('categoria'));
    await categoria.findElement(By.css('option:nth-child(2)')).click();

    console.log('💾 Intentando guardar producto duplicado...');
    await driver.findElement(By.id('btnEnviar')).click();

    // === Paso 7: Esperar mensaje de error o alerta ===
    console.log('⏳ Esperando mensaje de error o alerta...');
    await driver.sleep(3000);

    let mensaje = '';
    try {
      // Buscar si aparece SweetAlert o mensaje de error
      const alerta = await driver.findElement(By.css('.swal2-popup'));
      mensaje = await alerta.getText();
    } catch {
      const pageSource = await driver.getPageSource();
      mensaje = pageSource;
    }

    console.log('📄 Mensaje detectado:', mensaje);

    if (/ya existe|duplicado|existente|registrado anteriormente/i.test(mensaje)) {
      console.log('✅ Sistema detectó correctamente el producto duplicado.');
      status = 'p';
      notes = 'El sistema mostró mensaje de duplicado correctamente: ' + mensaje;
    } else {
      console.log('❌ El sistema NO detectó duplicado.');
      status = 'f';
      notes = 'El sistema permitió registrar un producto existente. Mensaje: ' + mensaje;
    }

  } catch (error) {
    console.error('❌ Error durante la prueba de producto duplicado:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLinkDuplicado(status, notes);
  }
}

// === FUNCIÓN: Reportar resultado a TestLink ===
async function reportResultToTestLinkDuplicado(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });
    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: '1-2', // ⚠️ cambia al ID real del caso "Producto duplicado"
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

// === Ejecutar ===
(async () => {
  console.log('🚀 Iniciando prueba: Registrar producto duplicado...');
  await runTestRegistrarProductoDuplicado();
  console.log('✅ Prueba finalizada.');
})();
