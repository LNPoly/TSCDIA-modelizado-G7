document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.image-upload').forEach(input => {
        const tabName = input.getAttribute('data-tab');
        
        const status = document.querySelector(`.file-status[data-tab="${tabName}"]`);
        const previewContainer = document.querySelector(`.image-preview[data-tab="${tabName}"]`);
        const previewImage = previewContainer.querySelector('.preview-img');
        const clearBtn = document.querySelector(`.clear-btn[data-tab="${tabName}"]`);
        const resultContainer = document.querySelector(`.result-container[data-tab="${tabName}"]`);
        
        // Configurar relación label-input
        const inputId = `imageUpload_${tabName}`;
        input.id = inputId;
        const label = document.querySelector(`.upload-label[data-tab="${tabName}"]`);
        label.setAttribute('for', inputId);

        // Evento para selección de imagen
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png'];
                
                if (!allowedTypes.includes(file.type)) {
                    status.innerHTML = `<span class="text-danger">Error: Solo archivos JPG/PNG</span>`;
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    clearBtn.style.display = 'inline-block';
                    resultContainer.style.display = 'block';
                    
                    if(tabName === 'PAJAROS') {
                        document.getElementById(`birdResult-${tabName}`).textContent = "--"; // Temporal
                        document.getElementById(`percentageResult-${tabName}`).textContent = "--"; // Temporal
                    } else if(tabName === 'PERROSYGATOS') {
                        document.getElementById(`result-type-${tabName}`).textContent = "--"; // Temporal
                        document.getElementById(`result-percentage-${tabName}`).textContent = "--"; // Temporal
                    }
                };
                reader.readAsDataURL(file);
                
                status.innerHTML = `<span class="text-success">Archivo: ${file.name} (${(file.size/1024).toFixed(1)} KB)</span>`;
            }
        });

        // Evento para botón de limpieza
        clearBtn.addEventListener('click', function() {
            input.value = '';
            previewContainer.style.display = 'none';
            status.innerHTML = '';
            clearBtn.style.display = 'none';
            resultContainer.style.display = 'none';
            
            // Limpiar resultados según la pestaña
            if(tabName === 'PAJAROS') {
                document.getElementById(`birdResult-${tabName}`).textContent = '--';
                document.getElementById(`percentageResult-${tabName}`).textContent = '--';
            } else if(tabName === 'PERROSYGATOS') {
                document.getElementById(`animalResult-${tabName}`).textContent = '--';
                document.getElementById(`percentageResult-${tabName}`).textContent = '--';
            }
        });
    });
});