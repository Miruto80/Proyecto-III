const { Builder, By, Key, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '76133924c3d3f13d8490b26f5d5a7ca5';
const TEST_CASE_EXTERNAL_ID = 'Prueba-1';
const TEST_PLAN_ID = 104;
const BUILD_ID = 1;

async function runTest() {
  let driver;
  let status = 'f';
  let notes = '';

  try {
    driver = await new Builder().forBrowser('MicrosoftEdge').build();
    console.log("🚀 Iniciando prueba de venta completa...");

    // === LOGIN ===
    console.log("➡️ Iniciando sesión...");
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=login');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log("✅ Login exitoso.");

    // === ABRIR MÓDULO DE VENTA ===
    console.log("➡️ Abriendo módulo de venta...");
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=salida');
    const abrirModalBtn = await driver.findElement(By.css('button[data-bs-target="#registroModal"]'));
    await abrirModalBtn.click();
    await driver.wait(until.elementLocated(By.id('registroModal')), 10000);

    // === PASO 1: CLIENTE ===
    console.log("➡️ Paso 1: Cliente");
    const cedulaInput = await driver.findElement(By.id('cedula_cliente'));
    await driver.wait(until.elementIsVisible(cedulaInput), 10000);
    await cedulaInput.clear();
    await cedulaInput.sendKeys('12345678');
    await driver.sleep(1500);

    // Si es cliente nuevo
    const nombreInput = await driver.findElement(By.id('nombre_cliente'));
    const nombreValue = await nombreInput.getAttribute('value');
    if (!nombreValue || nombreValue.trim() === '') {
      await nombreInput.sendKeys('Ana');
      await driver.findElement(By.id('apellido_cliente')).sendKeys('Pérez');
      await driver.findElement(By.id('telefono_cliente')).sendKeys('04121234567');
      await driver.findElement(By.id('correo_cliente')).sendKeys('ana.perez@example.com');
    }

    // Avanzar a Productos
    await clickSiguiente(driver, "Cliente ➜ Productos");

    // === PASO 2: PRODUCTOS ===
    console.log("➡️ Paso 2: Productos");
    await driver.wait(until.elementLocated(By.css('.producto-select-venta')), 10000);
    const productoSelect = await driver.findElement(By.css('.producto-select-venta'));
    await productoSelect.sendKeys(Key.ARROW_DOWN, Key.ENTER);

    const cantidadInput = await driver.findElement(By.css('.cantidad-input-venta'));
    await driver.executeScript(`
      const input = arguments[0];
      input.value = '2';
      input.dispatchEvent(new Event('input', { bubbles: true }));
      input.dispatchEvent(new Event('change', { bubbles: true }));
      input.dispatchEvent(new KeyboardEvent('keyup', { bubbles: true }));
    `, cantidadInput);

    // Esperar total
    await driver.wait(until.elementLocated(By.xpath("//*[contains(text(),'Total:')]")), 10000);

    // Leer el total real desde el DOM
    const totalText = await driver.findElement(By.xpath("//*[contains(text(),'Total:')]")).getText();
    const totalValue = parseFloat(totalText.replace(/[^0-9.,]/g, '').replace(',', '.'));
    console.log("🧮 Total detectado:", totalValue);

    // Avanzar a Pago
    await clickSiguiente(driver, "Productos ➜ Pago");

    // === PASO 3: PAGO ===
    console.log("➡️ Paso 3: Pago");
    await driver.wait(until.elementLocated(By.css('.metodo-pago-select')), 10000);
    const metodoPagoSelect = await driver.findElement(By.css('.metodo-pago-select'));
    await metodoPagoSelect.sendKeys(Key.ARROW_DOWN, Key.ENTER);

    const montoInput = await driver.findElement(By.css('.monto-metodopago'));
    await driver.executeScript(`
      const input = arguments[0];
      input.value = '${totalValue.toFixed(2)}';
      input.dispatchEvent(new Event('input', { bubbles: true }));
      input.dispatchEvent(new Event('change', { bubbles: true }));
    `, montoInput);

    await driver.sleep(1000);

    // Avanzar a Confirmación
    await clickSiguiente(driver, "Pago ➜ Confirmación");

    // === PASO 4: CONFIRMAR ===
    console.log("➡️ Paso 4: Confirmación");
    await driver.wait(until.elementLocated(By.id('btnRegistrarVenta')), 10000);
    const registrarVentaBtn = await driver.findElement(By.id('btnRegistrarVenta'));
    await driver.wait(until.elementIsEnabled(registrarVentaBtn), 10000);
    await driver.executeScript('arguments[0].scrollIntoView(true);', registrarVentaBtn);
    await driver.executeScript('arguments[0].click();', registrarVentaBtn);
    console.log("🖱️ Click en Registrar Venta...");

    // === CONFIRMACIÓN SWEETALERT2 ===
    console.log("➡️ Esperando botón 'Sí, confirmar venta'...");
    const confirmarBtn = await driver.wait(
      until.elementLocated(By.css('.swal2-confirm')),
      10000
    );
    await driver.wait(until.elementIsVisible(confirmarBtn), 5000);
    await driver.executeScript('arguments[0].click();', confirmarBtn);
    console.log("✅ Se presionó 'Sí, confirmar venta'.");

    // === VALIDAR ÉXITO FINAL ===
    console.log("⏳ Esperando mensaje final...");
    const successMsg = await driver.wait(
      until.elementLocated(By.xpath("//*[contains(text(),'Venta registrada exitosamente')]")),
      15000
    );
    await driver.wait(until.elementIsVisible(successMsg), 5000);
    console.log("🎉 Venta registrada exitosamente.");

    notes = `✅ Venta registrada correctamente con total ${totalValue.toFixed(2)}. Cliente ${nombreValue ? 'existente' : 'nuevo'}.`;
    status = 'p';

  } catch (error) {
    console.log("❌ Error durante la prueba:", error.message);
    notes = '❌ Error durante la prueba: ' + error.message;
  } finally {
    if (driver) await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// 🔹 Función para avanzar entre pasos
async function clickSiguiente(driver, pasoTexto = '') {
  console.log(`➡️ Avanzando: ${pasoTexto}`);
  await driver.sleep(1000);
  await driver.wait(until.elementLocated(By.id('btnSiguiente')), 10000);
  const btn = await driver.findElement(By.id('btnSiguiente'));
  await driver.wait(until.elementIsVisible(btn), 10000);
  await driver.wait(until.elementIsEnabled(btn), 10000);
  await driver.executeScript('arguments[0].scrollIntoView(true);', btn);
  await driver.executeScript('arguments[0].click();', btn);
  await driver.sleep(2000); // permitir render del siguiente paso
}

// 🔹 Reporte a TestLink
async function reportResultToTestLink(status, notes) {
  const client = xmlrpc.createClient({ url: TESTLINK_URL });
  const cleanNotes = notes.replace(/<[^>]*>/g, '').replace(/\n/g, ' ').trim();

  return new Promise((resolve, reject) => {
    client.methodCall('tl.checkDevKey', [{ devKey: DEV_KEY }], function (error) {
      if (error) {
        reject(error);
        return;
      }

      const params = {
        devKey: DEV_KEY,
        testcaseexternalid: TEST_CASE_EXTERNAL_ID,
        testplanid: TEST_PLAN_ID,
        buildid: BUILD_ID,
        notes: cleanNotes,
        status: status,
      };

      client.methodCall('tl.reportTCResult', [params], function (error, value) {
        if (error) reject(error);
        else resolve(value);
      });
    });
  });
}

// Ejecutar prueba
runTest().catch(console.error);
