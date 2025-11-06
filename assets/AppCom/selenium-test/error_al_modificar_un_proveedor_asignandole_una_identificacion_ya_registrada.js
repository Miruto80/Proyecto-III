// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-17';
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO ===
async function runTest() {
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);

    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    console.log('📂 Navegando al módulo Proveedor...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor');
    await driver.wait(until.urlContains('pagina=proveedor'), 10000);
    console.log('✅ Página de proveedor cargada.');

    // Esperar a que haya registros en la tabla
    await driver.wait(until.elementLocated(By.css('.modificar')), 8000);

    // Clic en el primer botón de modificar
    console.log('✏️ Abriendo modal de modificación...');
    const botonesModificar = await driver.findElements(By.css('.modificar'));
    await driver.executeScript('arguments[0].click();', botonesModificar[0]);

    // Esperar a que el campo numero_documento tenga valor (AJAX completado)
    await driver.wait(async () => {
      const value = await driver.findElement(By.id('numero_documento')).getAttribute('value');
      return value && value.trim() !== '';
    }, 8000, 'El campo numero_documento no se llenó después de abrir el modal');

    console.log('✅ Modal abierto y datos cargados.');

    // --- Modificar los datos ---
    const tipoDoc = await driver.findElement(By.id('tipo_documento'));
    const numeroDoc = await driver.findElement(By.id('numero_documento'));
    const nombre = await driver.findElement(By.id('nombre'));
    const correo = await driver.findElement(By.id('correo'));
    const telefono = await driver.findElement(By.id('telefono'));
    const direccion = await driver.findElement(By.id('direccion'));

    console.log('📝 Modificando datos del proveedor...');

    // Cambiar Tipo Documento
    await tipoDoc.sendKeys('V');
    await driver.sleep(500);

    // Cambiar número documento a uno existente
    await numeroDoc.clear();
    await driver.sleep(200);
    await numeroDoc.sendKeys('30753995'); // Documento ya registrado

    // Refrescar nombre
    await nombre.clear();
    await nombre.sendKeys('Rhichard Virguez');
    await correo.clear();
    await correo.sendKeys('virguezrhichard11@gmail.com');
    await telefono.clear();
    await telefono.sendKeys('04245071950');
    await direccion.clear();
    await direccion.sendKeys('Cabudare, tierra del sol 3');

    // Clic en el botón de actualizar
    console.log('💾 Intentando guardar proveedor duplicado...');
    const btnActualizar = await driver.findElement(By.id('btnEnviar'));
    await driver.executeScript('arguments[0].click();', btnActualizar);

    // Esperar el mensaje de error (SweetAlert)
    console.log('⏳ Esperando mensaje de error...');
    await driver.wait(
      until.elementLocated(By.xpath("//*[contains(text(),'Ya existe') or contains(text(),'proveedor registrado')]")),
      10000
    );

    console.log('❗ Mensaje de error mostrado correctamente.');
    status = 'p';
    notes = 'El sistema mostró correctamente el mensaje de proveedor duplicado.';

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.sleep(4000); // pequeña pausa para ver la alerta
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === REPORTAR RESULTADO A TESTLINK ===
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
  client.methodCall('tl.reportTCResult', [params], (err, val) => {
    if (err) console.error('⚠️ Error al enviar resultado a TestLink:', err);
    else console.log('📤 Resultado enviado a TestLink:', val);
  });
}

runTest();
