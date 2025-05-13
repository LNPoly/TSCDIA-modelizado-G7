# args <- commandArgs(trailingOnly = TRUE)
# image_path <- args[1]

# # Ejemplo: Usando el paquete "magick" para obtener dimensiones
# library(magick)

# img <- image_read(image_path)
# info <- image_info(img)
# cat(paste("Ancho:", info$width, "| Alto:", info$height))


# ----------- INSTALACIÓN DE DEPENDENCIAS -----------
# Instalar paquetes del sistema necesarios para ImageMagick
system("sudo apt-get update", intern = TRUE)
system("sudo apt-get install -y imagemagick libmagick++-dev", intern = TRUE)

# Instalar paquetes de R necesarios
if(!require(pacman)) install.packages("pacman")
pacman::p_load(httr, googledrive, magick, dplyr, purrr, tidyr, furrr, future)

# ----------- AUTENTICACIÓN EN GOOGLE DRIVE -----------
drive_auth(email = "avincenti@iusm.edu.ar")  # Sigue las instrucciones en consola

# ----------- CONFIGURACIÓN INICIAL -----------
plan(multisession, workers = 2)  # Procesamiento paralelo

# ID de la carpeta en Drive (debes reemplazarlo con tu ID real)
folder_id <- "TU_ID_DE_CARPETA_AQUI"

# ----------- FUNCIONES AUXILIARES -----------
# Función para crear/verificar carpeta de salida
create_output_dir <- function() {
  output_dir <- "procesadas_en_drive"
  existing_folders <- drive_ls(as_id(folder_id)) %>%
    filter(name == output_dir,
           map_lgl(drive_resource, ~.x$mimeType == "application/vnd.google-apps.folder"))
  
  if(nrow(existing_folders) == 0) {
    drive_mkdir(name = output_dir, path = as_id(folder_id))$id
  } else {
    existing_folders$id[1]
  }
}

# Función para obtener archivos de Drive
get_drive_files <- function() {
  tryCatch({
    drive_ls(path = as_id(folder_id), recursive = TRUE) %>%
      filter(
        map_lgl(drive_resource, ~ .x$kind == "drive#file"),
        grepl("\\.(jpg|jpeg|png|webp|gif)$", name, ignore.case = TRUE)
      ) %>%
      select(name, id) %>%
      distinct(name, .keep_all = TRUE)
  }, error = function(e) {
    message("Error al leer archivos: ", e$message)
    return(NULL)
  })
}

# ----------- PROCESAMIENTO PRINCIPAL -----------
# Crear carpeta de salida
output_dir_id <- create_output_dir()

# Obtener archivos
files <- get_drive_files()
if (is.null(files) || nrow(files) == 0) stop("No hay imágenes para procesar.")

# Descargar imágenes a temporal
temp_dir <- tempdir()
files <- files %>%
  mutate(
    local_path = map2_chr(id, name, ~{
      temp_file <- file.path(temp_dir, .y)
      drive_download(as_id(.x), path = temp_file, overwrite = TRUE)
      temp_file
    })
  )

# Procesar imágenes y obtener dimensiones
processed_data <- future_map(files$local_path, ~{
  tryCatch({
    # Leer imagen
    img <- image_read(.x)
    
    # Obtener metadatos originales
    original_info <- image_info(img)
    original_dim <- paste("Original - Ancho:", original_info$width, "| Alto:", original_info$height)
    
    # Procesar imagen
    img_procesada <- image_resize(img, "150x150")
    
    # Obtener metadatos nuevos
    nueva_info <- image_info(img_procesada)
    nueva_dim <- paste("Nuevo - Ancho:", nueva_info$width, "| Alto:", nueva_info$height)
    
    # Guardar imagen procesada
    output_path <- file.path(temp_dir, paste0("proc_", basename(.x)))
    image_write(img_procesada, output_path)
    
    # Crear registro de metadatos
    tibble(
      archivo = basename(.x),
      original_width = original_info$width,
      original_height = original_info$height,
      nuevo_width = nueva_info$width,
      nuevo_height = nueva_info$height,
      ruta_local = output_path
    )
  }, error = function(e) {
    message("Error procesando ", basename(.x), ": ", e$message)
    return(NULL)
  })
}, .progress = TRUE) %>% 
  bind_rows()

# ----------- SUBIR RESULTADOS A DRIVE -----------
# Subir imágenes procesadas
walk(processed_data$ruta_local, ~{
  drive_upload(.x, path = as_id(output_dir_id), overwrite = TRUE)
})

# Crear y subir registro de metadatos
metadata_file <- file.path(temp_dir, "metadata_procesamiento.csv")
write.csv(processed_data %>% select(-ruta_local), metadata_file, row.names = FALSE)
drive_upload(metadata_file, path = as_id(output_dir_id), name = "metadata_procesamiento.csv")

# ----------- REPORTE FINAL -----------
cat("\nProcesamiento completo!\n")
cat("Resumen de dimensiones:\n")
print(processed_data %>% select(archivo, original_width, original_height, nuevo_width, nuevo_height))