// Función para cargar un archivo Excel desde una ruta específica
function cargarExcelDesdeRuta(rutaArchivo, idContenedor) {
    fetch(rutaArchivo)
      .then(response => response.arrayBuffer())
      .then(data => convertirExcelAHTML(data, idContenedor))
      .catch(error => console.error('Error al cargar el archivo:', error));
  }

  // Función para convertir datos de Excel a tabla HTML y renderizar en un contenedor específico
  function convertirExcelAHTML(data, idContenedor) {
    let workbook = XLSX.read(data, { type: 'array' });
    let nombreHoja = workbook.SheetNames[0];
    let hoja = workbook.Sheets[nombreHoja];
    let rango = XLSX.utils.decode_range(hoja['!ref']);
    
    let tablaHTML = '<table class="table table-bordered table-striped"><thead class="thead-dark"><tr>';

    // Obtener encabezados
    for (let C = rango.s.c; C <= rango.e.c; C++) {
      let celda = hoja[XLSX.utils.encode_cell({r: 0, c: C})];
      tablaHTML += `<th>${celda ? celda.v : ''}</th>`;
    }
    tablaHTML += '</tr></thead><tbody>';

    // Obtener las filas restantes
    for (let R = 1; R <= rango.e.r; R++) {
      tablaHTML += '<tr>';
      for (let C = rango.s.c; C <= rango.e.c; C++) {
        let celda = hoja[XLSX.utils.encode_cell({r: R, c: C})];
        tablaHTML += `<td>${celda ? celda.v : ''}</td>`;
      }
      tablaHTML += '</tr>';
    }

    tablaHTML += '</tbody></table>';

    // Renderizar la tabla en el contenedor correspondiente
    document.getElementById(idContenedor).innerHTML = tablaHTML;
  }

  // Función para cargar y renderizar múltiples archivos Excel al mismo tiempo
  function cargarMultiplesExcels() {
    const archivos = [
      { ruta: './doc/ECAD.xlsx', contenedor: 'tabla1' },
      { ruta: './doc/ESUB.xlsx', contenedor: 'tabla2' }
    ];

    archivos.forEach(archivo => {
      cargarExcelDesdeRuta(archivo.ruta, archivo.contenedor);
    });
  }

  // Cargar múltiples archivos al cargar la página
  window.onload = function() {
    cargarMultiplesExcels();
  }