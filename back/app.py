
from python_processing.image_processor import process_with_python
from models_processor import classify_image
import numpy as np

import os
os.environ["TF_ENABLE_ONEDNN_OPTS"] = "0"

from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
from flask import Flask, request, jsonify
from flask_cors import CORS
import subprocess  # Para ejecutar R

app = Flask(__name__)
CORS(app)  # Permite todas las rutas

model =  os.path.join(os.path.dirname(__file__), "model", "Clasificador_de_perros_y_gatos.h5")
app.config['UPLOAD_FOLDER'] = 'temp'

# Ruta para procesar imágenes
@app.route('/process', methods=['POST'])
def process_image():
    if 'image' not in request.files:
        return jsonify({"error": "No se envió imagen"}), 400
    
    file = request.files['image']
    filename = file.filename
    temp_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
    file.save(temp_path)

    try:
        # Procesar con Python
        result_python = process_with_python(temp_path)
        
        # Clasificar con el modelo de TensorFlow
        prediction = classify_image(model, temp_path)
        result_model = "Perro" if prediction > 0.5 else "Gato"
        
        # Procesar con R
        result_r = subprocess.run(
            ['Rscript', 'r_processing/image_analysis.R', temp_path],
            capture_output=True,
            text=True
        ).stdout

        # Eliminar archivo temporal
        os.remove(temp_path)

        return jsonify({
            "python": result_python,
            "modelo": result_model,
            "r": result_r.strip()
        })

    except Exception as e:
        os.remove(temp_path)
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)
    app.run(debug=True)