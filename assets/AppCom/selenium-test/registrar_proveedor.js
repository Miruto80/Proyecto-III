// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-11'; // ⚙️ tu ID real en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Registrar Proveedor ===
async function runTest() {
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === PASO 1: LOGIN ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);

    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === PASO 2: IR A PROVEEDOR ===
    console.log('📂 Navegando al módulo Proveedor...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor');
    await driver.wait(until.urlContains('pagina=proveedor'), 10000);
    console.log('✅ Página de proveedor cargada.');

    // === PASO 3: ABRIR MODAL DE REGISTRO ===
    console.log('🪟 Abriendo modal de registro...');
    const btnRegistrar = await driver.findElement(By.id('btnAbrirRegistrar'));
    await driver.executeScript('arguments[0].click();', btnRegistrar);
    await driver.wait(until.elementLocated(By.id('formProveedor')), 5000);
    console.log('✅ Modal abierto correctamente.');

    // === PASO 4: LLENAR FORMULARIO ===
    console.log('✍️ Ingresando datos del proveedor...');
    await driver.findElement(By.id('tipo_documento')).sendKeys('V');
    await driver.findElement(By.id('numero_documento')).sendKeys('30753995');
    await driver.findElement(By.id('nombre')).sendKeys('Rhichard Virguez');
    await driver.findElement(By.id('correo')).sendKeys('virguezrhichard11@gmail.com');
    await driver.findElement(By.id('telefono')).sendKeys('04245071950');
    await driver.findElement(By.id('direccion')).sendKeys('Cabudare, tierra del sol 3');

    // === PASO 5: HACER CLIC EN REGISTRAR ===
    console.log('💾 Registrando proveedor...');
    const btnEnviar = await driver.findElement(By.id('btnEnviar'));
    await driver.executeScript('arguments[0].click();', btnEnviar);

    // === PASO 6: ESPERAR ALERTA DE ÉXITO ===
    console.log('⏳ Esperando confirmación...');
    await driver.wait(
      until.elementLocated(By.xpath("//*[contains(text(),'Proveedor') and contains(text(),'registrado')]")),
      10000
    );

    console.log('🎉 Proveedor registrado con éxito.');
    notes = 'El sistema registró el proveedor correctamente y mostró la alerta de éxito.';
    status = 'p';

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.sleep(2000);
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === FUNCIÓN: REPORTAR RESULTADO A TESTLINK ===
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

// === EJECUTAR TEST ===
runTest();

