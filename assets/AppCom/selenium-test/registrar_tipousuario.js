// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-22'; // ID real del caso en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Registrar Tipo de Usuario ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Entrar al login ===
    console.log('🧭 Navegando al formulario de login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(2000);

    // === Paso 2: Ingresar credenciales ===
    console.log('✏️ Ingresando credenciales...');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');

    // === Paso 3: Ingresar al sistema ===
    console.log('🖱️ Haciendo clic en "Ingresar"...');
    await driver.findElement(By.id('ingresar')).click();
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // === Paso 4: Ir al módulo Tipo Usuario ===
    console.log('📂 Navegando al módulo Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.urlContains('pagina=tipousuario'), 10000);
    console.log('✅ Página de Tipo Usuario cargada.');

    // === Paso 5: Abrir modal de registro ===
    console.log('🆕 Abriendo modal de registro...');
    const btnRegistrar = await driver.wait(until.elementLocated(By.css('button[data-bs-target="#registro"]')), 10000);
    await btnRegistrar.click();

    // Esperar el modal
    await driver.wait(until.elementLocated(By.id('nombre')), 10000);
    await driver.sleep(500);

    // === Paso 6: Llenar formulario ===
    console.log('✍️ Llenando formulario...');
    await driver.findElement(By.id('nombre')).sendKeys('Asesor de Ventas');

    // === Seleccionar nivel de acceso ===
    console.log('🔽 Seleccionando nivel...');
    const selectNivel = await driver.findElement(By.id('nivel'));
    await selectNivel.click();
    await driver.findElement(By.css('#nivel option[value="2"]')).click(); // ✅ Nivel 2

    // === Seleccionar estado ===
    console.log('🔽 Seleccionando estado...');
    const selectEstatus = await driver.findElement(By.id('estatus'));
    await selectEstatus.click();
    await driver.findElement(By.css('#estatus option[value="1"]')).click(); // ✅ Activo

    await driver.sleep(500);

    // === Paso 7: Registrar ===
    console.log('💾 Haciendo clic en "Registrar"...');
    await driver.findElement(By.id('registrar')).click();

    // === Paso 8: Esperar mensaje de éxito ===
    console.log('⏳ Esperando mensaje de confirmación...');
    await driver.wait(until.elementLocated(By.css('.swal2-popup')), 10000);
    const mensaje = await driver.findElement(By.css('.swal2-title')).getText();

    if (mensaje.includes('Rol registrado con éxito')) {
      console.log('✅ Prueba exitosa: Rol registrado correctamente.');
      notes = 'Registro de tipo de usuario completado con éxito.';
      status = 'p';
    } else {
      throw new Error('El mensaje de éxito no apareció o fue diferente.');
    }

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.sleep(1500);
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

// === Ejecutar prueba ===
runTest();
