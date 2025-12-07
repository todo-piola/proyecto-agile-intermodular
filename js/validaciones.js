import {REGEX} from './Regex.js';

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

