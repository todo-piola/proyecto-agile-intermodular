import { useState, useEffect } from "react";
import { validarCorreo, validarContrasennia } from "@js/registerauth/validators.js";

export default function LoginForm() {
  const estadoVacio = { correo: "", password: "", crear: false };

  const [form, setForm] = useState(estadoVacio);
  const [errores, setErrores] = useState({});
  const [enviando, setEnviando] = useState(false);

  // Resetea el formulario cuando Bootstrap termina de cerrar el modal
  useEffect(() => {
    const modal = document.getElementById("modalLogin");
    const handleClose = () => {
      setForm(estadoVacio);
      setErrores({});
    };
    modal?.addEventListener("hidden.bs.modal", handleClose);
    return () => modal?.removeEventListener("hidden.bs.modal", handleClose);
  }, []);

  // Valida correo y contraseña en tiempo real mientras el usuario escribe
  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setForm(prev => ({ ...prev, [name]: type === "checkbox" ? checked : value }));

    if (name === "correo") {
      const r = validarCorreo(value);
      setErrores(prev => ({ ...prev, correo: r.boolean ? "" : r.errMensaje }));
    }
    if (name === "password") {
      const r = validarContrasennia(value);
      setErrores(prev => ({ ...prev, password: r.boolean ? "" : r.errMensaje }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // El admin tiene contraseña especial en el servidor, no se valida en el cliente
    const esAdmin = form.correo.trim() === "admin@admin.com";
    const errCorreo = validarCorreo(form.correo);
    const errPass   = esAdmin ? { boolean: true } : validarContrasennia(form.password);

    if (!errCorreo.boolean || !errPass.boolean || !form.crear) {
      setErrores({
        correo:  errCorreo.boolean ? "" : errCorreo.errMensaje,
        password: errPass.boolean  ? "" : errPass.errMensaje,
        cookies: !form.crear ? "Debes aceptar la política de cookies" : ""
      });
      return;
    }

    setEnviando(true);
    // El formulario tiene action y method definidos, submit() lo envía al PHP directamente
    e.target.submit();
  };

  return (
    <div className="modal fade" id="modalLogin" tabIndex="-1" aria-hidden="true">
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content bg-dark text-white">

          <div className="modal-header border-secondary">
            <h5 className="modal-title">Iniciar sesión</h5>
            <button type="button" className="btn-close btn-close-white" data-bs-dismiss="modal" />
          </div>

          <div className="modal-body">
            {/* action y method envían el formulario a PHP sin fetch */}
            <form method="POST" action="/proyecto-agile-intermodular/php/login.php" onSubmit={handleSubmit} noValidate>
              <input type="hidden" name="login" value="1" />

              <div className="mb-3">
                <label className="form-label">Correo</label>
                <input type="text" name="correo" className="form-control item-form-login"
                  placeholder="Correo" value={form.correo} onChange={handleChange} />
                {errores.correo && <small className="text-danger">{errores.correo}</small>}
              </div>

              <div className="mb-3">
                <label className="form-label">Contraseña</label>
                <input type="password" name="password" className="form-control item-form-login"
                  placeholder="Contraseña" value={form.password} onChange={handleChange} />
                {errores.password && <small className="text-danger">{errores.password}</small>}
              </div>

              <div className="form-check mb-3">
                <input type="checkbox" name="crear" id="crear"
                  className="form-check-input" checked={form.crear} onChange={handleChange} />
                <label className="form-check-label" htmlFor="crear">Aceptar política de cookies</label>
                {errores.cookies && <div><small className="text-danger">{errores.cookies}</small></div>}
              </div>

              <div className="d-grid">
                <button className="btn btn-outline-warning" type="submit" disabled={enviando}>
                  {enviando ? "Entrando..." : "Entrar"}
                </button>
              </div>
            </form>

            {/* Bootstrap gestiona el cambio entre modales con sus atributos data-bs */}
            <div className="text-center mt-3">
              <a href="#" className="text-warning text-decoration-none small"
                data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal">
                ¿No tienes cuenta? ¡Regístrate en pocos pasos!
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  );
}