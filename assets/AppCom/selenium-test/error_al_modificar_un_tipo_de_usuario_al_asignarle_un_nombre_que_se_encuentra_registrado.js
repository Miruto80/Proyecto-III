// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-26';
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: ERROR AL MODIFICAR UN TIPO DE USUARIO EXISTENTE ===
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

    // === Paso 2: Navegar al módulo Tipo Usuario ===
    console.log('📂 Abriendo módulo Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.urlContains('pagina=tipousuario'), 10000);
    await driver.sleep(2000);

    // === Paso 3: Buscar botones de modificar ===
    console.log('🔍 Buscando botones de modificación...');
    const botonesModificar = await driver.findElements(By.css('.modificar'));
    if (botonesModificar.length === 0) throw new Error('No se encontraron botones de modificar.');

    // === Paso 4: Saltar el tipo de usuario con ID 2 (Administrador) ===
    let botonValido = null;
    for (const boton of botonesModificar) {
      const idTipo = await boton.getAttribute('data-id');
      if (idTipo !== '2') {
        botonValido = boton;
        break;
      }
    }

    if (!botonValido) throw new Error('No hay tipos de usuario modificables (todos son protegidos).');

    // === Paso 5: Abrir modal de modificación ===
    console.log('✏️ Abriendo modal de modificación...');
    await botonValido.click();
    await driver.sleep(1500);

    // === Paso 6: Intentar cambiar nombre a uno existente (Administrador) ===
    console.log('📝 Cambiando nombre a "Administrador"...');
    const inputNombre = await driver.findElement(By.id('nombre_modificar'));
    await inputNombre.clear();
    await inputNombre.sendKeys('Administrador');
    await driver.sleep(500);

    const selectNivel = await driver.findElement(By.id('nivel_modificar'));
    await selectNivel.click();
    await selectNivel.sendKeys(Key.ARROW_DOWN, Key.ENTER);
    await driver.sleep(500);

    const selectEstatus = await driver.findElement(By.id('estatus_modificar'));
    await selectEstatus.click();
    await selectEstatus.sendKeys(Key.ARROW_DOWN, Key.ENTER);
    await driver.sleep(500);

    // === Paso 7: Clic en "Actualizar" ===
    console.log('🖱️ Haciendo clic en "Actualizar"...');
    await driver.findElement(By.id('btnModificar')).click();

    // === Paso 8: Esperar mensaje de error ===
    console.log('🔎 Esperando mensaje de error...');
    const alerta = await driver.wait(until.elementLocated(By.css('.swal2-popup')), 8000);
    const textoAlerta = await alerta.getText();
    console.log('📢 Mensaje de alerta:', textoAlerta);

    if (textoAlerta.includes('Ya existe un tipo de usuario') || textoAlerta.includes('existente')) {
      console.log('✅ El sistema detectó correctamente el intento de duplicado.');
      status = 'p';
      notes = 'Se mostró el mensaje de error al intentar modificar un tipo de usuario con nombre duplicado.';
    } else {
      throw new Error('No se mostró el mensaje esperado al intentar modificar un tipo existente.');
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
