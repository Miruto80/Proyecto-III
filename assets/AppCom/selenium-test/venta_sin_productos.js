const { Builder, By, Key, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '76133924c3d3f13d8490b26f5d5a7ca5';
const TEST_CASE_EXTERNAL_ID = 'Prueba-3'; // Ajusta si es otro ID
const TEST_PLAN_ID = 104;
const BUILD_ID = 1;

async function runTest() {
  let driver;
  let status = 'f';
  let notes = '';

  try {
    driver = await new Builder().forBrowser('MicrosoftEdge').build();
    console.log("🚀 Iniciando prueba: Registrar venta sin productos...");

    // === LOGIN ===
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=login');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log("✅ Login exitoso.");

    // === ABRIR MÓDULO DE VENTA ===
    console.log("➡️ Abriendo módulo de venta...");
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=salida');
    const abrirModalBtn = await driver.findElement(By.css('button[data-bs-target=\"#registroModal\"]'));
    await abrirModalBtn.click();
    await driver.wait(until.elementLocated(By.id('registroModal')), 10000);

    // === PASO 1: CLIENTE ===
    console.log("➡️ Seleccionando cliente válido...");
    const cedulaInput = await driver.findElement(By.id('cedula_cliente'));
    await driver.wait(until.elementIsVisible(cedulaInput), 10000);
    await cedulaInput.clear();
    await cedulaInput.sendKeys('12345678');
    await driver.sleep(1500);

    // Si no existe, registrar nuevo cliente
    const nombreInput = await driver.findElement(By.id('nombre_cliente'));
    const nombreValue = await nombreInput.getAttribute('value');
    if (!nombreValue || nombreValue.trim() === '') {
      await nombreInput.sendKeys('Ana');
      await driver.findElement(By.id('apellido_cliente')).sendKeys('Pérez');
      await driver.findElement(By.id('telefono_cliente')).sendKeys('04121234567');
      await driver.findElement(By.id('correo_cliente')).sendKeys('ana.perez@example.com');
    }

    // Avanzar al paso de productos
    await clickSiguiente(driver, "Cliente ➜ Productos");

    // === PASO 2: PRODUCTOS (no se agregan productos) ===
    console.log("➡️ En paso de productos (no se agregan productos).");
    await driver.sleep(2000);

    // Intentar avanzar al paso de pago
    console.log("➡️ Intentando avanzar sin productos...");
    const btnSiguiente = await driver.findElement(By.id('btnSiguiente'));
    const isEnabled = await btnSiguiente.isEnabled();

    if (!isEnabled) {
      console.log("✅ El sistema bloqueó correctamente el avance sin productos.");
      notes = "✅ El sistema no permitió avanzar al siguiente paso al no agregar productos (comportamiento esperado).";
      status = 'p'; // Passed
    } else {
      // Si el botón está habilitado, intentamos avanzar (comportamiento incorrecto)
      await driver.executeScript('arguments[0].click();', btnSiguiente);
      await driver.sleep(2000);

      // Verificar si cambió de paso
      const currentStep = await driver.executeScript("return document.querySelector('#step-3-content')?.offsetParent !== null;");
      if (currentStep) {
        console.log("❌ El sistema permitió avanzar sin productos (error).");
        notes = "❌ El sistema permitió avanzar al paso de pago sin productos.";
        status = 'f';
      } else {
        console.log("✅ El sistema bloqueó correctamente el avance sin productos.");
        notes = "✅ El sistema no permitió avanzar al siguiente paso al no agregar productos (comportamiento esperado).";
        status = 'p';
      }
    }

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
  await driver.sleep(2000);
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
