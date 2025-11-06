// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-14'; // Error al modificar con inputs vacíos
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === LOGIN ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 8000);

    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 8000);
    console.log('✅ Login exitoso');

    // === ABRIR CATEGORÍA ===
    console.log('📂 Abriendo módulo Categoría...');
    let categoriaBtn = await driver.findElement(By.xpath("//*[contains(text(),'Categ')]"));
    await driver.executeScript("arguments[0].click();", categoriaBtn);
    await driver.wait(until.urlContains('pagina=categoria'), 8000);
    console.log('✅ Página de categoría cargada');

    // === ABRIR MODAL DE MODIFICACIÓN ===
    console.log('✏️ Abriendo modal de modificación...');
    let btnModificar = await driver.findElement(By.css('.btnModif'));
    await driver.executeScript("arguments[0].click();", btnModificar);
    await driver.sleep(1000);

    console.log('✅ Modal de edición abierto');

    // === LIMPIAR EL CAMPO ===
    console.log('🧹 Borrando el nombre de la categoría...');
    let inputNombre = await driver.findElement(By.id('nombre'));
    await inputNombre.clear();

    // === INTENTAR ACTUALIZAR ===
    console.log('💾 Intentando actualizar con campo vacío...');
    let btnActualizar = await driver.findElement(
      By.xpath("//button[contains(text(),'Actualizar') or contains(.,'Actualizar')]")
    );
    await driver.executeScript("arguments[0].click();", btnActualizar);

    // === ESPERAR ALERTA SWEETALERT ===
    console.log('⏳ Esperando alerta de error...');
    let alerta = await driver.wait(
      until.elementLocated(By.xpath("//div[contains(@class,'swal2-popup')]")),
      8000
    );
    let textoAlerta = await alerta.getText();
    console.log('🚨 Alerta mostrada:', textoAlerta);

    // Esperar unos segundos para que se vea visualmente
    await driver.sleep(3000);

    // === VALIDAR ALERTA ===
    if (
      textoAlerta.toLowerCase().includes('datos inválidos') ||
      textoAlerta.toLowerCase().includes('error')
    ) {
      console.log('✅ El sistema mostró correctamente la alerta por campo vacío al modificar.');
      notes = 'El sistema mostró la alerta "Datos inválidos" al intentar modificar sin llenar el campo.';
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

// === EJECUTAR ===
runTest();
