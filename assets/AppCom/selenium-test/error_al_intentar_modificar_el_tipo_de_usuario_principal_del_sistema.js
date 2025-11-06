// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-29'; // 🔹 Código de prueba 1-29
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al intentar modificar el tipo usuario principal ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(2000);

    // === LOGIN ===
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    // Esperar redirección
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Ir al módulo Tipo Usuario ===
    console.log('📂 Navegando al módulo Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.elementLocated(By.id('myTable')), 10000);
    console.log('✅ Página de Tipo Usuario cargada.');

    // === Buscar botón de modificar del tipo usuario principal (id = 2) ===
    console.log('🔎 Buscando el botón del tipo usuario principal...');
    const botones = await driver.findElements(By.css('.modificar'));
    let botonAdministrador = null;

    for (const btn of botones) {
      const id = await btn.getAttribute('data-id');
      if (id === '2') {
        botonAdministrador = btn;
        break;
      }
    }

    if (!botonAdministrador) throw new Error('No se encontró el botón para el tipo usuario principal (ID 2).');

    // === Intentar abrir modal de modificación ===
    console.log('🖱️ Intentando modificar el tipo usuario principal...');
    await botonAdministrador.click();

    // Esperar y leer SweetAlert2
    console.log('⏳ Esperando alerta SweetAlert2...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup.swal2-modal')), 5000);
    await driver.sleep(800);

    const alertaElemento = await driver.findElement(By.css('.swal2-html-container'));
    const alertaTexto = await alertaElemento.getText();
    console.log('📢 Mensaje mostrado:', alertaTexto);

    if (
      alertaTexto.includes('Acción no permitida') ||
      alertaTexto.includes('no puede modificarse') ||
      alertaTexto.includes('Administrador')
    ) {
      console.log('✅ Validación correcta: se mostró mensaje de restricción para el rol Administrador.');
      status = 'p';
      notes = 'El sistema impidió correctamente la modificación del rol principal (Administrador).';
    } else {
      throw new Error('⚠️ No se mostró el mensaje esperado de "Acción no permitida".');
    }

    // Cerrar alerta con ESC
    const body = await driver.findElement(By.css('body'));
    await body.sendKeys('\uE00C');
    await driver.sleep(1000);

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
    status = 'f';
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === FUNCIÓN PARA REPORTAR RESULTADOS A TESTLINK ===
async function reportResultToTestLink(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });

    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: TEST_CASE_EXTERNAL_ID,
      testplanid: TEST_PLAN_ID,
      buildname: BUILD_NAME,
      notes: notes,
      status: status
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
