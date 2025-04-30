from python_processing.image_processor import procesar_con_python
from flask import Flask, request, jsonify
from flask_cors import CORS
import subprocess  # Para ejecutar R
import os

app = Flask(__name__)
CORS(app)  # Permite todas las rutas


app = Flask(__name__)
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

    # Procesar con Python
    resultado_python = procesar_con_python(temp_path)

    # Procesar con R (ejemplo)
    resultado_r = subprocess.run(
        ['Rscript', 'r_processing/image_analysis.R', temp_path],
        capture_output=True,
        text=True
    ).stdout

    # Eliminar archivo temporal
    os.remove(temp_path)

    return jsonify({
        "python": resultado_python,
        "r": resultado_r.strip()
    })

if __name__ == '__main__':
    os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)
    app.run(debug=True)