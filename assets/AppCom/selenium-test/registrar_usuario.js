// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '1af1fedd401b426799e4fd0ec39586de';
const TEST_PLAN_ID = 151;
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: REGISTRAR PRODUCTO DUPLICADO ===
async function runTestRegistrarProductoDuplicado() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f';
  let notes = '';

  try {
    console.log('🧭 Navegando al login...');
    await driver.get('http://localhost:8080/proyectoIII/Proyecto-III/?pagina=login');
    await driver.sleep(2000);

    console.log('✏️ Ingresando credenciales...');
    await driver.findElement(By.id('usuario')).sendKeys('10200300');
    await driver.findElement(By.id('pid')).sendKeys('love1234');
    await driver.findElement(By.id('ingresar')).click();

    await driver.wait(until.urlContains('pagina=home'), 10000);
    console.log('✅ Login exitoso.');

    console.log('📦 Navegando a la sección de usuariu...');
    await driver.get('http://localhost:8080/proyectoIII/Proyecto-III/?pagina=usuario');
    await driver.wait(until.elementLocated(By.id('btnAbrirRegistrar')), 10000);
    console.log('✅ Página de productos cargada.');

    console.log('🧾 Abriendo modal de registro...');
    await driver.findElement(By.id('btnAbrirRegistrar')).click();
    await driver.sleep(1000);

    console.log('✍️ Llenando los datos del producto duplicado...');
    await driver.findElement(By.id('nombre')).sendKeys('Eduardo'); // Ya existente
    await driver.findElement(By.id('apellido')).sendKeys('Rojas'); // Ya existente
    await driver.findElement(By.id('cedula')).sendKeys('12241103');
    await driver.findElement(By.id('telefono')).sendKeys('0412-4279329');
    await driver.findElement(By.id('correo')).sendKeys('eduardo.rojas@gmail.com');
    await driver.findElement(By.id('clave')).sendKeys('lara1234');
    await driver.findElement(By.id('confirmar_clave')).sendKeys('lara1234');
 
    const categoria = await driver.findElement(By.id('rolSelect'));
    await categoria.findElement(By.css('option:nth-child(2)')).click();

    
    await driver.findElement(By.id('registrar')).click();

                // === Paso 6.1: Esperar y confirmar SweetAlert2 ===
            console.log('⏳ Esperando confirmación SweetAlert2...');
            await driver.wait(until.elementLocated(By.css('.swal2-popup')), 5000);
            const confirmButton = await driver.findElement(By.css('.swal2-confirm'));
            await confirmButton.click();
            console.log('✅ Confirmación enviada.');

            // === Paso 6.2: Esperar alerta de éxito ===
            console.log('⏳ Esperando alerta de éxito...');
            await driver.wait(until.elementLocated(By.css('.swal2-popup')), 5000);
            const successAlert = await driver.findElement(By.css('.swal2-popup'));
            const successMessage = await successAlert.getText();
            console.log('📄 Mensaje de éxito detectado:', successMessage);

            // === Paso 6.3: Evaluar resultado ===
            if (/registro exitoso|usuario creado|guardado correctamente/i.test(successMessage)) {
              console.log('✅ Registro exitoso confirmado.');
              status = 'p';
              notes = 'El sistema confirmó el registro exitoso: ' + successMessage;
            } else {
              console.log('❌ No se detectó mensaje de éxito esperado.');
              status = 'f';
              notes = 'No se mostró mensaje de éxito esperado. Mensaje: ' + successMessage;
            }


  } catch (error) {
    console.error('❌ Error durante la prueba de Usuario:', error.message);
    notes = 'Error: ' + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLinkDuplicado(status, notes);
  }
}

// === FUNCIÓN: Reportar resultado a TestLink ===
async function reportResultToTestLinkDuplicado(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });
    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: 'D-1-7', // ⚠️ cambia al ID real del caso "Producto duplicado"
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

// === Ejecutar ===
(async () => {
  console.log('🚀 Iniciando prueba: Registrar producto duplicado...');
  await runTestRegistrarProductoDuplicado();
  console.log('✅ Prueba finalizada.');
})();
