import * as fh from './form-handler.js'
import {mostrarPasswd} from './ui.js'
import { getPeliculasSemana, getPeliculasMejorValoradas } from './movie-service.js';
import { renderizarPeliculas, mostrarError } from './movie-ui.js';

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

    /* =========== FORMULARIO REGISTRO =============== */
    const form = document.getElementById('form-envio-1');

    if(form) {
        const camposFormulario = {  //Objeto que almacena referencias a todos los campos del formulario
            nombreApe: document.getElementById('nombreApellido'),
            correo: document.getElementById('correo'),
            pass: document.getElementById('contrasena'),
            sexo: document.querySelector('input[name="sexo"]:checked')?.value || "",
            fechaN: document.getElementById('fechaNacimiento'),
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

        //Event listener para el icono de mostrar/ocultar contraseña
        icono_ojito.addEventListener('click', e => {
            mostrarPasswd(icono_ojito, camposFormulario.pass);
        })
    

        form.addEventListener('submit', (e) => {
            fh.manejarSubmit(e, camposFormulario);
        })
    }

    /* ================ FORMULARIO LOGIN ================ */
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        const camposLogin = {
            correoLogin: document.getElementById('correoLogin'),
            contrasenaLogin: document.getElementById("contrasenaLogin")
        }
    
        // Login (pasa sus propios IDs de error)
        camposLogin.correoLogin.addEventListener('input', (e) => fh.inputCorreo(e, "errCorreoLogin"));
        camposLogin.contrasenaLogin.addEventListener('input', (e) => fh.inputPasswd(e, "errContrasenaLogin"));

        loginForm.addEventListener('submit', (e) => fh.manejarSubmit(e, camposLogin));
    }


    /* ================ TOGGLE ICONOS ================ */

  const corazon = document.querySelectorAll('.corazon');

    corazon.forEach(c => {
      c.addEventListener('click', () => {
          c.classList.toggle('bi-heart');
          c.classList.toggle('bi-heart-fill');
        });
    });



    /* ================ PELÍCULAS DE LA SEMANA ================ */

    try {
        const [masVistas, masGustadas] = await Promise.all([
          getPeliculasSemana(),
          getPeliculasMejorValoradas()
        ]);
        renderizarPeliculas(masVistas, 'grid-mas-vistas');
        renderizarPeliculas(masGustadas, 'grid-mas-gustadas');
      } catch (err) {
        mostrarError('grid-mas-vistas');
        mostrarError('grid-mas-gustadas');
        console.error(err);
    }
});