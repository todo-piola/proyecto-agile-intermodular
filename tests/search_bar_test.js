const { Builder, By, Key, until } = require("selenium-webdriver");
const chrome = require("selenium-webdriver/chrome");

let expect;
before(async () => {
    ({ expect } = await import("chai"));
});

const BASE_URL = process.env.E2E_BASE_URL || "http://localhost/progecto-agil/proyecto-agile-intermodular";
const RUN_HEADLESS = process.env.E2E_HEADLESS !== "0";

describe("Barra de búsqueda - LABUTACASOCIAL", function () {
    this.timeout(60000);
    let driver;

    before(async () => {
        const options = new chrome.Options();
        if (RUN_HEADLESS) options.addArguments("--headless=new");
        options.addArguments("--window-size=1366,900");
        options.addArguments("--disable-gpu");
        driver = await new Builder().forBrowser("chrome").setChromeOptions(options).build();
    });

    after(async () => {
        if (driver) await driver.quit();
    });

    async function getVisibleElement(cssSelector) {
        await driver.wait(until.elementsLocated(By.css(cssSelector)), 10000);

        return driver.wait(async () => {
            const elements = await driver.findElements(By.css(cssSelector));

            for (const element of elements) {
                try {
                    if (await element.isDisplayed()) return element;
                } catch (error) {
                    continue;
                }
            }

            return elements[0] || false;
        }, 10000);
    }

    it("debería navegar a search.php al buscar con la lupa", async () => {
        await driver.get(`${BASE_URL}/index.php`);

        const inputBusqueda = await getVisibleElement("#contenedor-lupa #barraBusqueda, #contenedor-lupa-movil #barraBusqueda");
        const botonLupa = await getVisibleElement("#contenedor-lupa #lupaBtn, #contenedor-lupa-movil #lupaBtn");

        await inputBusqueda.clear();
        await inputBusqueda.sendKeys("matrix");
        await driver.executeScript("arguments[0].click();", botonLupa);

        await driver.wait(async () => {
            const currentUrl = await driver.getCurrentUrl();
            return currentUrl.includes("route=search") && currentUrl.includes("query=");
        }, 10000);

        const currentUrl = await driver.getCurrentUrl();
        const queryValue = new URL(currentUrl).searchParams.get("query");
        expect(queryValue).to.equal("matrix");
    });

    it("no debería navegar si la búsqueda tiene menos de 2 caracteres", async () => {
        await driver.get(`${BASE_URL}/index.php`);

        const inputBusqueda = await getVisibleElement("#contenedor-lupa #barraBusqueda, #contenedor-lupa-movil #barraBusqueda");
        const botonLupa = await getVisibleElement("#contenedor-lupa #lupaBtn, #contenedor-lupa-movil #lupaBtn");

        const initialUrl = await driver.getCurrentUrl();

        await inputBusqueda.clear();
        await inputBusqueda.sendKeys("a");
        await driver.executeScript("arguments[0].click();", botonLupa);
        await driver.sleep(800);

        const finalUrl = await driver.getCurrentUrl();
        expect(finalUrl).to.equal(initialUrl);
    });

    it("debería navegar al buscar con Enter", async () => {
        await driver.get(`${BASE_URL}/index.php`);

        const inputBusqueda = await getVisibleElement("#contenedor-lupa #barraBusqueda, #contenedor-lupa-movil #barraBusqueda");

        await inputBusqueda.clear();
        await inputBusqueda.sendKeys("avatar", Key.ENTER);

        await driver.wait(async () => {
            const currentUrl = await driver.getCurrentUrl();
            return currentUrl.includes("route=search") && currentUrl.includes("query=");
        }, 10000);

        const currentUrl = await driver.getCurrentUrl();
        const queryValue = new URL(currentUrl).searchParams.get("query");
        expect(queryValue).to.equal("avatar");
    });
});