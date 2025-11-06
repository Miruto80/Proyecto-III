// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-18'; // ID del caso en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al registrar proveedor con caracter inválido ===
async function runTest() {
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // --- 1) Login
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // --- 2) Ir a Proveedor
    console.log('📂 Navegando al módulo Proveedor...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor');
    await driver.wait(until.urlContains('pagina=proveedor'), 10000);
    console.log('✅ Página de proveedor cargada.');

    // --- 3) Abrir modal Registrar
    console.log('🪟 Abriendo modal de registro...');
    const btnRegistrar = await driver.wait(until.elementLocated(By.id('btnAbrirRegistrar')), 8000);
    await driver.executeScript('arguments[0].click();', btnRegistrar);
    await driver.wait(until.elementLocated(By.id('formProveedor')), 5000);
    console.log('✅ Modal abierto.');

    // --- 4) Llenar formulario con datos inválidos
    console.log('✍️ Llenando formulario con datos inválidos...');
    // Tipo V
    await driver.findElement(By.id('tipo_documento')).sendKeys('V');
    await driver.findElement(By.id('numero_documento')).sendKeys('30753995');
    // Nombre con carácter inválido (número)
    await driver.findElement(By.id('nombre')).sendKeys('Rhichard Virgue3');
    // Correo inválido (sin @)
    await driver.findElement(By.id('correo')).sendKeys('virguezrhichard11gmail.com');
    await driver.findElement(By.id('telefono')).sendKeys('04245071950');
    await driver.findElement(By.id('direccion')).sendKeys('Cabudare, tierra del sol 3');

    // --- 5) Pulsar Registrar
    console.log('💾 Pulsando Registrar...');
    const btnEnviar = await driver.findElement(By.id('btnEnviar'));
    await driver.executeScript('arguments[0].click();', btnEnviar);

    // --- 6) Validaciones:
    // Esperar SWEETALERT de error (muestraMensaje -> Swal.fire con icon 'error')
    console.log('⏳ Esperando SweetAlert de error...');
    let swalVisible = false;
    try {
      const swal = await driver.wait(until.elementLocated(By.css('.swal2-popup')), 8000);
      await driver.wait(until.elementIsVisible(swal), 5000);
      const swalText = await swal.getText();
      console.log('🚨 SweetAlert texto:', swalText);
      if (swalText && swalText.toLowerCase().includes('por favor')) swalVisible = true;
    } catch (e) {
      // no se encontró swal, continuamos a validar mensajes de campo
      console.log('ℹ️ No se detectó SweetAlert dentro del timeout.');
    }

    // Validar mensajes debajo de inputs: #snombre y #scorreo
    console.log('🔎 Comprobando mensajes de validación debajo de inputs...');
    const spanNombre = await driver.findElement(By.id('snombre'));
    const spanCorreo = await driver.findElement(By.id('scorreo'));

    const txtNombre = (await spanNombre.getText()).trim();
    const txtCorreo = (await spanCorreo.getText()).trim();
    console.log('snombre:', txtNombre || '<vacío>');
    console.log('scorreo:', txtCorreo || '<vacío>');

    // Criterio de éxito: aparece SweetAlert de error OR al menos uno de los spans contiene mensaje
    if (swalVisible || txtNombre.length > 0 || txtCorreo.length > 0) {
      console.log('✅ El sistema mostró la validación esperada (SweetAlert y/o mensajes bajo inputs).');
      notes = `Se mostró validación. swalVisible=${swalVisible}, snombre="${txtNombre}", scorreo="${txtCorreo}"`;
      status = 'p';
    } else {
      throw new Error('No se detectó SweetAlert ni mensajes de validación debajo de los inputs.');
    }

    // Dar tiempo para que se aprecie visualmente
    await driver.sleep(4000);

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === Reportar resultado a TestLink ===
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
    client.methodCall('tl.reportTCResult', [params], function (error, value) {
      if (error) console.error('⚠️ Error al enviar resultado a TestLink:', error);
      else console.log('📤 Resultado enviado a TestLink:', value);
    });
  } catch (err) {
    console.error('⚠️ No se pudo conectar con TestLink:', err);
  }
}

// === Ejecutar test ===
runTest();
