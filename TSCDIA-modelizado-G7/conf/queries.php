<?php
                
        //Query executed on: index.php
        $sql5="SELECT   a.nombre,
                        a.emailOficial,
                        a.telefono
                        FROM mame_efocapemm2 a ";

        $sql9 = "SELECT   a.cuatrigrama,
                          a.mapa
                          FROM mame_efocapemm2 a
                          WHERE nombre = ";
                        

                        
                        
        $sql6="SELECT   a.resumen_cursoid,
                        a.tipo_formacion,
                        a.nombre,
                        a.objetivo,
                        a.descripcion_corta,
                        a.cant_duracion,
                        a.imagen1,
                        a.unidad_duracion
                        FROM vm_mame_resumen_cursos2 a
                        WHERE tipo_formacion = 'CURSO_CORTO'";

        $sql7="SELECT COUNT(*) FROM vm_mame_resumen_cursos2 WHERE tipo_formacion = 'CURSO_REGULAR';";

        $sql8="SELECT   a.resumen_cursoid,
                        a.tipo_formacion,
                        a.nombre,
                        a.objetivo,
                        a.descripcion_corta,
                        a.cant_duracion,
                        a.imagen1,
                        a.unidad_duracion
                        FROM vm_mame_resumen_cursos2 a
                        WHERE tipo_formacion = 'CURSO_REGULAR'";


        //--------------------------------

        //Query executed on: curso1.php
        $sql10="SELECT  a.tipo_formacion,
                        a.nombre,
                        a.destinatarios,
                        a.objetivo,
                        a.requicitos,
                        a.cant_duracion,
                        a.imagen2,
                        a.imagen3,
                        a.imagen4,
                        a.unidad_duracion
                        FROM vm_mame_resumen_cursos2 a
                        WHERE resumen_cursoid = ";
        //--------------------------------

        //Query executed on: curso_corto.php

        $sql11="SELECT  a.resumen_cursoid, 
                        a.modalidad, 
                        a.cant_duracion, 
                        a.unidad_duracion ,
                        b.cuatrigrama,
                        b.escuela,
                        b.fecha_desde,
                        b.fecha_hasta,
                        b.fecha_cierre_inscripcion
                        FROM vm_mame_resumen_cursos2 a, vm_mame_resumen_ediciones2 b WHERE a.resumen_cursoid = ";
        $sql11b= " AND b.resumen_cursoid = "; 

        $sql12="SELECT * FROM vm_mame_resumen_ediciones2 WHERE fecha_cierre_inscripcion LIKE ";
        $sql12b=" AND resumen_cursoid = ";

        $sql13="SELECT DISTINCT resumen_cursoid, cuatrigrama, escuela FROM vm_mame_resumen_ediciones2 WHERE resumen_cursoid = ";
        
        $sql14="SELECT * FROM vm_mame_resumen_ediciones2 WHERE cuatrigrama = ";
        $sql14b=" AND resumen_cursoid = ";
?>