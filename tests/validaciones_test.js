const { Builder, By, until } = require("selenium-webdriver");
require("chromedriver");

let expect;
before(async () => {
    ({ expect } = await import("chai"));
});

describe("Verificación de errores - formulario LABUTACASOCIAL", function () {
    this.timeout(60000);
    let driver;

    before(async () => {
        driver = await new Builder().forBrowser("chrome").build();
    });

    after(async () => {
        if (driver) await driver.quit();
    });

    //Función para abrir el modal
    async function abrirModal() {
        await driver.get("http://127.0.0.1:8080/index.html");
        const botonRegistro = await driver.wait(
            until.elementLocated(By.css('[data-bs-toggle="modal"]')),
            10000
        );
        await driver.executeScript("arguments[0].click();", botonRegistro);

        const modal = await driver.wait(
            until.elementLocated(By.id("modal")),
            10000
        );
        await driver.wait(until.elementIsVisible(modal), 10000);
        await driver.sleep(500); // espera animación Bootstrap
    }

    //Función para esperar que un input sea interactuable
    async function esperarInput(id) {
        const elemento = await driver.wait(until.elementLocated(By.id(id)), 5000);
        await driver.wait(until.elementIsVisible(elemento), 5000);
        await driver.wait(until.elementIsEnabled(elemento), 5000);
        return elemento;
    }

    //Función para esperar que aparezca un mensaje de error y detenerse un poco
    async function verificarError(idError) {
        const elementoError = await driver.findElement(By.id(idError));
        await driver.wait(async () => (await elementoError.getText()).trim().length > 0, 5000);
        // Pausa para visualizar el error
        await driver.sleep(1000);
        return elementoError;
    }

    it("Mostrar error si el nombre tiene más de dos palabras o caracteres inválidos", async () => {
        await abrirModal();
        const nombreInput = await esperarInput("nombreApellido");
        await nombreInput.sendKeys("Juan Carlos Pérez 123");

        const botonEnviar = await driver.findElement(By.id("btn-envio"));
        await driver.executeScript("arguments[0].click();", botonEnviar);

        const errorNombre = await verificarError("errNombre");
        expect((await errorNombre.getText()).trim().length).to.be.greaterThan(0);
    });

    it("Mostrar error si el correo es inválido", async () => {
        await abrirModal();
        const nombreInput = await esperarInput("nombreApellido");
        await nombreInput.sendKeys("Juan Pérez");

        const correoInput = await esperarInput("correo");
        await correoInput.sendKeys("correo-invalido");

        const botonEnviar = await driver.findElement(By.id("btn-envio"));
        await driver.executeScript("arguments[0].click();", botonEnviar);

        const errorCorreo = await verificarError("errCorreo");
        expect((await errorCorreo.getText()).trim().length).to.be.greaterThan(0);
    });

    it("Mostrar error si la contraseña no cumple requisitos", async () => {
        await abrirModal();
        const nombreInput = await esperarInput("nombreApellido");
        await nombreInput.sendKeys("Juan Pérez");

        const correoInput = await esperarInput("correo");
        await correoInput.sendKeys("juanperez@gmail.com");

        const passInput = await esperarInput("contrasena");
        await passInput.sendKeys("12345");

        const botonEnviar = await driver.findElement(By.id("btn-envio"));
        await driver.executeScript("arguments[0].click();", botonEnviar);

        const errorPass = await verificarError("errContrasena");
        expect((await errorPass.getText()).trim().length).to.be.greaterThan(0);
    });

    it("Mostrar error si la confirmación de contraseña no coincide", async () => {
        await abrirModal();
        const nombreInput = await esperarInput("nombreApellido");
        await nombreInput.sendKeys("Juan Pérez");

        const correoInput = await esperarInput("correo");
        await correoInput.sendKeys("juanperez@gmail.com");

        const passInput = await esperarInput("contrasena");
        await passInput.sendKeys("Contrasenna123!");

        const confirmInput = await esperarInput("confirmarContrasena");
        await confirmInput.sendKeys("OtraContrasena!");

        const botonEnviar = await driver.findElement(By.id("btn-envio"));
        await driver.executeScript("arguments[0].click();", botonEnviar);

        const errorConfirm = await verificarError("errConfirmarContrasena");
        expect((await errorConfirm.getText()).trim().length).to.be.greaterThan(0);
    });

    it("Mostrar error si la fecha de nacimiento es del futuro", async () => {
        await abrirModal();
        const nombreInput = await esperarInput("nombreApellido");
        await nombreInput.sendKeys("Juan Pérez");

        const correoInput = await esperarInput("correo");
        await correoInput.sendKeys("juanperez@gmail.com");

        const passInput = await esperarInput("contrasena");
        await passInput.sendKeys("Contrasenna123!");

        const confirmInput = await esperarInput("confirmarContrasena");
        await confirmInput.sendKeys("Contrasenna123!");

        const fechaInput = await esperarInput("fechaNacimiento");
        // Fecha futura
        const fechaFutura = new Date();
        fechaFutura.setFullYear(fechaFutura.getFullYear() + 1);
        const fechaStr = fechaFutura.toISOString().split("T")[0];
        await fechaInput.sendKeys(fechaStr);

        const botonEnviar = await driver.findElement(By.id("btn-envio"));
        await driver.executeScript("arguments[0].click();", botonEnviar);

        const errorFecha = await verificarError("errNacimiento");
        expect((await errorFecha.getText()).trim().length).to.be.greaterThan(0);
    });
});
