document.addEventListener("DOMContentLoaded", async () => {
  await cargarTemplate("encabezado", "/templates/header.html");
  await cargarTemplate("carrito", "/templates/carrito.html");
    //insertarLupas(); // Hasta que header.html no cargue, no se insertan las lupas
    await cargarTemplate("piepagina", "/templates/footer.html");
  });
  
  /**
   * Carga un template HTML dentro de un contenedor
   * @param {string} idContenedor
   * @param {string} ruta
   */

  async function cargarTemplate(idContenedor, ruta) {
    const contenedor = document.getElementById(idContenedor);
    if (!contenedor) return;

    // En la capa PHP el header/footer ya vienen renderizados por include,
    // asi que solo inyectamos templates en placeholders vacios.
    const esPlaceholder = contenedor.tagName === "DIV" && contenedor.children.length === 0;
    if (!esPlaceholder) return;
  
    try {
      const response = await fetch(ruta);
        
      if (!response.ok) throw new Error(`Error status: ${response.status}`);

      const data = await response.text(); // En vez de .json() se usa .text() para los templates HTML

      contenedor.innerHTML = data;    

    } catch (err) {
      console.error(`Error cargando ${ruta}:`, err);
    }
  }
  
  /**
   * Inserta las lupas en los contenedores correspondientes

  function insertarLupas() {
    const lupas = ["contenedor-lupa", "contenedor-lupa-movil"];
  
    lupas.forEach(id => {
      const contenedor = document.getElementById(id);
      if (contenedor) {
          if (contenedor.querySelector("#barraBusqueda") || contenedor.querySelector("#lupaBtn")) {
              return;
          }

          const barraBusqueda = document.createElement("input");
          barraBusqueda.id = "barraBusqueda";
          barraBusqueda.type = "search";
          barraBusqueda.className = "form-control me-2";
          barraBusqueda.placeholder = "Buscar...";
          barraBusqueda.ariaLabel = "Buscar";


          const lupa = document.createElement("i");
          lupa.id = "lupaBtn";
          lupa.className = "bi bi-search fs-4 text-warning bg-black mx-2 rounded";
          lupa.style.cursor = "pointer";

          contenedor.appendChild(barraBusqueda);
          contenedor.appendChild(lupa);
      }
    });
  }
     */