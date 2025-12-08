const { Builder, By, until } = require("selenium-webdriver");
const { expect } = require("chai");
require("chromedriver");

    describe("Formulario correcto de LABUTACASOCIAL", function () {
    this.timeout(60000);
    let driver;

    before(async () => {
        driver = await new Builder().forBrowser("chrome").build();
    });

    after(async () => {
        if (driver) await driver.quit();
    });

    it("Debería enviar el formulario correctamente con datos válidos", async () => {
        await driver.get("http://127.0.0.1:8080/index.html");

        //Abrir modal
        const botonRegistro = await driver.wait(until.elementLocated(By.css('[data-bs-toggle="modal"]')), 10000);
        await driver.executeScript("arguments[0].click();", botonRegistro);

        const modal = await driver.wait(until.elementLocated(By.id("modal")), 10000);
        await driver.wait(until.elementIsVisible(modal), 10000);
        await driver.sleep(500);

        //Llenar inputs
        const nombreInput = await driver.findElement(By.id("nombreApellido"));
        await driver.wait(until.elementIsVisible(nombreInput), 5000);
        await nombreInput.sendKeys("Juan Pérez");

        const correoInput = await driver.findElement(By.id("correo"));
        await correoInput.sendKeys("juanperez@gmail.com");

        const passInput = await driver.findElement(By.id("contrasena"));
        const password = "Contrasenna123!";
        await passInput.sendKeys(password);

        const confirmInput = await driver.findElement(By.id("confirmarContrasena"));
        await confirmInput.sendKeys(password);

        const fechaInput = await driver.findElement(By.id("fechaNacimiento"));
        await fechaInput.sendKeys("1990-01-01");

        //Radio y checkboxes
        await driver.executeScript("arguments[0].click();", await driver.findElement(By.id("masculino")));
        await driver.executeScript("arguments[0].click();", await driver.findElement(By.id("notificaciones")));
        await driver.executeScript("arguments[0].click();", await driver.findElement(By.id("revistaDigital")));

        //Envia formulario
        const botonEnviar = await driver.findElement(By.id("btn-envio"));
        await driver.executeScript("arguments[0].click();", botonEnviar);

        //Verificar el mensaje de éxito
        const mensajeExito = await driver.wait(until.elementLocated(By.id("mensajeExito")), 5000);
        const text = await mensajeExito.getText();
        expect(text.trim()).to.not.equal("");

        await driver.sleep(1000); //espera para ver el mensaje
    });
});
