// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-25'; // ✅ ID del caso en TestLink
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: ERROR AL REGISTRAR UN TIPO DE USUARIO EXISTENTE ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    // === Paso 1: Navegar al login ===
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(1500);

    // === Paso 2: Ingresar credenciales ===
    console.log('✏️ Ingresando credenciales...');
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    // === Paso 3: Esperar redirección al home ===
    console.log('⏳ Esperando redirección al home...');
    await driver.wait(until.urlContains('pagina=home'), 10000);

    // === Paso 4: Ir al módulo Tipo Usuario ===
    console.log('📂 Navegando al módulo Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.urlContains('pagina=tipousuario'), 10000);
    await driver.sleep(1500);

    // === Paso 5: Abrir modal de registro ===
    console.log('🆕 Abriendo modal de registro...');
    const btnRegistrar = await driver.wait(until.elementLocated(By.css('button[data-bs-target="#registro"]')), 10000);
    await btnRegistrar.click();
    await driver.sleep(1000);

    // === Paso 6: Completar el formulario con datos ya existentes ===
    console.log('✍️ Llenando datos del tipo de usuario existente...');
    await driver.findElement(By.id('nombre')).sendKeys('Administrador');

    const selectNivel = await driver.findElement(By.id('nivel'));
    await selectNivel.click();
    await selectNivel.sendKeys(Key.ARROW_DOWN, Key.ENTER); // seleccionar Nivel 2
    await driver.sleep(500);

    const selectEstatus = await driver.findElement(By.id('estatus'));
    await selectEstatus.click();
    await selectEstatus.sendKeys(Key.ARROW_DOWN, Key.ENTER); // seleccionar Activo
    await driver.sleep(500);

    // === Paso 7: Intentar registrar ===
    console.log('🖱️ Haciendo clic en "Registrar"...');
    await driver.findElement(By.id('registrar')).click();
    await driver.sleep(1500);

    // === Paso 8: Verificar mensaje de error ===
    console.log('🔎 Buscando mensaje de error en SweetAlert...');
    const alerta = await driver.wait(
      until.elementLocated(By.css('.swal2-popup')),
      8000
    );

    const alertaTexto = await alerta.getText();
    console.log('📢 Mensaje de alerta:', alertaTexto);

    if (alertaTexto.includes('Ya existe un tipo de usuario') || alertaTexto.includes('existente')) {
      console.log('✅ Mensaje de error mostrado correctamente.');
      status = 'p';
      notes = 'Se detectó correctamente el intento de registrar un tipo de usuario duplicado.';
    } else {
      throw new Error('No se mostró el mensaje esperado al registrar un tipo de usuario existente.');
    }

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.sleep(2000);
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
