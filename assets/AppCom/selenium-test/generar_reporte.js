// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-34'; // ID del caso en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST: Generar Reporte (cualquier card) ===
async function runTest() {
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 2: Ir al módulo Reporte ===
    console.log('📂 Navegando al módulo Reporte...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=reporte');
    await driver.wait(until.urlContains('pagina=reporte'), 10000);
    console.log('✅ Página de Reporte cargada.');

    // === Paso 3: Seleccionar cualquier card ===
    console.log('🖱️ Seleccionando un reporte cualquiera...');
    const cards = await driver.findElements(By.css('.card .btn.report-btn'));
    if (cards.length === 0) throw new Error('No se encontraron cards de reporte.');

    const botonReporte = cards[0];
    await driver.executeScript('arguments[0].scrollIntoView(true);', botonReporte);
    await driver.sleep(300);
    await botonReporte.click();

    await driver.wait(until.elementLocated(By.css('.modal.show form.report-form')), 7000);
    console.log('✅ Modal de Reporte abierto.');

    // === Paso 4: Click en “GENERAR PDF” ===
    console.log('📄 Clic en GENERAR PDF...');
    const btnPDF = await driver.findElement(
      By.xpath("//div[contains(@class,'modal') and contains(@class,'show')]//button[contains(.,'GENERAR PDF')]")
    );
    await driver.executeScript('arguments[0].scrollIntoView(true);', btnPDF);
    await driver.sleep(400);
    await driver.executeScript('arguments[0].click();', btnPDF);

    // === Paso 5: Validar alerta SWEETALERT2 ===
    console.log('🔍 Esperando alerta SweetAlert2...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 7000);

    const title = await driver.findElement(By.css('.swal2-title')).getText();
    const text = await driver.findElement(By.css('.swal2-html-container')).getText();

    console.log('✅ Alerta detectada:');
    console.log('   Título:', title);
    console.log('   Texto:', text);

    if (!title.toLowerCase().includes('generando') && !text.toLowerCase().includes('pdf')) {
      throw new Error('El mensaje de alerta no contiene el texto esperado.');
    }

    // Cerrar alerta automáticamente
    await driver.findElement(By.css('.swal2-confirm')).click();

    status = 'p';
    notes = '✅ El sistema mostró correctamente la alerta de generación de PDF.';
    console.log('✅ Prueba completada con éxito.');

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = '❌ Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === ENVIAR RESULTADO A TESTLINK ===
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

// === EJECUTAR ===
runTest();
