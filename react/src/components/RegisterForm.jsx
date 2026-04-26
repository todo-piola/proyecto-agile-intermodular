// RegisterForm.jsx
import { useState, useEffect } from "react";
import {
  validarNombreApellido, validarCorreo, validarContrasennia,
  validarComprobacionPassword, validarFecha, validarTarjeta
} from "@js/registerauth/validators.js";
import { PAISES } from "@js/registerauth/paises.js";

export default function RegisterForm() {
  const estadoVacio = {
    nombre: "", correo: "", password: "", confirmacion: "",
    pais: "", tarjeta: "", sexo: "", fecha: "",
    notificaciones: false, revista: false
  };

  const [form, setForm] = useState(estadoVacio);
  const [errores, setErrores] = useState({});
  const [validos, setValidos] = useState({});
  const [mostrarPass, setMostrarPass] = useState(false);
  const [mensaje, setMensaje] = useState("");
  const [enviando, setEnviando] = useState(false);

  // Resetea el formulario cuando Bootstrap termina de cerrar el modal
  useEffect(() => {
    const modal = document.getElementById("modal");
    const handleClose = () => {
      setForm(estadoVacio);
      setErrores({});
      setValidos({});
      setMensaje("");
    };
    modal?.addEventListener("hidden.bs.modal", handleClose);
    return () => modal?.removeEventListener("hidden.bs.modal", handleClose);
  }, []);

  const marcarError = (campo, msg) => {
    setErrores(prev => ({ ...prev, [campo]: msg }));
    setValidos(prev => ({ ...prev, [campo]: false }));
  };
  const marcarValido = (campo) => {
    setErrores(prev => ({ ...prev, [campo]: "" }));
    setValidos(prev => ({ ...prev, [campo]: true }));
  };

  // Valida un campo individual y actualiza su estado visual
  const validarCampo = (campo, valor) => {
    let resultado;

    if (campo === "nombre")            resultado = validarNombreApellido(valor);
    else if (campo === "correo")       resultado = validarCorreo(valor);
    else if (campo === "password")     resultado = validarContrasennia(valor);
    else if (campo === "confirmacion") resultado = validarComprobacionPassword(valor, form.password);
    else if (campo === "fecha")        resultado = valor ? validarFecha(valor) : { boolean: true };
    else if (campo === "tarjeta")      resultado = valor ? validarTarjeta(valor) : { boolean: true };
    else return true;

    if (resultado.boolean) marcarValido(campo);
    else marcarError(campo, resultado.errMensaje);

    return resultado.boolean;
  };

  // Actualiza el estado del form y valida el campo en tiempo real
  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    const val = type === "checkbox" ? checked : value;
    setForm(prev => ({ ...prev, [name]: val }));
    validarCampo(name, value);
  };

  const getBasePath = () => {
    const pathname = window.location.pathname;
    const metaBase = document
      .querySelector('meta[name="app-base"]')
      ?.getAttribute("content")
      ?.trim();

    if (metaBase) return metaBase.replace(/\/+$/, "");

    const marker = "/proyecto-agile-intermodular";
    const idx = pathname.indexOf(marker);
    if (idx >= 0) return pathname.slice(0, idx + marker.length);

    const indexPos = pathname.lastIndexOf("/index.php");
    if (indexPos >= 0) return pathname.slice(0, indexPos);

    return "";
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMensaje("");

    // Valida todos los campos obligatorios antes de enviar
    const esValido =
      validarCampo("nombre", form.nombre) &&
      validarCampo("correo", form.correo) &&
      validarCampo("password", form.password) &&
      validarCampo("confirmacion", form.confirmacion) &&
      validarCampo("fecha", form.fecha) &&
      validarCampo("tarjeta", form.tarjeta);

    if (!esValido) {
      setMensaje("Revisa los errores en el formulario");
      return;
    }

    setEnviando(true);
    try {
      // Se envía JSON; el PHP lo lee con json_decode en lugar de $_POST
      const basePath = getBasePath();
      const res = await fetch(`${basePath}/php/registro.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(form),
      });

      if (!res.ok) {
        throw new Error("HTTP " + res.status);
      }

      const data = await res.json();

      if (data.success) {
        setMensaje("Usuario registrado correctamente");
        setForm(estadoVacio);
        setErrores({});
        setValidos({});
      } else {
        setMensaje(data.errores.join(" | "));
      }
    } catch {
      setMensaje("Error al conectar con el servidor");
    } finally {
      setEnviando(false);
    }
  };

  // Las clases valido/incorrecto van en el DIV contenedor, no en el input,
  // porque el icono de validación se posiciona con CSS relativo al div padre
  const claseContenedor = (campo) =>
    `mb-3 field ${errores[campo] ? "incorrecto" : validos[campo] ? "valido" : ""}`;

  return (
    <div className="modal fade" id="modal" tabIndex="-1" aria-hidden="true">
      <div className="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div className="modal-content bg-dark text-white">

          <div className="modal-header border-warning">
            <h4 className="modal-title">Regístrate en pocos pasos</h4>
            <button type="button" className="btn-close bg-white" data-bs-dismiss="modal" />
          </div>

          <div className="modal-body">
            <form onSubmit={handleSubmit} noValidate>

              <div className={claseContenedor("nombre")}>
                <label className="form-label">Nombre y Apellido <span className="asterisco">*</span></label>
                <input type="text" name="nombre" className="form-control" value={form.nombre} onChange={handleChange} />
                {errores.nombre && <p className="error">{errores.nombre}</p>}
              </div>

              <div className={claseContenedor("correo")}>
                <label className="form-label">Email <span className="asterisco">*</span></label>
                <input type="text" name="correo" className="form-control" value={form.correo} onChange={handleChange} />
                {errores.correo && <p className="error">{errores.correo}</p>}
              </div>

              <div className={claseContenedor("password")}>
                {/* El icono del ojo controla la visibilidad de la contraseña */}
                <label className="form-label">
                  Contraseña <span className="asterisco">*</span>
                  <i
                    className={`bi ${mostrarPass ? "bi-eye-slash-fill" : "bi-eye-fill"} toggle-pass ms-2`}
                    style={{ cursor: "pointer" }}
                    onClick={() => setMostrarPass(p => !p)}
                  />
                </label>
                <input type={mostrarPass ? "text" : "password"} name="password" className="form-control" value={form.password} onChange={handleChange} />
                {errores.password && <p className="error">{errores.password}</p>}
              </div>

              <div className={claseContenedor("confirmacion")}>
                <label className="form-label">Confirmar Contraseña <span className="asterisco">*</span></label>
                <input type="password" name="confirmacion" className="form-control" value={form.confirmacion} onChange={handleChange} />
                {errores.confirmacion && <p className="error">{errores.confirmacion}</p>}
              </div>

              <div className="mb-3">
                <select name="pais" className="form-select" value={form.pais} onChange={handleChange}>
                  <option value="">País de residencia</option>
                  {PAISES.map(p => <option key={p} value={p}>{p}</option>)}
                </select>
              </div>

              {/* La tarjeta solo se pide si se ha seleccionado un país */}
              {form.pais && (
                <div className={claseContenedor("tarjeta")}>
                  <label className="form-label">Tarjeta bancaria</label>
                  <input type="text" name="tarjeta" className="form-control" value={form.tarjeta} onChange={handleChange} />
                  {errores.tarjeta && <p className="error">{errores.tarjeta}</p>}
                </div>
              )}

              <fieldset className="mb-3">
                <label className="form-label d-block">Sexo</label>
                {["masculino", "femenino", "prefiero-no-decir"].map(s => (
                  <div className="form-check" key={s}>
                    <input className="form-check-input" type="radio" name="sexo" id={s} value={s} checked={form.sexo === s} onChange={handleChange} />
                    <label className="form-check-label" htmlFor={s}>
                      {s.charAt(0).toUpperCase() + s.slice(1).replace("-", " ")}
                    </label>
                  </div>
                ))}
              </fieldset>

              <div className={claseContenedor("fecha")}>
                <label className="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fecha" className="form-control" value={form.fecha} onChange={handleChange} />
                {errores.fecha && <p className="error">{errores.fecha}</p>}
              </div>

              <div className="form-check mb-2">
                <input className="form-check-input" type="checkbox" name="notificaciones" id="notificaciones" checked={form.notificaciones} onChange={handleChange} />
                <label className="form-check-label" htmlFor="notificaciones">Activar notificaciones</label>
              </div>

              <div className="form-check mb-3">
                <input className="form-check-input" type="checkbox" name="revista" id="revistaDigital" checked={form.revista} onChange={handleChange} />
                <label className="form-check-label" htmlFor="revistaDigital">Recibir revista digital</label>
              </div>

              {mensaje && (
                <p className={`mb-2 ${mensaje.startsWith("Usuario") ? "text-success" : "text-danger"}`}>
                  {mensaje}
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