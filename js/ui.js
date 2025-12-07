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

export function mensajeErrorFecha(mensaje){
    document.getElementById("errNacimiento").textContent = mensaje;
}

export function limpiarMensajeErrorFecha(){
    document.getElementById("errNacimiento").textContent = "";
}

export function mostrarMensajeExito(mensaje){
    document.getElementById("mensajeExito").textContent = mensaje;
}

export function limpiarFormulario(form){
    form.reset();
}
