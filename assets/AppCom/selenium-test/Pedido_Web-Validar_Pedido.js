// pedido_validar_testlink.js
// Requiere: npm install selenium-webdriver xmlrpc
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');
const fs = require('fs');

// CONFIG TESTLINK (IMPORTANTE: usa el External ID con prefijo real de tu proyecto)
const TESTLINK_URL = 'http://localhost/testlink/testlink-1.9.18/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '55387a68ad480af2c9f640e71f955f57';
const TEST_CASE_EXTERNAL_ID = '1-34'; // <-- debe ser algo como PROY-34, ABC-34, LM-34
const TEST_PLAN_ID = 3;
const BUILD_NAME = 'v.1';

async function runTest() {
    let driver = await new Builder().forBrowser('MicrosoftEdge').build();
    let status = 'f';
    let notes = '';

    try {
        console.log('➤ Navegando al login...');
        await driver.get('http://localhost:8080/lovemakeup/Proyecto-III/Proyecto-III/?pagina=login');
        await driver.wait(until.elementLocated(By.id('usuario')), 10000);

        console.log('➤ Ingresando credenciales...');
        await driver.findElement(By.id('usuario')).sendKeys('10200300');
        await driver.findElement(By.id('pid')).sendKeys('love1234');

        // Click en ingresar
        try {
            await driver.findElement(By.id('ingresar')).click();
        } catch {
            const loginBtn = await findFirstAvailable(driver, [
                By.xpath("//button[contains(.,'Ingresar')]"),
                By.xpath("//button")
            ]);
            await clickForce(driver, loginBtn);
        }

        await driver.wait(until.urlContains('pagina=home'), 10000);
        console.log('✅ Login exitoso');

        // ===== ABRIR PEDIDOWEB
        console.log('➤ Abriendo PedidoWeb desde sidebar...');
        await openSidebarIfHidden(driver);

        const pedidoWebLink = await waitAndFind(driver, By.css('a[href="?pagina=pedidoweb"]'), 10000);
        await clickForce(driver, pedidoWebLink);

        await driver.wait(until.urlContains('pagina=pedidoweb'), 10000);
        console.log('✅ PedidoWeb abierto');

        // ===== BOTÓN OJO
        console.log('➤ Abriendo primer pedido...');
        const eyeBtn = await findFirstAvailable(driver, [
            By.css("table tbody tr:first-child .btn-info"),
            By.xpath("//table//tr[1]//i[contains(@class,'fa-eye')]/ancestor::button"),
            By.xpath("//table//tr[1]//button[contains(@class,'info')]")
        ]);
        if (!eyeBtn) throw new Error('No se encontró botón Ver (ojo)');
        await clickForce(driver, eyeBtn);

        // espera modal
        await driver.wait(
            until.elementLocated(By.css('.modal.show, .modal.fade.show')),
            10000
        );
        console.log('✅ Modal de pedido abierto');

        // ===== CERRAR MODAL (Bootstrap 5)
        // === CERRAR MODAL ===
console.log('➤ Cerrando modal con fallback JS...');

try {
    // Intento con botón .btn-close si existe
    const closeBtn = await findFirstAvailable(driver, [
        By.css('.modal.show .btn-close'),
        By.xpath("//button[@data-bs-dismiss='modal']"),
        By.xpath("//button[contains(@aria-label,'Cerrar')]")
    ]);

    if (closeBtn) {
        await clickForce(driver, closeBtn);
    } else {
        console.log("⚠ No hay botón visible, cerrando con backdrop...");
        await driver.executeScript(`
            let backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.click();
        `);
    }

    // ===== Fallback 100% seguro: cerrar modal desde Bootstrap
    await driver.sleep(500);
    await driver.executeScript(`
        const m = document.querySelector('.modal.show, .modal.fade.show');
        if (m) {
            try {
                const instance = bootstrap.Modal.getInstance(m) || new bootstrap.Modal(m);
                instance.hide();
            } catch(e) {}
        }
    `);

} catch (e) {
    console.log("⚠ No se pudo cerrar modal por botón. Forzando cierre absoluto...");
    await driver.executeScript(`
        const m = document.querySelector('.modal.show, .modal.fade.show');
        if (m) m.style.display = "none";
    `);
}

// esperar desaparición
await driver.wait(async () => {
    const modals = await driver.findElements(By.css('.modal.show, .modal.fade.show'));
    return modals.length === 0;
}, 8000);

console.log('✅ Modal cerrado por JS de Bootstrap');

        // esperar desaparición modal
        await driver.wait(async () => {
            const modals = await driver.findElements(By.css('.modal.show, .modal.fade.show'));
            return modals.length === 0;
        }, 8000);

        console.log('✅ Modal cerrado');

        // ===== CONFIRMAR PEDIDO
      // ===== CONFIRMAR PEDIDO =====
console.log("➤ Confirmando pedido...");

const checkBtn = await findFirstAvailable(driver, [
    By.css("table tbody tr:first-child button.btn-validar"),
    By.css("table tbody tr:first-child button.btn-success"),
    By.xpath("//button[contains(@class, 'btn-validar')]"),
    By.xpath("//button[i[contains(@class,'fa-check')]]")
]);

if (!checkBtn) throw new Error("No se encontró botón Confirmar (check)");

await driver.executeScript("arguments[0].scrollIntoView(true);", checkBtn);
await driver.sleep(300);
await clickForce(driver, checkBtn);

// ===== ESPERAR SWEETALERT =====
console.log("➤ Esperando SweetAlert...");
const swalConfirm = await driver.wait(
    until.elementLocated(By.css(".swal2-confirm")),
    8000
);

// aseguramos que sea clickeable
await driver.wait(until.elementIsVisible(swalConfirm), 8000);
await driver.wait(until.elementIsEnabled(swalConfirm), 8000);

// ===== CLIC EN "Sí, confirmar"
console.log("➤ Click en 'Sí, confirmar'...");
await clickForce(driver, swalConfirm);

console.log("✅ Pedido confirmado en SweetAlert");
status = 'p';
notes = 'Pedido validado correctamente.';


       

    } catch (error) {
        console.log('❌ Error durante la prueba:', error);

        // CAPTURA DE PANTALLA
        try {
            const screenshot = await driver.takeScreenshot();
            fs.writeFileSync("error_pedido.png", screenshot, 'base64');
            console.log('🖼 Captura guardada como error_pedido.png');
        } catch {}

        status = 'f';
        notes = 'Error: ' + error.message;
    } finally {
        try { await driver.quit(); } catch {}
        await reportToTestLink(status, notes);
    }
}

/* ==========================================
              FUNCIONES UTILITARIAS
=========================================== */

// Clic forzado que funciona aunque el elemento esté cubierto
async function clickForce(driver, element) {
    try {
        await driver.executeScript("arguments[0].scrollIntoView(true);", element);
    } catch {}

    try {
        await driver.wait(until.elementIsVisible(element), 2000).catch(()=>{});
        await driver.wait(until.elementIsEnabled(element), 2000).catch(()=>{});
        await element.click();
    } catch {
        console.log("⚠ Click normal falló, usando JavaScript");
        await driver.executeScript("arguments[0].click()", element);
    }
}

// Buscar primero disponible
async function findFirstAvailable(driver, locators) {
    for (const locator of locators) {
        try {
            return await driver.findElement(locator);
        } catch {}
    }
    return null;
}

// Esperar y luego encontrar
async function waitAndFind(driver, locator, time = 5000) {
    await driver.wait(until.elementLocated(locator), time);
    return driver.findElement(locator);
}

// Abrir sidebar si está oculto
async function openSidebarIfHidden(driver) {
    try {
        const toggle = await driver.findElement(By.id('iconSidenav'));
        if (await toggle.isDisplayed()) {
            await toggle.click();
            await driver.sleep(700);
        }
    } catch {}
}

// Reporte TestLink sin errores XML
async function reportToTestLink(status, notes) {
    console.log('➤ Enviando resultado a TestLink...');

    // Evitar tag BR en XML
    notes = String(notes)
        .replace(/(\r\n|\n|\r)/gm, ' ')
        .replace(/<br\s*\/?>/gi, ' ')
        .trim();

    const client = xmlrpc.createClient({ url: TESTLINK_URL });

    const params = {
        devKey: DEV_KEY,
        testcaseexternalid: TEST_CASE_EXTERNAL_ID,
        testplanid: TEST_PLAN_ID,
        buildname: BUILD_NAME,
        notes: notes,
        status: status
    };

    client.methodCall('tl.reportTCResult', [params], (err, value) => {
        if (err) {
            console.error('❌ Error reportando a TestLink:', err);
        } else {
            console.log('✅ Resultado reportado a TestLink:', value);
        }
    });
}

runTest();
