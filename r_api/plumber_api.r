library(plumber)
library(magick)

#* @post /process-r
function(req) {
  img <- image_read(req$body)
  info <- image_info(img)
  list(result = paste("Ancho (R):", info$width))
}

# Ejecutar en terminal: Rscript -e "pr <- plumber::plumb('plumber_api.R'); pr$run(port=8000)"