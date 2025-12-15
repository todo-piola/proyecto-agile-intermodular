//Modulo dedicado a las expresiones regulares para validar el formulario de la landing page
export const regex = {
    nombreApellido: /^(?!.*\s{2})[A-Za-zÁáÉéÍíÓóÚúÑñÜü\-']+(?:\s[A-Za-zÁáÉéÍíÓóÚúÑñÜü\-']+)?$/,
    correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
}