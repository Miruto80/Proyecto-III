// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'R-1-9'; // ⚠️ cambia al ID real en tu TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al modificar una categoría asignándole un carácter inválido ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(1000);
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 2: Ir a Categoría ===
    console.log('🧭 Navegando a Categoría...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=categoria');
    await driver.wait(until.urlContains('pagina=categoria'), 10000);

    // === Paso 3: Buscar fila con nombre "Polvo" ===
    console.log('🖱️ Buscando fila con nombre "Polvo"...');
    const rows = await driver.findElements(By.css('table tbody tr'));
    let targetRow = null;

    for (const row of rows) {
      const cells = await row.findElements(By.css('td'));
      for (const cell of cells) {
        const text = (await cell.getText())
          .trim()
          .toLowerCase()
          .replace(/\s+/g, ''); // quita espacios invisibles
        if (text.includes('polvo')) {
          targetRow = row;
          break;
        }
      }
      if (targetRow) break;
    }

    if (!targetRow) {
      throw new Error('No se encontró la categoría "Polvo" en la tabla.');
    }

    // === Paso 4: Abrir modal de Modificar ===
    console.log('🖱️ Abriendo modal de Modificar para "Polvo"...');
    const btnModificar = await targetRow.findElement(By.css('.btnModif'));
    await btnModificar.click();

    await driver.wait(until.elementIsVisible(driver.findElement(By.id('nombre'))), 8000);

    // === Paso 5: Intentar colocar un carácter inválido (número) ===
    console.log('✏️ Intentando ingresar "Polv8" (carácter inválido)...');
    const inputNombre = await driver.findElement(By.id('nombre'));
    await inputNombre.clear();

    const invalidName = 'Polv8';
    for (let ch of invalidName) {
      await inputNombre.sendKeys(ch);
      await driver.sleep(200); // simula keypress/keyup para disparar validación
    }

    // Verificar que el input bloqueó el número (debe quedar "Polv")
    const value = await inputNombre.getAttribute('value');
    console.log('📋 Valor final del input:', value);

    // === Paso 6: Verificar mensaje de error debajo del input ===
    console.log('⏳ Verificando mensaje de error debajo del input (#snombre)...');
    const errorMsgEl = await driver.wait(until.elementLocated(By.id('snombre')), 7000);
    const errorText = await errorMsgEl.getText();

    if (
      value.trim() === 'Polv' &&
      errorText.toLowerCase().includes('solo se permiten letras')
    ) {
      console.log('✅ Bloqueo correcto: número rechazado y mensaje mostrado.');
      notes = 'El sistema bloqueó el carácter inválido al modificar la categoría y mostró el mensaje esperado.';
      status = 'p';
    } else {
      throw new Error(`Validación no coincide. Input="${value}", Mensaje="${errorText}"`);
    }

    // Pausa breve para apreciar el mensaje
    await driver.sleep(3000);

    // Importante: NO enviar el formulario (no se debe guardar nada en esta prueba)

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
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

// === Ejecutar test ===
runTest();
