import * as v from './validators.js';
import * as u from './ui.js';
import {limpiarMensajeErrorFecha, mensajeErrorFecha} from "./ui.js";

//Objeto que almacena el estado de validación de cada campo del formulario
//Inicialmente todos en false excepto fecha, dirección, pais y tarjeta que 
// al ser opcionales empiezan como true
const estadoInputs = {
    nombreApellido: false,
    correo: false,
    password: false,
    confirmacion: false,
    fecha: true,
    direccion: true,
    pais: true,
    tarjeta: true
}


/**
 * Actualiza el estado de validación de un campo específico
 * @param {string} clave - Nombre del campo a actualizar
 * @param {boolean} esValido - Estado de validación del campo
 * @returns {boolean} - Nuevo estado del campo
 */
function cambiosEstadoInputs(clave, esValido){
    estadoInputs[clave] = !!esValido;
    return estadoInputs[clave];
}


/**
 * Maneja la validación en tiempo real del campo nombre y apellido
 * Se ejecuta durante el evento input del campo correspondiente
 * @param {Event} e - Evento de entrada del campo
 * @returns {boolean} - Resultado de la validación
 */
export function inputNombreApellido(e){
    const valor = e.target.value;

    const objFuncion = v.validarNombreApellido(valor);

    if (!objFuncion.boolean){
        u.mensajesError("errNombre", objFuncion.errMensaje, e.target);
        cambiosEstadoInputs("nombreApellido", false);
        return false;
    }
    else{
        u.limpiarMensajesError("errNombre", e.target);
        cambiosEstadoInputs("nombreApellido", true);
        return true;
    }
}


/**
 * Maneja la validación en tiempo real del campo de correo electrónico
 * Se ejecuta durante el evento input del campo correspondiente
 * @param {Event} e - Evento de entrada del campo
 * @returns {boolean} - Resultado de la validación
 */
export function inputCorreo(e, idError = "errCorreo"){
    const valor = e.target.value;

    const objFuncion = v.validarCorreo(valor);

    if (!objFuncion.boolean){
        u.mensajesError(idError, objFuncion.errMensaje, e.target);
        cambiosEstadoInputs("correo", false);
        return false;
    }
    else{
        u.limpiarMensajesError(idError, e.target);
        cambiosEstadoInputs("correo", true);
        return true;
    }
}


/**
 * Maneja la validación en tiempo real del campo de contraseña principal
 * Se ejecuta durante el evento input del campo correspondiente
 * @param {Event} e - Evento de entrada del campo
 * @returns {boolean} - Resultado de la validación
 */
export function inputPasswd(e, idError = "errContrasena"){
    const valor = e.target.value;

    const objFuncion = v.validarContrasennia(valor);

    if (!objFuncion.boolean){
        u.mensajesError(idError, objFuncion.errMensaje, e.target);
        cambiosEstadoInputs("password", false);
        return false;
    }
    else{
        u.limpiarMensajesError(idError, e.target);
        cambiosEstadoInputs("password", true);
        return true;
    }
}


/**
 * Maneja la validación en tiempo real del campo de confirmación de contraseña
 * Compara con la contraseña original y valida coincidencia
 * @param {Event} e - Evento de entrada del campo
 * @returns {boolean} - Resultado de la validación
 */
export function inputConfirmacion(e){
    const valor = e.target.value;
    const passwrodOriginal = document.getElementById('contrasena').value.trim();

    const objFuncion = v.validarComprobacionPassword(valor, passwrodOriginal);

    if (!objFuncion.boolean){
        u.mensajesError("errConfirmarContrasena", objFuncion.errMensaje, e.target);
        cambiosEstadoInputs("confirmacion", false);
        return false;
    }
    else{
        u.limpiarMensajesError("errConfirmarContrasena", e.target);
        cambiosEstadoInputs("confirmacion", true);
        return true;
    }
}


/**
 * Maneja la validación en tiempo real del campo de fecha
 * Se ejecuta durante el evento input del campo correspondiente
 * @param {Event} e - Evento de entrada del campo
 * @returns {boolean} - Resultado de la validación
 */
export function inputFecha(e){
    const valor = e.target.value;

    const objFuncion = v.validarFecha(valor);

    if (!objFuncion.boolean){
        u.mensajeErrorFecha(objFuncion.errMensaje);
        cambiosEstadoInputs("fecha", false);
        return false;
    }
    else{
        u.limpiarMensajeErrorFecha();
        cambiosEstadoInputs("fecha", true);
        return true;
    }
}

/**
 * Maneja la validación en tiempo real del campo de la tarjeta bancaria
 * Se ejecuta durante el evento input del campo correspondiente
 * @param {Event} e - Evento de entrada del campo
 * @returns {boolean} - Resultado de la validación
 */

export function inputTarjeta(e){
    const valor = e.target.value;

    const objFuncion = v.validarTarjeta(valor);

    if (!objFuncion.boolean){
        u.mensajesError("errTarjeta", objFuncion.errMensaje, e.target);
        cambiosEstadoInputs("tarjeta", false);
        return false;
    }
    else{
        u.limpiarMensajesError("errTarjeta", e.target);
        cambiosEstadoInputs("tarjeta", true);
        return true;
    }
}

let direccionValor = ""
let paisValor = ""

export function inputDireccion(e) {
    direccionValor = e.target.value.trim();
    actualizarVisibilidadTarjeta();
}

export function inputPais(e) {
    paisValor = e.target.value;
    actualizarVisibilidadTarjeta();
}

export function actualizarVisibilidadTarjeta() {
    const campoTarjeta = document.getElementById('tarjeta');

    if (direccionValor !== "" && paisValor !== "") {
        campoTarjeta.style.display = "block";
    } else {
        campoTarjeta.style.display = "none";
    }
}

/**
 * Verifica si todos los campos del formulario están validados correctamente
 * @returns {boolean} - True si todos los campos son válidos
 */
function revalidarTodo() {
    return estadoInputs.nombreApellido &&
        estadoInputs.correo &&
        estadoInputs.password &&
        estadoInputs.confirmacion &&
        estadoInputs.fecha &&
        estadoInputs.tarjeta
}


/**
 * Convierte datos a JSON mediante serialización/deserialización
 * Crea una copia profunda del objeto para evitar referencias
 * @param {Object} datos - Objeto con los datos del formulario
 * @returns {Object} - Copia profunda de los datos en formato JSON limpio
 */
function convertirDatosJSON(datos){
    return JSON.parse(JSON.stringify(datos));
}


/**
 * Maneja el evento de envío del formulario
 * Realiza validación final, muestra mensaje de éxito y procesa datos
 * @param {Event} event - Evento submit del formulario
 * @param {Object} datosForm - Objeto con los datos del formulario a procesar
 */
export async function manejarSubmit(event, datosForm){
    event.preventDefault();
    const form = event.target;

    // Validación final en frontend
    if(!revalidarTodo()){
        u.mostrarMensajeExito("Revisa los errores en el formulario");
        return;
    }
    const sexoSeleccionado = document.querySelector('input[name="sexo"]:checked')?.value || "";
    const paisSeleccionado = document.getElementById('pais').value;
    // Preparamos todos los datos del formulario para PHP
    const payload = {
        nombre: datosForm.nombreApe.value,
        correo: datosForm.correo.value,
        password: datosForm.pass.value,
        pais: paisSeleccionado,
        sexo: sexoSeleccionado,
        fecha: datosForm.fechaN?.value || "",
        notificaciones: datosForm.notificaciones?.checked ? 1 : 0,
        revista: datosForm.revista?.checked ? 1 : 0,
        crear: datosForm.crear?.checked ? 1 : 0
    };

    try {
        const respuesta = await fetch('bd/registro.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(payload)
        });

        const textData = await respuesta.text(); // <- obtener texto crudo
        console.log("Respuesta cruda de PHP:", textData);

        const data = JSON.parse(textData); // parsear después para ver si falla
        console.log("Respuesta parseada:", data);

        if(data.success){
            u.mostrarMensajeExito("Usuario registrado correctamente");
            u.limpiarFormulario(form);
        } else {
            u.mostrarMensajeExito("");
            data.errores.forEach(err => {
                alert(err);
            });
        }

    } catch(err){
        console.error("Error en registro:", err);
        u.mostrarMensajeExito("Ocurrió un error al registrar");
    }
}