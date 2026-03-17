const { Builder, By, until } = require("selenium-webdriver");
const chrome = require("selenium-webdriver/chrome");

let expect;
before(async () => {
    ({ expect } = await import("chai"));
});

const BASE_URL = process.env.E2E_BASE_URL || "http://localhost/progecto-agil/proyecto-agile-intermodular";
const CART_STORAGE_KEY = "proyecto-agile-intermodular-cart";
const RUN_HEADLESS = process.env.E2E_HEADLESS !== "0";

describe("Carrito offcanvas - LABUTACASOCIAL", function () {
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

    async function limpiarCarrito() {
        await driver.executeScript(`
            window.localStorage.removeItem('${CART_STORAGE_KEY}');
            window.sessionStorage.removeItem('orderSummary');
        `);
    }

    async function guardarUnItemEnCarrito() {
        await driver.executeScript(`
            window.localStorage.setItem('${CART_STORAGE_KEY}', JSON.stringify({
                items: [{
                    id: '9999',
                    titulo: 'Pelicula Test Selenium',
                    precio: 4.5,
                    imagen: 'https://image.tmdb.org/t/p/w500/test-image.jpg',
                    director: 'Director Test',
                    fecha: '01/01/2026'
                }]
            }));
        `);
    }

    async function abrirOffcanvasCarrito() {
        const botonCarrito = await driver.wait(until.elementLocated(By.id("cartBtn")), 10000);
        await driver.executeScript("arguments[0].click();", botonCarrito);

        await driver.wait(async () => {
            const panel = await driver.findElement(By.id("offcanvasScrolling"));
            const className = await panel.getAttribute("class");
            return className.includes("show");
        }, 10000);
    }

    it("debería cargar el contador e item del carrito desde localStorage", async () => {
        await driver.get(`${BASE_URL}/index.php`);
        await limpiarCarrito();
        await guardarUnItemEnCarrito();
        await driver.navigate().refresh();

        await driver.wait(async () => {
            const count = await driver.findElement(By.id("cart-count")).getText();
            return count.trim() === "1";
        }, 10000);

        await abrirOffcanvasCarrito();

        await driver.wait(async () => {
            const items = await driver.findElements(By.css("#cart-items .cart-item"));
            return items.length === 1;
        }, 10000);

        await driver.wait(async () => {
            const cartText = await driver.findElement(By.id("cart-items")).getText();
            return cartText.includes("Pelicula Test Selenium");
        }, 10000);

        const checkoutButtons = await driver.findElements(By.id("checkout-btn"));
        expect(checkoutButtons.length).to.equal(1);
    });

    it("debería eliminar un item del carrito y dejar contador en 0", async () => {
        await driver.get(`${BASE_URL}/index.php`);
        await limpiarCarrito();
        await guardarUnItemEnCarrito();
        await driver.navigate().refresh();

        await abrirOffcanvasCarrito();

        const eliminarBtn = await driver.wait(until.elementLocated(By.css("#cart-items .eliminate-movie")), 10000);
        await driver.executeScript("arguments[0].click();", eliminarBtn);

        await driver.wait(async () => {
            const count = await driver.findElement(By.id("cart-count")).getText();
            return count.trim() === "0";
        }, 10000);

        const checkoutButtons = await driver.findElements(By.id("checkout-btn"));
        expect(checkoutButtons.length).to.equal(0);

        const storedCart = await driver.executeScript(`
            return window.localStorage.getItem('${CART_STORAGE_KEY}');
        `);
        expect(storedCart).to.equal(null);
    });
});