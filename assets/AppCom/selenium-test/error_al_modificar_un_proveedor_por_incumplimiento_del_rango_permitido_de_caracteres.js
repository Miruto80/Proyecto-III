// error_modificar_proveedor_rango_caracteres.js
// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-21'; // Ajusta si tu ID es otro
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

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

    // 2) Ir a Proveedor
    console.log('📂 Navegando al módulo Proveedor...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=proveedor');
    await driver.wait(until.urlContains('pagina=proveedor'), 10000);
    console.log('✅ Página de proveedor cargada.');

    // 3) Abrir modal Modificar (primer proveedor disponible)
    console.log('✏️ Abriendo modal de modificación...');
    await driver.wait(until.elementLocated(By.css('.modificar')), 10000);
    const botonesModificar = await driver.findElements(By.css('.modificar'));
    if (botonesModificar.length === 0) throw new Error('No se encontró ningún botón .modificar en la tabla.');
    // scroll & click via JS
    await driver.executeScript('arguments[0].scrollIntoView(true);', botonesModificar[0]);
    await driver.sleep(300);
    await driver.executeScript('arguments[0].click();', botonesModificar[0]);

    // 4) Esperar modal y carga AJAX (numero_documento debe llenarse)
    await driver.wait(until.elementLocated(By.id('formProveedor')), 8000);
    await driver.wait(async () => {
      try {
        const val = await driver.findElement(By.id('numero_documento')).getAttribute('value');
        return val && val.trim() !== '';
      } catch (e) {
        return false;
      }
    }, 10000, 'El campo numero_documento no se llenó tras abrir el modal');
    await driver.sleep(400); // margen extra
    console.log('✅ Modal abierto y datos iniciales cargados.');

    // 5) Sobrescribir campos con valores fuera del rango
    console.log('📝 Escribiendo valores fuera del rango en el formulario...');
    const tipoDoc = await driver.findElement(By.id('tipo_documento'));
    const numeroDoc = await driver.findElement(By.id('numero_documento'));
    const nombre = await driver.findElement(By.id('nombre'));
    const correo = await driver.findElement(By.id('correo'));
    const telefono = await driver.findElement(By.id('telefono'));
    const direccion = await driver.findElement(By.id('direccion'));

    // Tipo documento (sendKeys no rompe si ya tiene valor)
    await tipoDoc.sendKeys('V');
    // Número de documento (mantener o reescribir)
    await numeroDoc.clear();
    await driver.sleep(150);
    await numeroDoc.sendKeys('30753995');

    // Valores inválidos / fuera de rango
    await nombre.clear();
    await driver.sleep(100);
    await nombre.sendKeys('Rh'); // muy corto (menos de 3)

    await correo.clear();
    await driver.sleep(100);
    await correo.sendKeys('virguezrhichard11gmail.com'); // sin @

    await telefono.clear();
    await driver.sendKeys ? await telefono.sendKeys('04245') : await telefono.sendKeys('04245'); // incompleto

    // dejar dirección vacía (para probar campo obligatorio)
    await direccion.clear();

    // 6) Click en Actualizar
    console.log('💾 Pulsando Actualizar...');
    const btnActualizar = await driver.findElement(By.id('btnEnviar'));
    await driver.executeScript('arguments[0].click();', btnActualizar);

    // 7) Validaciones:
    console.log('⏳ Esperando SweetAlert de error o mensajes de validación bajo inputs...');
    let swalVisible = false;
    try {
      const swal = await driver.wait(until.elementLocated(By.css('.swal2-popup')), 7000);
      await driver.wait(until.elementIsVisible(swal), 4000);
      // si aparece popup, lo consideramos error
      swalVisible = true;
      console.log('🔔 SweetAlert detectado.');
    } catch (e) {
      // no apareció SweetAlert, seguimos a validar spans
      console.log('ℹ️ No se detectó SweetAlert dentro del timeout, se comprobarán mensajes en campos.');
    }

    // Comprobar mensajes debajo de los inputs (si existen)
    const getSpanText = async (id) => {
      try {
        const el = await driver.findElement(By.id(id));
        const txt = await el.getText();
        return txt ? txt.trim() : '';
      } catch (e) {
        return '';
      }
    };

    const txtSnombre = await getSpanText('snombre');
    const txtScorreo = await getSpanText('scorreo');
    const txtStelefono = await getSpanText('stelefono');
    const txtSdireccion = await getSpanText('sdireccion');

    console.log('snombre:', txtSnombre || '<vacío>');
    console.log('scorreo:', txtScorreo || '<vacío>');
    console.log('stelefono:', txtStelefono || '<vacío>');
    console.log('sdireccion:', txtSdireccion || '<vacío>');

    const hayErroresCampo = txtSnombre.length > 0 || txtScorreo.length > 0 || txtStelefono.length > 0 || txtSdireccion.length > 0;

    if (swalVisible || hayErroresCampo) {
      console.log('✅ Validación mostrada correctamente (SweetAlert y/o mensajes en campos).');
      notes = `swal=${swalVisible}, snombre="${txtSnombre}", scorreo="${txtScorreo}", stelefono="${txtStelefono}", sdireccion="${txtSdireccion}"`;
      status = 'p';
    } else {
      throw new Error('No se detectó SweetAlert ni mensajes de validación debajo de los inputs.');
    }

    // dejar unos segundos para visualizar la validación
    await driver.sleep(4500);

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === Reportar a TestLink ===
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
