// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-36'; // 🔹 Caso 1-36
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Marcar notificación como leída ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(1500);

    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 2: Ir al módulo Notificaciones ===
    console.log('📂 Navegando al módulo Notificaciones...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=notificacion');
    await driver.wait(until.elementLocated(By.id('notif-body')), 10000);
    console.log('✅ Página de Notificaciones cargada.');

    // === Paso 3: Buscar botón de "Marcar como leída" ===
    console.log('🔎 Buscando botón de acción...');
    let botones = await driver.findElements(By.css('.btn-action[data-accion="marcarLeida"]'));

    if (botones.length === 0) {
      throw new Error('No hay notificaciones con botón "Marcar como leída" disponibles.');
    }

    // Tomar la primera notificación disponible
    const boton = botones[0];
    console.log('📩 Notificación encontrada. Intentando marcar como leída...');
    await boton.click();

    // === Paso 4: Confirmar alerta de SweetAlert ===
    console.log('🖱️ Confirmando acción en SweetAlert...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup.swal2-modal')), 10000);
    await driver.sleep(500);
    await driver.findElement(By.css('.swal2-confirm')).click();

    // === Paso 5: Esperar mensaje de éxito ===
    console.log('⏳ Esperando mensaje de confirmación...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup.swal2-modal')), 10000);

    const alerta = await driver.findElement(By.css('.swal2-html-container'));
    const mensaje = await alerta.getText();
    console.log('📢 Mensaje mostrado:', mensaje);

    if (
      mensaje.includes('¡Listo!') ||
      mensaje.includes('marcada como leída') ||
      mensaje.includes('Notificación actualizada')
    ) {
      console.log('✅ Prueba exitosa: se marcó la notificación como leída.');
      status = 'p';
      notes = 'La notificación fue marcada como leída correctamente.';
    } else {
      throw new Error('No se mostró el mensaje de éxito esperado.');
    }

    // Esperar un poco para ver efecto visual
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

// === FUNCIÓN PARA REPORTAR RESULTADO A TESTLINK ===
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
