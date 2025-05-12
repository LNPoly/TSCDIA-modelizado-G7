#logica de python (lo que hizo nico)
# backend/python_processing/image_processor.py
import cv2 # si no está instalado, en terminal escribir: pip install opencv-python-headless
import numpy as np

def process_with_python(image_path):
    """
    Ejemplo básico: Detecta bordes y devuelve metadata de la imagen.
    """
    # Cargar imagen
    img = cv2.imread(image_path)
    
    if img is None:
        return "Error: No se pudo cargar la imagen"

    # Procesamiento (ej: detección de bordes)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    edges = cv2.Canny(gray, 100, 200)

    # Metadata básica
    height, width, channels = img.shape
    return {
        "format": "JPEG",
        "dimensions": f"{width}x{height}",
        "edges_detected": np.sum(edges > 0)  # Pixeles con bordes
    }