/**
 * Muestra un mensaje de error en un elemento específico y actualiza estilos del campo
 * @param {string} idError - ID del elemento HTML donde mostrar el mensaje
 * @param {string} mensaje - Texto del mensaje de error
 * @param {EventTarget} evento - Elemento que disparó el evento (input, select, etc.)
 */
export function mensajesError(idError, mensaje, evento){
    //Validación de que el elemento seleccionado exista
    const errElement = document.getElementById(idError);
    if (errElement) errElement.textContent = mensaje;

    if(evento){
        const wrapper = evento.closest(".field");
        if (wrapper){
            wrapper.classList.add("incorrecto");
            wrapper.classList.remove("valido");
        }
        else{
            wrapper.classList.add("incorrecto");
            wrapper.classList.remove("valido");
        }
    }
}


/**
 * Limpia el mensaje de error y restablece los estilos del campo a válido
 * @param {string} idError - ID del elemento HTML donde estaba el mensaje
 * @param {EventTarget} evento - Elemento que disparó el evento
 */
export function limpiarMensajesError(idError, evento){
    const errElement = document.getElementById(idError);
    if (errElement) errElement.textContent = "";

    if (evento){
        const wrapper = evento.closest(".field");
        if (wrapper){
            wrapper.classList.add("valido");
            wrapper.classList.remove("incorrecto");
        }
        else{
            wrapper.classList.add("valido");
            wrapper.classList.remove("incorrecto");
        }
    }

}


/**
 * Muestra un mensaje de error específico para el campo de fecha
 * @param {string} mensaje - Texto del mensaje de error
 */
export function mensajeErrorFecha(mensaje){
    document.getElementById("errNacimiento").textContent = mensaje;
}


/**
 * Limpia el mensaje de error del campo de fecha
 */
export function limpiarMensajeErrorFecha(){
    document.getElementById("errNacimiento").textContent = "";
}


/**
 * Muestra un mensaje de éxito en un elemento predefinido
 * @param {string} mensaje - Texto del mensaje de éxito
 */
export function mostrarMensajeExito(mensaje){
    document.getElementById("mensajeExito").textContent = mensaje;
}


/**
 * Alterna la visibilidad de una contraseña cambiando el tipo de input
 * y actualiza el icono visual
 * @param {HTMLElement} icono - Elemento del icono (ej: <i>)
 * @param {HTMLInputElement} contrasenna - Input de tipo password
 */
export function mostrarPasswd(icono,contrasenna){
    if (contrasenna.type === "password"){
        contrasenna.type = "text";
        icono.classList.remove("bi-eye-fill");
        icono.classList.add("bi-eye-slash-fill");
    }
    else{
        contrasenna.type = "password"
        icono.classList.add("bi-eye-fill");
        icono.classList.remove("bi-eye-slash-fill");
    }
}


/**
 * Restablece todos los campos de un formulario a sus valores por defecto
 * @param {HTMLFormElement} form - Formulario a limpiar
 */
export function limpiarFormulario(form){
    form.reset();
}
