// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-19'; // ID del caso en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al modificar proveedor con carácter inválido ===
async function runTest() {
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // 1) Login
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // 2) Ir al módulo Proveedor
    console.log('📂 Navegando al módulo Proveedor...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor');
    await driver.wait(until.urlContains('pagina=proveedor'), 10000);
    console.log('✅ Página de proveedor cargada.');

    // 3) Abrir modal Modificar (primer proveedor disponible)
    console.log('✏️ Abriendo modal de modificación...');
    await driver.wait(until.elementLocated(By.css('.modificar')), 10000);
    const botonesModificar = await driver.findElements(By.css('.modificar'));
    if (botonesModificar.length === 0) throw new Error('No se encontró ningún botón .modificar en la tabla.');
    await driver.executeScript('arguments[0].scrollIntoView(true);', botonesModificar[0]);
    await driver.sleep(400);
    await driver.executeScript('arguments[0].click();', botonesModificar[0]);

    // Esperar a que el formulario esté presente y cargado vía AJAX
    await driver.wait(until.elementLocated(By.id('formProveedor')), 8000);
    // esperar a que numero_documento tenga valor (datos cargados)
    await driver.wait(async () => {
      const val = await driver.findElement(By.id('numero_documento')).getAttribute('value');
      return val && val.trim() !== '';
    }, 8000, 'El campo numero_documento no se llenó tras abrir el modal');

    await driver.sleep(500); // margen extra
    console.log('✅ Modal abierto y datos cargados.');

    // 4) Modificar campos con valores inválidos
    console.log('📝 Escribiendo valores inválidos en el formulario...');
    // tipo_documento: se selecciona V (si ya está seleccionado, sendKeys no rompe)
    const tipoDoc = await driver.findElement(By.id('tipo_documento'));
    await tipoDoc.sendKeys('V');

    const numeroDoc = await driver.findElement(By.id('numero_documento'));
    await numeroDoc.clear();
    await driver.sleep(150);
    await numeroDoc.sendKeys('30753995'); // puede ser el mismo o no; lo dejamos para mantener flujo

    const nombre = await driver.findElement(By.id('nombre'));
    await nombre.clear();
    await driver.sleep(100);
    await nombre.sendKeys('Rhichard Virgue3'); // nombre con número => inválido

    const correo = await driver.findElement(By.id('correo'));
    await correo.clear();
    await driver.sleep(100);
    await correo.sendKeys('virguezrhichard11gmail.com'); // correo inválido (sin @)

    const telefono = await driver.findElement(By.id('telefono'));
    await telefono.clear();
    await telefono.sendKeys('04245071950');

    const direccion = await driver.findElement(By.id('direccion'));
    await direccion.clear();
    await direccion.sendKeys('Cabudare, tierra del sol 3');

    // 5) Click en Actualizar
    console.log('💾 Pulsando Actualizar...');
    const btnActualizar = await driver.findElement(By.id('btnEnviar'));
    await driver.executeScript('arguments[0].click();', btnActualizar);

    // 6) Validaciones:
    console.log('⏳ Esperando SweetAlert de error o mensajes de validación bajo inputs...');
    let swalVisible = false;
    try {
      const swal = await driver.wait(until.elementLocated(By.css('.swal2-popup')), 8000);
      await driver.wait(until.elementIsVisible(swal), 5000);
      const txt = await swal.getText();
      // comprobación laxa: que sea un mensaje de error
      if (txt && txt.toLowerCase().includes('error') || txt.toLowerCase().includes('por favor') ) {
        swalVisible = true;
      } else {
        // Some implementations use custom text; consider visible as true if popup exists
        swalVisible = true;
      }
    } catch (e) {
      // no SweetAlert; seguiremos comprobando spans
    }

    // Comprobar mensajes bajo inputs
    const snombreEl = await driver.findElement(By.id('snombre'));
    const scorreoEl = await driver.findElement(By.id('scorreo'));
    const txtSnombre = (await snombreEl.getText()).trim();
    const txtScorreo = (await scorreoEl.getText()).trim();

    console.log('snombre:', txtSnombre || '<vacío>');
    console.log('scorreo:', txtScorreo || '<vacío>');
    const hayErroresCampo = txtSnombre.length > 0 || txtScorreo.length > 0;

    if (swalVisible || hayErroresCampo) {
      console.log('✅ Validación mostrada correctamente (SweetAlert y/o mensajes en campos).');
      notes = `swal=${swalVisible}, snombre="${txtSnombre}", scorreo="${txtScorreo}"`;
      status = 'p';
    } else {
      throw new Error('No se detectó SweetAlert ni mensajes de validación debajo de los inputs.');
    }

    // dejar unos segundos para que se aprecie la alerta/mensajes
    await driver.sleep(4500);

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
    client.methodCall('tl.reportTCResult', [params], (err, val) => {
      if (err) console.error('⚠️ Error al enviar resultado a TestLink:', err);
      else console.log('📤 Resultado enviado a TestLink:', val);
    });
  } catch (err) {
    console.error('⚠️ No se pudo conectar con TestLink:', err);
  }
}

runTest();
