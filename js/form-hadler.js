import * as v from './validaciones.js';
import * as u from './ui.js';
import {limpiarMensajeErrorFecha, mensajeErrorFecha} from "./ui.js";

const estadoInputs = {
    nombreApellido: false,
    correo: false,
    password: false,
    confirmacion: false,
    fecha: true
}

function cambiosEstadoInputs(clave, esValido){
    estadoInputs[clave] = !!esValido;
    return estadoInputs[clave];
}

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

function revalidarTodo() {
    return estadoInputs.nombreApellido &&
        estadoInputs.correo &&
        estadoInputs.password &&
        estadoInputs.confirmacion &&
        estadoInputs.fecha;
}

export function manejarSubmit(event){
    event.preventDefault();
    const form = event.target;

    const todoOk = revalidarTodo();

    if (todoOk){
        u.mostrarMensajeExito("Formulario enviado correctamente");
        u.limpiarFormulario(form);
    }
}