import {REGEX} from './Regex.js';


/**
 * Valida un nombre y apellido asegurando que solo contenga letras y espacios
 * @param {string} nomApe - Nombre y apellido a validar
 * @returns {Object} - Objeto con resultado booleano y mensaje de error
 */
export function validarNombreApellido(nomApe){
    const nombreLimpio = nomApe ? nomApe.trim() : '';

    if(REGEX.nombreApellido.test(nombreLimpio)){
        return {
            boolean: true,
            errMensaje: ""
        };
    }
    else{
        return {
            boolean: false,
            errMensaje: "Solo nombre y apellido. Numeros no permitidos"
        };
    }

}


/**
 * Valida un correo electrónico con múltiples verificaciones:
 * - Presencia de @
 * - Formato correcto de dominio
 * - Extensión válida
 * - Patrón general con regex
 * @param {string} correo - Correo electrónico a validar
 * @returns {Object} - Objeto con resultado booleano y mensaje de error
 */
export function validarCorreo(correo){
    const correoLimpio = correo ? correo.trim() : '';

    if(!correoLimpio.includes('@')){
        return {
            boolean: false,
            errMensaje: "Debe incluir @"
        };
    }

    const partes = correoLimpio.split('@');
    if(partes.length !== 2){
        return{
            boolean: true,
            errMensaje: "Formato de correo inválido",
        }
    }

    const [local, dominio] = partes;

    if (!dominio || !dominio.includes('.')){
        return {
            boolean: false,
            errMensaje: "El dominio debe tener una extensión (.com, .es, .uk, etc.)"
        };
    }

    const extension = dominio.split('.').pop();
    if (!extension || extension.length < 2){
        return {
            boolean: false,
            errMensaje: "El dominio debe tener una extensión válida (mín. 2 caracteres)"
        };
    }

    if(!REGEX.correo.test(correo)){
        return {
            boolean: false,
            errMensaje: "Formato de Email invalido"
        };
    }

    return {
        boolean: true,
        errMensaje: ""
    };
}


/**
 * Valida una contraseña según criterios de seguridad:
 * - Mínimo 8 caracteres
 * - Al menos una mayúscula, una minúscula, un número y un carácter especial
 * @param {string} passwd - Contraseña a validar
 * @returns {Object} - Objeto con resultado booleano y mensaje de error
 */
export function validarContrasennia(passwd){
    const passwdLimpia = passwd ? passwd.trim() : '';

    if (!REGEX.password.test(passwdLimpia)){
        return {
            boolean: false,
            errMensaje: "Contraseña no valida. Min 8 carácteres " +
                "una mayúscula, una minúscula, un número, un carácter especial"
        };
    }
    else{
        return {
            boolean: true,
            errMensaje: ""
        };
    }
}

/**
 * Compara dos contraseñas para verificar que coincidan
 * @param {string} passwdComprobada - Contraseña a comparar
 * @param {string} passwdOriginal - Contraseña original de referencia
 * @returns {Object} - Objeto con resultado booleano y mensaje de error
 */
export function validarComprobacionPassword(passwdComprobada, passwdOriginal){
    if (passwdComprobada !== passwdOriginal){
        return{
            boolean: false,
            errMensaje: "Las contraseñas no coinciden"
        }
    }

    return{
        boolean: true,
        errMensaje: ""
    }
}


/**
 * Valida que una fecha no sea futura respecto a la fecha actual
 * @param {string} fecha - Fecha a validar (en formato string)
 * @returns {Object} - Objeto con resultado booleano y mensaje de error
 */
export function validarFecha(fecha){
    const objFecha = new Date(fecha);
    const fechaActual = new Date();

    if (fechaActual < objFecha){
        return{
            boolean: false,
            errMensaje: "No puede seleccionar una fecha superior a la actual"
        }
    }

    return{
        boolean: true,
        errMensaje: "Fecha seleccionada correctamente"
    }
}

