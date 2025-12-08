import * as fh from './form-hadler.js'
import * as u from './ui.js'

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('form-envio-1');

    const nombreApe = document.getElementById('nombreApellido');
    const email = document.getElementById('correo');
    const password = document.getElementById('contrasena');
    const doubleCheck = document.getElementById('confirmarContrasena');
    const fechaN = document.getElementById('fechaNacimiento');
    const icono_ojito = document.querySelector('.bi')

    nombreApe.addEventListener('input', fh.inputNombreApellido);
    email.addEventListener('input', fh.inputCorreo);
    password.addEventListener('input', fh.inputPasswd);
    doubleCheck.addEventListener('input', fh.inputConfirmacion);

    fechaN.addEventListener('input', fh.inputFecha);

    icono_ojito.addEventListener('click', e => {
        u.mostrarPasswd(icono_ojito, password);
    })

    form.addEventListener('submit', fh.manejarSubmit);
})