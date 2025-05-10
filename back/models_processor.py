# model_processor.py
import tensorflow as tf
import cv2
import numpy as np

def load_model():
    """Carga el modelo entrenado"""
    try:
        model = tf.keras.models.load_model('model/Clasificador_de_perros_y_gatos.h5')
        return model
    except Exception as e:
        print(f"Error cargando el modelo: {e}")
        return None

def preprocess_image(image_path, tamano=100):
    """Preprocesa la imagen para el modelo"""
    img = cv2.imread(image_path)
    if img is None:
        return None
    # Convertir a escala de grises y redimensionar
    img_gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    img_resized = cv2.resize(img_gray, (tamano, tamano))
    # Normalizar y reshape
    img_normalized = img_resized / 255.0
    img_expanded = img_normalized.reshape((tamano, tamano, 1))
    return img_expanded

def classify_image(model, image_path):
    """Clasifica la imagen entre perro o gato"""
    if model is None:
        return None
    
    img_processed = preprocess_image(image_path)
    if img_processed is None:
        return None
    
    prediction = model.predict(np.expand_dims(img_processed, axis=0))
    return prediction