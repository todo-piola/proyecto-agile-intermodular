import * as fh from './form-handler.js'
import {mostrarPasswd} from './ui.js'

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('form-envio-1');

    //Objeto que almacena referencias a todos los campos del formulario
    const camposFormulario = {
        nombreApe: document.getElementById('nombreApellido'),
        correo: document.getElementById('correo'),
        pass: document.getElementById('contrasena'),
        sexo: document.querySelector('input[name="sexo"]:checked')?.value || "",
        fechaN: document.getElementById('fechaNacimiento'),
        tarjeta: document.getElementById('tarjeta'),
        notificaciones: document.getElementById('notificaciones'),
        revista: document.getElementById('revista')
    }

    //Campo adicional para confirmación de contraseña
    const doubleCheck = document.getElementById('confirmarContrasena');

    //Icono de ojo para mostrar/ocultar contraseña (usa clase Bootstrap)
    const icono_ojito = document.querySelector('.bi')

    //Asigna event listeners a cada campo para validación en tiempo real
    camposFormulario.nombreApe.addEventListener('input', fh.inputNombreApellido);
    camposFormulario.correo.addEventListener('input', fh.inputCorreo);
    camposFormulario.pass.addEventListener('input', fh.inputPasswd);
    doubleCheck.addEventListener('input', fh.inputConfirmacion);

    camposFormulario.fechaN.addEventListener('input', fh.inputFecha);
    camposFormulario.tarjeta.addEventListener('input', fh.inputTarjeta)

    //Event listener para el icono de mostrar/ocultar contraseña
    icono_ojito.addEventListener('click', e => {
        mostrarPasswd(icono_ojito, camposFormulario.pass);
    })

    //Event listener para el evento submit del formulario
    form.addEventListener('submit', (e) => {
        fh.manejarSubmit(e, camposFormulario);
    });
})