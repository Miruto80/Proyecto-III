// Importa Selenium WebDriver
const { Builder, By, Key, until } = require('selenium-webdriver');

async function runEdgeTest() {
  // Inicia el navegador Microsoft Edge
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();

  try {
    // Abre una página (por ejemplo, Google)
    await driver.get('https://www.google.com');

    // Busca la caja de búsqueda
    let searchBox = await driver.findElement(By.name('q'));

    // Escribe algo y presiona Enter
    await searchBox.sendKeys('TestLink Selenium Edge', Key.RETURN);

    // Espera a que cargue el título
    await driver.wait(until.titleContains('TestLink'), 5000);

    console.log('✅ Prueba exitosa: búsqueda realizada en Edge.');
  } catch (error) {
    console.error('❌ Error durante la prueba:', error);
  } finally {
    // Cierra el navegador
    await driver.quit();
  }
}

runEdgeTest();
