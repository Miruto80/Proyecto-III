// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink/testlink2/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '11ec259b8ac7c56e5d7a47814a33f639';
const TEST_CASE_EXTERNAL_ID = 'R-1-28';
const TEST_PLAN_ID = 2;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: Error al modificar tipo usuario (rango de caracteres) ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=login');
    await driver.sleep(2000);

    // Esperar y llenar campos de login
    await driver.wait(until.elementLocated(By.id('usuario')), 10000);
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    // Esperar redirección
    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    // Navegar al módulo Tipo Usuario
    console.log('📂 Navegando al módulo Tipo Usuario...');
    await driver.get('http://localhost:8080/PROYECTO/Proyecto-III/?pagina=tipousuario');
    await driver.wait(until.elementLocated(By.id('myTable')), 10000);
    console.log('✅ Página de Tipo Usuario cargada.');

    // Buscar un botón de modificar (distinto del id 2)
    const botones = await driver.findElements(By.css('.modificar'));
    if (botones.length === 0) throw new Error('No se encontraron botones de modificar.');

    let botonSeleccionado = null;
    for (const btn of botones) {
      const id = await btn.getAttribute('data-id');
      if (id !== '2') {
        botonSeleccionado = btn;
        break;
      }
    }

    if (!botonSeleccionado) throw new Error('No se encontró un tipo de usuario modificable.');
    await botonSeleccionado.click();

    // Esperar modal
    console.log('🖱️ Modal de modificación abierto...');
    await driver.wait(until.elementLocated(By.id('nombre_modificar')), 5000);

    // Casos de nombres inválidos
    const casos = [
      'As', // corto
      '', // vacío
      'Asesor de Ventas del negocio Love Makeup con Servicio Integral y más' // largo
    ];

    for (const nombreInvalido of casos) {
      console.log(`🧪 Probando con nombre inválido: "${nombreInvalido}"`);

      const inputNombre = await driver.findElement(By.id('nombre_modificar'));
      await inputNombre.clear();
      await inputNombre.sendKeys(nombreInvalido);

      await driver.findElement(By.id('btnModificar')).click();

      // Esperar y leer alerta SweetAlert2
      console.log('⏳ Esperando alerta SweetAlert2...');
      try {
        await driver.wait(
          until.elementLocated(By.css('.swal2-popup.swal2-modal')),
          5000
        );
        await driver.sleep(800); // esperar render completo

        const alertaElemento = await driver.findElement(By.css('.swal2-html-container'));
        const alertaTexto = await alertaElemento.getText();

        console.log('📢 Mensaje mostrado:', alertaTexto);

        if (
          alertaTexto.includes('Nombre inválido') ||
          alertaTexto.includes('Nombre invalido') ||
          alertaTexto.includes('debe tener')
        ) {
          console.log('✅ Validación correcta: mensaje de error mostrado.');
          status = 'p';
          notes = 'El sistema mostró correctamente la alerta por nombre inválido.';
        } else {
          throw new Error('⚠️ No se detectó el mensaje de error esperado.');
        }

        // Cerrar alerta (presionando ESC)
        const body = await driver.findElement(By.css('body'));
        await body.sendKeys('\uE00C');
        await driver.sleep(1000);
      } catch (error) {
        console.warn('❌ No se encontró o no se leyó correctamente el SweetAlert:', error.message);
        status = 'f';
        notes = 'SweetAlert2 no apareció o el mensaje fue distinto al esperado.';
      }
    }

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    status = 'f';
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === FUNCIÓN PARA REPORTAR RESULTADOS ===
async function reportResultToTestLink(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });

    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: TEST_CASE_EXTERNAL_ID,
      testplanid: TEST_PLAN_ID,
      buildname: BUILD_NAME,
      notes: notes,
      status: status
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
