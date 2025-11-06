// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-12'; 
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

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

    // === ABRIR MODAL MODIFICAR ===
    console.log('✏️ Abriendo modal de modificación...');
    const btnModificar = await driver.wait(until.elementLocated(By.css('.modificar')), 8000);
    await driver.executeScript('arguments[0].scrollIntoView(true);', btnModificar);
    await driver.sleep(500);
    await driver.executeScript('arguments[0].click();', btnModificar);

    // Esperar a que el formulario esté visible y activo
    await driver.wait(until.elementIsVisible(await driver.findElement(By.id('formProveedor'))), 8000);
    console.log('✅ Modal de modificar abierto.');
    await driver.sleep(800); // deja que se inicialice el modal

    // === MODIFICAR DATOS ===
    console.log('📝 Modificando datos del proveedor...');

    const tipoDoc = await driver.wait(until.elementLocated(By.id('tipo_documento')), 5000);
    await driver.executeScript("arguments[0].focus();", tipoDoc);
    await tipoDoc.sendKeys('J');

    const numDoc = await driver.wait(until.elementIsVisible(await driver.findElement(By.id('numero_documento'))), 5000);
    await numDoc.clear();
    await numDoc.sendKeys('3075399507');

    const nombre = await driver.wait(until.elementIsVisible(await driver.findElement(By.id('nombre'))), 5000);
    await nombre.clear();
    await nombre.sendKeys('Rhichard Virguez');

    const correo = await driver.findElement(By.id('correo'));
    await correo.clear();
    await correo.sendKeys('virguezrhichard11@gmail.com');

    const telefono = await driver.findElement(By.id('telefono'));
    await telefono.clear();
    await telefono.sendKeys('04245071950');

    const direccion = await driver.findElement(By.id('direccion'));
    await direccion.clear();
    await direccion.sendKeys('Cabudare, tierra del sol 3');

    // === CLIC EN ACTUALIZAR ===
    console.log('💾 Actualizando proveedor...');
    const btnEnviar = await driver.findElement(By.id('btnEnviar'));
    await driver.executeScript('arguments[0].click();', btnEnviar);

    // === ESPERAR ALERTA DE ÉXITO ===
    console.log('⏳ Esperando mensaje de éxito...');
    await driver.wait(
      until.elementLocated(By.xpath("//*[contains(text(),'Proveedor') and contains(text(),'modificado')]")),
      10000
    );

    console.log('🎉 Proveedor modificado con éxito.');
    notes = 'Proveedor modificado correctamente y se mostró el mensaje de éxito.';
    status = 'p';

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.sleep(4000); // deja ver el mensaje de Swal
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === REPORTAR RESULTADO A TESTLINK ===
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

runTest();
