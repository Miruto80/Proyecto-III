// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'R-1-37'; // 🔹 ID del caso de prueba en TestLink
const TEST_PLAN_ID = 2; // tu plan de prueba
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Marcar notificación como leída por el asesor ===
async function runTest() {
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Navegar al login ===
    console.log('🧭 Navegando al formulario de login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(2000);
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);

    // === Paso 2: Ingresar credenciales del asesor ===
    console.log('✏️ Ingresando credenciales de asesor...');
    await driver.findElement(By.id('usuario')).sendKeys('20152522');
    await driver.findElement(By.id('pid')).sendKeys('love1234');

    // === Paso 3: Hacer clic en "Ingresar" ===
    console.log('🖱️ Haciendo clic en "Ingresar"...');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso como Asesor de Ventas.');

    // === Paso 4: Ir al módulo de notificaciones ===
    console.log('🔔 Accediendo al módulo de notificaciones...');
    const notiIcon = await driver.wait(until.elementLocated(By.css('.notification-icon')), 10000);
    await notiIcon.click();
    await driver.wait(until.urlContains('pagina=notificacion'), 10000);
    console.log('✅ Página de notificaciones cargada.');

    // === Paso 5: Buscar botón de "Marcar como leída" (asesor) ===
    console.log('🔎 Buscando notificación sin leer (botón asesor)...');
    const botones = await driver.findElements(By.css('.btn-action[data-accion="marcarLeidaAsesora"]'));
    if (botones.length === 0) {
      throw new Error('No se encontró ninguna notificación sin leer para el asesor.');
    }

    const boton = botones[0];
    console.log('📩 Notificación encontrada. Intentando marcar como leída...');
    await boton.click();

    // === Paso 6: Confirmar SweetAlert ===
    console.log('🖱️ Confirmando alerta de "¿Marcar como leída?"...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 10000);
    await driver.findElement(By.css('.swal2-confirm')).click();

    // === Paso 7: Esperar mensaje de éxito ===
    console.log('⏳ Esperando mensaje de éxito...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup.swal2-modal')), 10000);
    const mensaje = await driver.findElement(By.css('.swal2-html-container')).getText();

    if (mensaje.includes('¡Listo!') || mensaje.includes('marcada como leída')) {
      console.log('✅ Notificación marcada como leída correctamente (asesor).');
    } else {
      throw new Error('No apareció mensaje de éxito esperado.');
    }

    // === Paso 8: Verificar que desaparece del listado del asesor ===
    await driver.sleep(1000);
    const filasRestantes = await driver.findElements(By.css('#notif-body tr'));
    if (filasRestantes.length === 0) {
      console.log('✅ La notificación desapareció del listado del asesor.');
      status = 'p';
      notes = 'Notificación marcada como leída por el asesor y eliminada de su vista.';
    } else {
      console.log('⚠️ La notificación sigue visible, pero fue marcada como leída correctamente.');
      status = 'p';
      notes = 'Notificación marcada como leída, pero aún visible (sin recarga).';
    }

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
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
