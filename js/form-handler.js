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
export function inputCorreo(e){
    const valor = e.target.value;

    const objFuncion = v.validarCorreo(valor);

    if (!objFuncion.boolean){
        u.mensajesError("errCorreo", objFuncion.errMensaje, e.target);
        cambiosEstadoInputs("correo", false);
        return false;
    }
    else{
        u.limpiarMensajesError("errCorreo", e.target);
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
export function inputPasswd(e){
    const valor = e.target.value;

    const objFuncion = v.validarContrasennia(valor);

    if (!objFuncion.boolean){
        u.mensajesError("errContrasena", objFuncion.errMensaje, e.target);
        cambiosEstadoInputs("password", false);
        return false;
    }
    else{
        u.limpiarMensajesError("errContrasena", e.target);
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
export function manejarSubmit(event, datosForm){
    event.preventDefault();
    const form = event.target;

    const todoOk = revalidarTodo();

    if (todoOk){
        u.mostrarMensajeExito("Formulario enviado correctamente");

        /*
        Esta variable (datos convertidos) no se utiliza, pero su objetivo es que cuando haya una conexión con el lado servidor
        los datos se puedan enviar correctamente y de forma segura.
         */
        const datosConvertidos = convertirDatosJSON(datosForm);
        u.limpiarFormulario(form);
    }
}