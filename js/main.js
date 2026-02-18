import * as fh from './form-handler.js'
import {mostrarPasswd} from './ui.js'

// Si estamos en GitHub Pages, usará el nombre del repo. 
// Si estamos en Local (localhost), se quedará vacío para buscar en la raíz del servidor.
const repo = window.location.hostname.includes('github.io') 
             ? '/proyecto-agile-intermodular' 
             : '';

async function cargarComponente(idPadre, rutaArchivo) {
    const contenedor = document.getElementById(idPadre);
    if (!contenedor) return;

    try {
        const respuesta = await fetch(rutaArchivo);
        if (!respuesta.ok) throw new Error(`Error ${respuesta.status}`);
        const html = await respuesta.text();
        contenedor.innerHTML = html;
    } catch (err) {
        console.error("Error cargando:", rutaArchivo, err);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
        // Ahora 'repo' es inteligente y se adapta al entorno
        await cargarComponente('footer-main', `${repo}/componentes/footer.html`);
    });

    const form = document.getElementById('form-envio-1');

    //Objeto que almacena referencias a todos los campos del formulario
    const camposFormulario = {
        nombreApe: document.getElementById('nombreApellido'),
        correo: document.getElementById('correo'),
        pass: document.getElementById('contrasena'),
        sexo: document.querySelector('input[name="sexo"]:checked')?.value || "",
        fechaN: document.getElementById('fechaNacimiento'),
        direccion: document.getElementById('direccion'),
        pais: document.getElementById('pais'),
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

    camposFormulario.direccion.addEventListener('input', fh.inputDireccion);
    camposFormulario.pais.addEventListener('input', fh.inputPais);
    camposFormulario.tarjeta.addEventListener('input', fh.actualizarVisibilidadTarjeta)

    //Event listener para el icono de mostrar/ocultar contraseña
    icono_ojito.addEventListener('click', e => {
        mostrarPasswd(icono_ojito, camposFormulario.pass);
    })

    //Event listener para el evento submit del formulario
    form.addEventListener('submit', (e) => {
        fh.manejarSubmit(e, camposFormulario);
    });