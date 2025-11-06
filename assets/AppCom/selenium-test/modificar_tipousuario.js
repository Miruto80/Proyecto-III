// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-23'; 
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: MODIFICAR TIPO DE USUARIO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(2000);

    console.log('✏️ Ingresando credenciales...');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');

    console.log('🖱️ Ingresando al sistema...');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);

    console.log('✅ Login exitoso. Navegando a Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.urlContains('pagina=tipousuario'), 10000);

    console.log('✅ Página Tipo Usuario cargada. Buscando botón válido...');
    // Buscar botón de modificar distinto del id=2
    const btnModificar = await driver.wait(
      until.elementLocated(By.css('.modificar[data-id]:not([data-id="2"])')),
      10000
    );
    await btnModificar.click();

    // Esperar a que aparezca el modal
    await driver.wait(until.elementLocated(By.id('nombre_modificar')), 10000);
    await driver.sleep(1000);

    console.log('📝 Modificando datos...');
    const selectEstatus = await driver.findElement(By.id('estatus_modificar'));
    await selectEstatus.click();
    await driver.findElement(By.css('#estatus_modificar option[value="2"]')).click();

    await driver.sleep(500);

    console.log('💾 Haciendo clic en "ACTUALIZAR"...');
    await driver.findElement(By.id('btnModificar')).click();

    console.log('⏳ Esperando confirmación...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 10000);
    const mensaje = await driver.findElement(By.css('.swal2-title')).getText();

    if (mensaje.includes('Rol modificado con éxito')) {
      console.log('✅ Prueba exitosa: Rol modificado correctamente.');
      notes = 'Modificación de tipo de usuario completada con éxito.';
      status = 'p';
    } else {
      throw new Error('El mensaje de éxito no apareció o fue diferente.');
    }

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.sleep(1500);
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
      testcaseexternalid: TEST_CASE_EXTERNAL_ID,
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

// === Ejecutar prueba ===
runTest();
