const { Builder } = require('selenium-webdriver');
require('chromedriver'); // <- IMPORTANTE

(async function testChrome() {
    let driver = await new Builder().forBrowser('chrome').build();
    await driver.get('https://www.google.com');
    console.log('Chrome abierto 🚀');
    await driver.quit();
})();