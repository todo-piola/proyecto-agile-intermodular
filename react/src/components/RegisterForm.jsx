import React, { useState } from "react";
import { validarNombreApellido, validarCorreo, validarContrasennia, validarComprobacionPassword, validarFecha, validarTarjeta } from "@js/registerauth/validators.js";

const PAISES = [
  "Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan",
  "Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia (Plurinational State of)",
  "Bosnia and Herzegovina","Botswana","Brazil","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cabo Verde","Cambodia",
  "Cameroon","Canada","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo",
  "Congo, Democratic Republic of the","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Côte d'Ivoire","Denmark",
  "Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia",
  "Eswatini (Swaziland)","Ethiopia","Fiji","Finland","France","Gabon","Gambia","Georgia","Germany","Ghana","Greece",
  "Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia",
  "Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, North",
  "Korea, South","Kuwait","Kyrgyzstan","Lao People's Democratic Republic","Latvia","Lebanon","Lesotho","Liberia","Libya",
  "Liechtenstein","Lithuania","Luxembourg","Macedonia North","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta",
  "Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco",
  "Mozambique","Myanmar (Burma)","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria",
  "Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal",
  "Qatar","Romania","Russian Federation","Rwanda","Saint Kitts and Nevis","Saint Lucia",
  "Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia",
  "Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Sudan",
  "Spain","Sri Lanka","Sudan","Suriname","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand",
  "Timor-Leste","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey (Türkiye)","Turkmenistan","Tuvalu","Uganda",
  "Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu",
  "Vatican City Holy See","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"
];

function Campo({ label, error, valido, children, requerido }) {
  return (
    <div className={`mb-3 field ${error ? "incorrecto" : valido ? "valido" : ""}`}>
      <label className="form-label">{label} {requerido && <span className="asterisco">*</span>}</label>
      {children}
      {error && <p className="error">{error}</p>}
    </div>
  );
}

export default function RegisterForm({ onSwitchToLogin }) {
  const [form, setForm] = useState({
    nombre: "", correo: "", password: "", confirmacion: "",
    pais: "", tarjeta: "", sexo: "", fecha: "",
    notificaciones: false, revista: false
  });

  const [errores, setErrores] = useState({});
  const [validos, setValidos] = useState({});
  const [showPass, setShowPass] = useState(false);
  const [mensajeExito, setMensajeExito] = useState("");
  const [enviando, setEnviando] = useState(false);

  const setError = (campo, msg) => {
    setErrores(prev => ({ ...prev, [campo]: msg }));
    setValidos(prev => ({ ...prev, [campo]: false }));
  };

  const setValido = (campo) => {
    setErrores(prev => ({ ...prev, [campo]: "" }));
    setValidos(prev => ({ ...prev, [campo]: true }));
  };

  const validarCampo = (campo, valor) => {
    switch (campo) {
      case "nombre": {
        const r = validarNombreApellido(valor);
        r.boolean ? setValido("nombre") : setError("nombre", r.errMensaje);
        return r.boolean;
      }
      case "correo": {
        const r = validarCorreo(valor);
        r.boolean ? setValido("correo") : setError("correo", r.errMensaje);
        return r.boolean;
      }
      case "password": {
        const r = validarContrasennia(valor);
        r.boolean ? setValido("password") : setError("password", r.errMensaje);
        return r.boolean;
      }
      case "confirmacion": {
        const r = validarComprobacionPassword(valor, form.password);
        r.boolean ? setValido("confirmacion") : setError("confirmacion", r.errMensaje);
        return r.boolean;
      }
      case "fecha": {
        if (!valor) return true;
        const r = validarFecha(valor);
        r.boolean ? setValido("fecha") : setError("fecha", r.errMensaje);
        return r.boolean;
      }
      case "tarjeta": {
        if (!valor) return true;
        const r = validarTarjeta(valor);
        r.boolean ? setValido("tarjeta") : setError("tarjeta", r.errMensaje);
        return r.boolean;
      }
      default: return true;
    }
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    const val = type === "checkbox" ? checked : value;
    setForm(prev => ({ ...prev, [name]: val }));
    if (["nombre","correo","password","fecha","tarjeta"].includes(name)) {
      validarCampo(name, value);
    }
    if (name === "confirmacion") {
      const r = validarComprobacionPassword(value, form.password);
      r.boolean ? setValido("confirmacion") : setError("confirmacion", r.errMensaje);
    }
  };

  const todoValido = () => {
    return (
      validarCampo("nombre", form.nombre) &&
      validarCampo("correo", form.correo) &&
      validarCampo("password", form.password) &&
      validarCampo("confirmacion", form.confirmacion) &&
      validarCampo("fecha", form.fecha) &&
      validarCampo("tarjeta", form.tarjeta)
    );
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMensajeExito("");

    if (!todoValido()) {
      setMensajeExito("Revisa los errores en el formulario");
      return;
    }

    setEnviando(true);

    try {
      const payload = new URLSearchParams({
        nombre: form.nombre,
        correo: form.correo,
        password: form.password,
        pais: form.pais,
        sexo: form.sexo,
        fecha: form.fecha,
        notificaciones: form.notificaciones ? 1 : 0,
        revista: form.revista ? 1 : 0,
        tarjeta: form.tarjeta
      });

      const res = await fetch("/proyecto-agile-intermodular/php/registro.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: payload
      });

      const data = await res.json();

      if (data.success) {
        setMensajeExito("✅ Usuario registrado correctamente");
        setForm({
          nombre: "", correo: "", password: "", confirmacion: "",
          pais: "", tarjeta: "", sexo: "", fecha: "",
          notificaciones: false, revista: false
        });
        setErrores({});
        setValidos({});
      } else {
        setMensajeExito(data.errores.join(" | "));
      }
    } catch (err) {
      setMensajeExito("Error al conectar con el servidor");
    } finally {
      setEnviando(false);
    }
  };

  return (
    <div className="modal fade" id="modal" tabIndex="-1" aria-hidden="true">
      <div className="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div className="modal-content bg-dark text-white">
          <div className="modal-header border-warning">
            <h4 className="modal-title">Regístrate en pocos pasos</h4>
            <button type="button" className="btn-close bg-white" data-bs-dismiss="modal"></button>
          </div>
          <div className="modal-body">
            <form id="form-envio-1" onSubmit={handleSubmit} noValidate>

              <Campo label="Nombre y Apellido" error={errores.nombre} valido={validos.nombre} requerido>
                <input
                  type="text" name="nombre" className="form-control"
                  value={form.nombre} onChange={handleChange} required
                />
              </Campo>

              <Campo label="Email" error={errores.correo} valido={validos.correo} requerido>
                <input
                  type="text" name="correo" className="form-control"
                  value={form.correo} onChange={handleChange} required
                />
              </Campo>

              <Campo label={
                <span>Contraseña <span className="asterisco">*</span>
                  <i
                    className={`bi ${showPass ? "bi-eye-slash-fill" : "bi-eye-fill"} toggle-pass ms-2`}
                    style={{ cursor: "pointer" }}
                    onClick={() => setShowPass(p => !p)}
                  />
                </span>
              } error={errores.password} valido={validos.password}>
                <input
                  type={showPass ? "text" : "password"} name="password" className="form-control"
                  value={form.password} onChange={handleChange} required
                />
              </Campo>

              <Campo label="Confirmar Contraseña" error={errores.confirmacion} valido={validos.confirmacion} requerido>
                <input
                  type="password" name="confirmacion" className="form-control"
                  value={form.confirmacion} onChange={handleChange} required
                />
              </Campo>

              <div className="mb-3 field">
                <select name="pais" className="form-select" value={form.pais} onChange={handleChange}>
                  <option value="">País de residencia</option>
                  {PAISES.map(p => <option key={p} value={p}>{p}</option>)}
                </select>
              </div>

              {form.pais && (
                <Campo label="Tarjeta bancaria" error={errores.tarjeta} valido={validos.tarjeta}>
                  <input
                    type="text" name="tarjeta" className="form-control"
                    value={form.tarjeta} onChange={handleChange}
                  />
                </Campo>
              )}

              <fieldset className="form-check mb-3">
                <label className="form-check-label d-block mb-1">Sexo</label>
                {["masculino","femenino","prefiero-no-decir"].map(s => (
                  <div className="form-check" key={s}>
                    <input
                      className="form-check-input" type="radio" name="sexo"
                      id={s} value={s} checked={form.sexo === s} onChange={handleChange}
                    />
                    <label className="form-check-label" htmlFor={s}>
                      {s.charAt(0).toUpperCase() + s.slice(1).replace("-", " ")}
                    </label>
                  </div>
                ))}
              </fieldset>

              <div className="mb-3">
                <label className="form-label">Fecha de Nacimiento</label>
                <input
                  type="date" name="fecha" className="form-control"
                  value={form.fecha} onChange={handleChange}
                />
                {errores.fecha && <p className="error">{errores.fecha}</p>}
              </div>

              <div className="form-check mb-2">
                <input
                  className="form-check-input" type="checkbox" name="notificaciones"
                  id="notificaciones" checked={form.notificaciones} onChange={handleChange}
                />
                <label className="form-check-label" htmlFor="notificaciones">Activar notificaciones</label>
              </div>

              <div className="form-check mb-3">
                <input
                  className="form-check-input" type="checkbox" name="revista"
                  id="revistaDigital" checked={form.revista} onChange={handleChange}
                />
                <label className="form-check-label" htmlFor="revistaDigital">Recibir revista digital</label>
              </div>

              {mensajeExito && (
                <p className={`mb-2 ${mensajeExito.startsWith("✅") ? "text-success" : "text-danger"}`}>
                  {mensajeExito}
                </p>
              )}

              <div className="modal-footer border-warning px-0">
                <button type="submit" className="btn btn-warning" disabled={enviando}>
                  {enviando ? "Enviando..." : "Enviar formulario"}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
}
