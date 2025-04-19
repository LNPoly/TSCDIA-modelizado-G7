// modal poopup
function openForm() {
    document.getElementById("myForm").style.display = "block";
  }
  
  function closeForm() {
    document.getElementById("myForm").style.display = "none";
  }


  function showResponse(responseText) {
    const responseSection = document.getElementById("responseSection");
    responseSection.innerHTML = `<strong>${responseText}</strong>`;
}

function showResponse() {
    const userInput = document.getElementById("userInput");
    const responseSection = document.getElementById("responseSection");
    const selectedOption = parseInt(userInput.value);

    let response = "";

    switch (selectedOption) {
        case 1:
            response = "Aquí está la respuesta para los requisitos generales...";
            break;
        case 2:
            response = "Aquí está la respuesta para las etapas del proceso de ingreso...";
            break;
        case 3:
            response = "Aquí está la respuesta para la aptitud psicofísica...";
            break;
        case 4:
            response = "Aquí está la respuesta para los exámenes de ingreso...";
            break;
        default:
            response = "No se encontró una respuesta para tu consulta";
            break;
    }

    responseSection.innerHTML = `<strong>${response}</strong>`;
}


// Función para manejar la intersección
function manejarInterseccion(entries, observer) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Cuando el elemento entra en el viewport, agregar la clase "mostrar" al elemento específico
        entry.target.classList.add('mostrar');
      } else {
        // Cuando el elemento sale del viewport, quitar la clase "mostrar" para reiniciar el efecto
        entry.target.classList.remove('mostrar');
      }
    });
  }
  
  // Obtén todos los elementos que deseas observar
  const elementos = document.querySelectorAll('.mi-div');
  
  // Configura el observador para cada elemento
  elementos.forEach(elemento => {
    const observer = new IntersectionObserver(manejarInterseccion, { threshold: 0.5 }); // Umbral del 50%
    observer.observe(elemento);
  });

  function loadIframe(iframeUrl) {
    document.getElementById("mapaIframe").src = iframeUrl;
}

// script.js
document.addEventListener("DOMContentLoaded", function () {
  const elementos = document.querySelectorAll(".animate__animated");

  const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
          if (entry.isIntersecting) {
              entry.target.classList.add("animate__fadeIn");
          } else {
              entry.target.classList.remove("animate__fadeIn");
          }
      });
  });

  elementos.forEach((elemento) => {
      observer.observe(elemento);
  });
});