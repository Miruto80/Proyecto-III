// === DEPENDENCIAS ===
const { Builder, By, Key, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '76133924c3d3f13d8490b26f5d5a7ca5';
const TEST_CASE_EXTERNAL_ID = 'Prueba-9'; 
const TEST_PLAN_ID = 104;
const BUILD_ID = 1;

// === TEST AUTOMATIZADO: REGISTRAR COMPRA VÁLIDA CON UN PRODUCTO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Iniciar sesión ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=login');
    await driver.sleep(2000);
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 2: Ir al módulo Compra ===
    console.log('🛒 Accediendo al módulo Compra...');
    await driver.get('http://localhost:8080/LoveMakeup/Proyecto-III/?pagina=entrada');
    await driver.wait(until.elementLocated(By.css('button[data-bs-target="#registroModal"]')), 10000);
    console.log('✅ Módulo Compra cargado correctamente.');

    // === Paso 3: Abrir formulario de compra ===
    console.log('📄 Abriendo formulario de compra...');
    const registrarBtn = await driver.findElement(By.css('button[data-bs-target="#registroModal"]'));
    await driver.executeScript("arguments[0].scrollIntoView({block: 'center'});", registrarBtn);
    await driver.wait(until.elementIsVisible(registrarBtn), 10000);
    await driver.wait(until.elementIsEnabled(registrarBtn), 10000);
    try {
      await registrarBtn.click();
    } catch (e) {
      await driver.executeScript("arguments[0].click();", registrarBtn);
    }

    // === Paso 4: Esperar que el modal esté completamente visible ===
    await driver.sleep(1000);
    const modal = await driver.findElement(By.id('registroModal'));
    await driver.wait(until.elementIsVisible(modal), 10000);

    // === Paso 5: Llenar datos básicos ===
    console.log('📝 Llenando datos de la compra...');
    const today = new Date().toISOString().split('T')[0];

    const fechaInput = await driver.findElement(By.id('fecha_entrada_reg'));
    await driver.wait(until.elementIsVisible(fechaInput), 10000);
    await fechaInput.sendKeys(today);

    const proveedorSelect = await driver.findElement(By.id('id_proveedor_reg'));
    await driver.wait(until.elementIsVisible(proveedorSelect), 10000);
    await proveedorSelect.sendKeys(Key.ARROW_DOWN, Key.ENTER);

    // === Paso 6: Llenar sección de productos ===
    console.log('📦 Llenando producto...');
    await driver.wait(until.elementLocated(By.css('#productos-container .producto-fila')), 10000);
    const productoFila = await driver.findElement(By.css('#productos-container .producto-fila'));
    await driver.wait(until.elementIsVisible(productoFila), 10000);

    const productoSelect = await productoFila.findElement(By.css('.producto-select'));
    await driver.wait(until.elementIsVisible(productoSelect), 10000);
    await productoSelect.sendKeys(Key.ARROW_DOWN, Key.ENTER);

    const cantidadInput = await productoFila.findElement(By.css('.cantidad-input'));
    await driver.wait(until.elementIsVisible(cantidadInput), 10000);
    await cantidadInput.clear();
    await cantidadInput.sendKeys('10');

    const precioInput = await productoFila.findElement(By.css('.precio-input'));
    await driver.wait(until.elementIsVisible(precioInput), 10000);
    await precioInput.clear();
    await precioInput.sendKeys('5.00');

    // === Paso 7: Registrar compra ===
    console.log('💾 Registrando compra...');
    const registrarCompraBtn = await driver.findElement(By.css('button[name="registrar_compra"]'));
    await driver.executeScript("arguments[0].scrollIntoView({block: 'center'});", registrarCompraBtn);
    await driver.wait(until.elementIsVisible(registrarCompraBtn), 10000);
    await driver.wait(until.elementIsEnabled(registrarCompraBtn), 10000);
    try {
      await registrarCompraBtn.click();
    } catch (e) {
      await driver.executeScript("arguments[0].click();", registrarCompraBtn);
    }

    // === Paso 8: Verificar éxito por cierre de modal ===
    console.log('🔍 Verificando cierre del modal...');
    await driver.wait(until.stalenessOf(modal), 10000);

    console.log('✅ Compra registrada exitosamente.');
    notes = 'Compra registrada exitosamente con un producto válido.';
    status = 'p';

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

    const cleanNotes = notes.replace(/<[^>]*>/g, '').replace(/\n/g, ' ').trim();

    client.methodCall('tl.checkDevKey', [{ devKey: DEV_KEY }], function (error, value) {
      if (error) {
        console.error('❌ DevKey inválido o conexión fallida:', error);
        return;
      }

      console.log('✅ DevKey válido. Reportando resultado...');
      const params = {
        devKey: DEV_KEY,
        testcaseexternalid: TEST_CASE_EXTERNAL_ID,
        testplanid: TEST_PLAN_ID,
        buildid: BUILD_ID,
        notes: cleanNotes,
        status: status,
      };

      client.methodCall('tl.reportTCResult', [params], function (error, value) {
        if (error) {
          console.error('⚠️ Error al enviar resultado a TestLink:', error);
        } else {
          console.log('📤 Resultado enviado a TestLink:', value);
        }
      });
    });
  } catch (error) {
    console.error('⚠️ No se pudo conectar con TestLink:', error);
  }
}

// === Ejecutar test ===
runTest();
