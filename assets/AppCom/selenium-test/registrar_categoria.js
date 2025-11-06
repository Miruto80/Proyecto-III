// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';  
const TEST_CASE_EXTERNAL_ID = 'R-1-1'; 
const TEST_PLAN_ID = 2; 
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 8000);
    console.log('✅ Login exitoso');

    console.log('📂 Abriendo Categoría...');
    let categoriaBtn = await driver.findElement(By.xpath("//*[contains(text(),'Categ')]"));
    await driver.executeScript("arguments[0].click()", categoriaBtn);
    await driver.wait(until.urlContains('pagina=categoria'), 8000);
    console.log('✅ Página de categoría abierta');

    console.log('🪟 Abriendo modal registrar...');
    await driver.findElement(By.id('btnAbrirRegistrar')).click();
    await driver.sleep(800);
    console.log('✅ Modal abierto');

    console.log('📝 Digitando nombre...');
    let inputNombre = await driver.findElement(By.id("nombre"));
    await inputNombre.sendKeys('Polvo');
    
    console.log('💾 Guardando...');
    let btnEnviar = await driver.findElement(By.id("btnEnviar"));
    await driver.executeScript("arguments[0].click()", btnEnviar);

    console.log('⏳ Esperando confirmación...');
    await driver.wait(
      until.elementLocated(By.xpath("//*[contains(text(),'Registrad') or contains(text(),'exitos')]")),
      8000
    );

    console.log('🎉 Categoría registrada con éxito');
    notes = '✅ Registro exitoso';
    status = 'p';

  } catch (e) {
    console.error('❌ Error:', e.message);
    notes = 'Error: ' + e.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === Reportar a TestLink ===
async function reportResultToTestLink(status, notes) {
  const client = xmlrpc.createClient({ url: TESTLINK_URL });

  client.methodCall('tl.reportTCResult', [{
    devKey: DEV_KEY,
    testcaseexternalid: TEST_CASE_EXTERNAL_ID,
    testplanid: TEST_PLAN_ID,
    buildname: BUILD_NAME,
    notes: notes.replace(/<br>/gi, "\n"),
    status: status,
  }], function (error, value) {
    if (error) console.error('⚠️ Error TestLink:', error);
    else console.log('📤 Resultado enviado:', value);
  });
}

runTest();
