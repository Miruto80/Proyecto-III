// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '1af1fedd401b426799e4fd0ec39586de';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'D-1-2'; // cambia al ID real en tu TestLink
const TEST_PLAN_ID = '151'; // ✅ tu test plan ID real
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: LOGIN CORRECTO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f'; // f = failed | p = passed
  let notes = '';

  try {
    // === Paso 1: Entrar al login ===
    console.log('🧭 Navegando al formulario de login...');
    await driver.get('http://localhost:8080/proyectoIII/Proyecto-III/?pagina=login');

    // Esperar un poco para verificar que la página carga
    await driver.sleep(2000);


       // Esperar que cargue el campo de usuario
       await driver.wait(until.elementLocated(By.id('usuario')), 10000);
       console.log('✅ Página de login cargada correctamente.');
   
       // === Paso 2: Ingresar cedula y contraseña ===
       console.log('✏️ Ingresando cédula y contraseña...');
       await driver.findElement(By.id('usuario')).sendKeys('10200400');
       await driver.findElement(By.id('pid')).sendKeys('love1234');
   
       // === Paso 3: Hacer clic en "Ingresar" ===
       console.log('🖱️ Haciendo clic en "Ingresar"...');
       await driver.findElement(By.id('ingresar')).click();
   
       // === Paso 5: Verificar alerta SweetAlert2 transitoria ===
        console.log('🔍 Verificando alerta de acceso denegado...');

        try {
          // Esperar que aparezca el contenedor de SweetAlert2
          await driver.wait(until.elementLocated(By.css('.swal2-popup')), 3000);

          // Capturar el texto del mensaje
          const mensaje = await driver.findElement(By.css('.swal2-html-container')).getText();

          if (mensaje.includes('Cédula y/o Clave inválida.')) {
            console.log('✅ Alerta SweetAlert2 verificada correctamente.');
            notes = 'Mensaje mostrado: ' + mensaje;
            status = 'p';
          } else {
            console.error('❌ El mensaje no contiene el texto esperado.');
            notes = 'Mensaje inesperado: ' + mensaje;
          }

        } catch (e) {
          console.error('❌ No se detectó la alerta SweetAlert2 a tiempo.');
          notes = 'No se detectó la alerta SweetAlert2: ' + e.message;
        }

     
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
      testplanid: TEST_PLAN_ID, // ✅ usamos directamente el número 3
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
