// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-13'; // Error al registrar categoría vacía
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === LOGIN ===
    console.log('🧭 Navegando al formulario de login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 8000);

    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 8000);
    console.log('✅ Login exitoso');

    // === CATEGORÍA ===
    console.log('📂 Abriendo módulo Categoría...');
    let categoriaBtn = await driver.findElement(By.xpath("//*[contains(text(),'Categ')]"));
    await driver.executeScript("arguments[0].click();", categoriaBtn);
    await driver.wait(until.urlContains('pagina=categoria'), 8000);
    console.log('✅ Página de categoría cargada');

    // === MODAL ===
    console.log('🪟 Abriendo modal de registro...');
    await driver.findElement(By.id('btnAbrirRegistrar')).click();
    await driver.sleep(1000);

    console.log('⚠️ Intentando registrar con campo vacío...');
    let btnRegistrarModal = await driver.findElement(
      By.xpath("(//button[contains(text(),'Registrar') or contains(.,'Registrar')])[last()]")
    );
    await driver.executeScript("arguments[0].click();", btnRegistrarModal);

    // === ESPERAR SWEETALERT ===
    console.log('⏳ Esperando alerta de validación...');
    let alerta = await driver.wait(
      until.elementLocated(By.xpath("//div[contains(@class,'swal2-popup')]")),
      8000
    );
    let textoAlerta = await alerta.getText();
    console.log('🚨 Alerta mostrada:', textoAlerta);

    // Esperar unos segundos para que se aprecie visualmente
    await driver.sleep(3000);

    // === VALIDAR EL MENSAJE ===
    if (
      textoAlerta.toLowerCase().includes('datos inválidos') ||
      textoAlerta.toLowerCase().includes('error')
    ) {
      console.log('✅ El sistema mostró correctamente la alerta por campos vacíos.');
      notes = 'El sistema mostró la alerta "Datos inválidos" al intentar registrar sin llenar el campo.';
      status = 'p';
    } else {
      throw new Error('El mensaje mostrado no fue el esperado ("Datos inválidos").');
    }

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === REPORTAR A TESTLINK ===
async function reportResultToTestLink(status, notes) {
  const client = xmlrpc.createClient({ url: TESTLINK_URL });
  const params = {
    devKey: DEV_KEY,
    testcaseexternalid: TEST_CASE_EXTERNAL_ID,
    testplanid: TEST_PLAN_ID,
    buildname: BUILD_NAME,
    notes,
    status,
  };

  client.methodCall('tl.reportTCResult', [params], function (err, val) {
    if (err) console.error('⚠️ Error TestLink:', err);
    else console.log('📤 Resultado enviado a TestLink:', val);
  });
}

runTest();
