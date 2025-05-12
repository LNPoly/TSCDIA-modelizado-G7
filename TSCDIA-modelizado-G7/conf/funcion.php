<?php
include_once('conf.php');
//include_once('mailer_5_2_11/PHPMailerAutoload.php');

/*
New function files, used to the entire project
*/





/**
 * 
 * 
Estas son las funciones de coneccion a la Base de datos
La primer funcion me permite identificar los Parametros de Coneccion
Todas estas funciones son las utilizadas para la conexion con la base de datos
/**
Esta Segunda Funcion es que arma los datos de la coneccion y trae los datos
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function sql_do_query($query, $db='0', $user='99999999'){
   // echo $query;
	$conf=sql_cnx_data($db);
    if(isset($conf['host'])){
        $resultado = sql_do_query_mysql($query, $db, $user);
        
    }else{
        $resultado = sql_do_query_odbc($query, $db, $user);
    }
    return $resultado;
}

/**
Esta Funcion Conecta con MYSQL
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function sql_do_query_mysql($query, $db='0', $user='99999999'){
    $conf=sql_cnx_data($db);
    $conexion = mysqli_connect($conf['host'],$conf['user'],$conf['pws']);
        mysqli_select_db($conexion, $conf['db']);
    	if($conexion){
    		$resultado=mysqli_query($conexion, $query);
    			if($resultado){
    			         //Manejo del Log de la aplicacion
                        $log=sql_cnx_data('0');
                        $conlog = mysqli_connect($log['host'],$log['user'],$log['pws']);
                        mysqli_select_db($conexion, $log['db']);
                        $ahora=date("Y-m-d H:m:s");
                        //$query2=addslashes(str_replace(';','',$query));
                        $query2=addslashes($query);
                        $querylog="INSERT INTO  pins_log (id, fecha, query, usuario) VALUES (NULL, '$ahora', '$query2', '$user');";
                        $resulog=mysqli_query($conexion, $query);
    			         //Fin del manejo del Log de la aplicacion
    				return $resultado;
    			}else{
    				die();
    			}
    		mysqli_close($conexion); 
      }
}



/**
Esta Funcion sirve como secuencia de ID
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/

function get_id($tabla){
    $db = '1';
    $conf=sql_cnx_data($db);
    if($conf['host']){
        $id=get_id_mysql($tabla);
    }else{
        $id=get_id_odbc($tabla);
    }
    return $id;
}

/**
Esta Funcion sirve como secuencia de ID
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/

function get_id_mysql($tabla){
    
     $sql="
     SELECT   id_tabla
       FROM   iusm_id
      WHERE   tabla = '$tabla';";
    
    //echo $sql;
	$conf=sql_cnx_data('1');
    
    /** PARA  ODBC **/
//    $conexion=odbc_connect($conf['dns'],$conf['user'],$conf['pws']);
//    $resultado=odbc_exec($conexion,$sql);
//    $ndoc_res = odbc_fetch_array($resultado);
    
    /** PARA MYSQL **/
    $conexion  = mysqli_connect($conf['host'],$conf['user'],$conf['pws']);
                 mysqli_select_db($conexion, $conf['db']);
    $resultado = mysqli_query($conexion, $sql);
    $ndoc_res  = mysql_fetch_alias_array($resultado);
    
    $res = $ndoc_res['ID_TABLA'];
    
    $resmas = $res +1;
    
    $sql4="
    UPDATE   iusm_id
       SET   id_tabla = $resmas
     WHERE   tabla = '$tabla';";
    /** PARA  ODBC **/
    $conexion  = mysqli_connect($conf['host'],$conf['user'],$conf['pws']);
                 mysqli_select_db($conexion, $conf['db']);
    $resultado = mysqli_query($conexion, $sql4);
    
    return $res;
}

/**
Esta Funcion convierte los Datos a un areglo Trabajable no case sencitive
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}


function mysql_fetch_alias_array($result)
{
    if (!($row = mysqli_fetch_array($result)))
    {
        return null;
    }

    $assoc = Array();
    $rowCount = mysqli_num_fields($result);
    
    for ($ndocx = 0; $ndocx < $rowCount; $ndocx++)
    {
        $field = strtoupper(mysqli_field_name($result, $ndocx));
        //$assoc[$field] = $row[$ndocx];
        //$assoc[$field] = htmlentities($row[$ndocx], ENT_QUOTES | ENT_IGNORE, "ISO-8859-1");
        $assoc[$field] = htmlentities($row[$ndocx], ENT_QUOTES | ENT_IGNORE, "ISO-8859-1");
    }
    return $assoc;
}

/**
Esta Funcion sirve como secuencia de ID
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/

function get_id_odbc($tabla){
    
     $sql="
     SELECT   id_tabla
       FROM   iusm_id
      WHERE   tabla = '$tabla';";
    
    //echo $sql;
	$conf=sql_cnx_data('1');
    
    /** PARA  ODBC **/
    $conexion=odbc_connect($conf['dns'],$conf['user'],$conf['pws']);
    $resultado=odbc_exec($conexion,$sql);
    $id_res = odbc_fetch_array($resultado);
    
    /** PARA MYSQL **/
//    $conexion=mysql_connect($conf['host'],$conf['user'],$conf['pws']);
//    $resultado= mysql_query($sql,$conexion);
//    $id_res = mysql_fetch_array($resultado);
    
    //var_dump($id_res);
    $id = ($id_res['ID_TABLA'] + 1);
    
    $sql4="
    UPDATE   iusm_id
       SET   id_tabla = $id
     WHERE   tabla = '$tabla';";
    /** PARA  ODBC **/
    $resultado=odbc_exec($conexion,$sql4);
    /** PARA MYSQL **/
//    $resultado= mysql_query($sql4,$conexion);
    //var_dump($id);
    return $id;
}
/**
Esta Funcion Conecta con ODBC
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function sql_do_query_odbc($query, $db='0', $user='99999999'){
	$conf=sql_cnx_data($db);
    $conexion=odbc_connect($conf['dns'],$conf['user'],$conf['pws']);
    	if($conexion){
    	   //echo $query;
           $resultado=odbc_exec($conexion,$query);
    			if($resultado){
    			    //echo "SI EJECUTA";
    			    //Manejo del Log de la aplicacion
                    $log=sql_cnx_data('0');
                    $conlog = odbc_connect($log['dns'],$log['user'],$log['pws']);
                    $query2=str_replace(';','',$query);
                    $query2=str_replace("'","",$query2);
                    $hoy=date('Y-m-d H:i:s');
                    $querylog="INSERT INTO usu_log 
                      (id, fecha, query, usuario) 
                    VALUES (
                      (select MAX(id)+1 as id from usu_log), to_date('$hoy', 'YYYY-MM-DD HH24:MI:SS'), '$query2', '$user');";
					//echo $querylog;
					//$preplog=odbc_prepare($conlog, $querylog);
                    //$resulog=odbc_execute($preplog);
			        //Fin del manejo del Log de la aplicacion
				    return $resultado;
					//return $resulog;
    			}else{
    				die();
    			}
            odbc_close ($conexion);
    }
}

/**
Esta Funcion Conecta con ODBC
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function sql_do_query_log($query, $db='0',$user='sistema'){
	$conf=sql_cnx_data($db);
    $conexion=odbc_connect($conf['dns'],$conf['user'],$conf['pws']);
    	if($conexion){
           $resultado=odbc_exec($conexion,$query);
    			if($resultado){
    			    //echo "SI EJECUTA";
    			    //Manejo del Log de la aplicacion
                    $log=sql_cnx_data('0');
					$id = get_id('USU_LOG');
                    $conlog = odbc_connect($log['dns'],$log['user'],$log['pws']);
                    $query2=str_replace(';','',$query);
                    $query2=str_replace("'","",$query2);
                    $querylog="INSERT INTO usu_log (id, fecha, query, usuario) VALUES ($id, '$hoy', '$query2', '$user');";
                    //echo $querylog;
                    $preplog=odbc_prepare($conlog, $querylog);
                    $resulog=odbc_execute($preplog);
			         //Fin del manejo del Log de la aplicacion
				    return $resultado;
					//return $resulog;
    			}else{
    				die();
    			}
            odbc_close ($conexion);
    }
}


/**
Esta Funcion convierte los Datos a un areglo Trabajable
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function sql_do_array($recurso, $db='0'){
    $conf=sql_cnx_data($db);
    if(isset($conf['host'])){
        $row=mysql_fetch_alias_array($recurso);
    }else{
        $row=odbc_fetch_array($recurso);
    }
    return $row;
}
/** ---------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function registros($recurso, $db='0'){
    $conf=sql_cnx_data($db);
    if($conf['host']){
        $row=mysql_num_rows($recurso);
    }else{
        $row=odbc_num_rows($recurso);
    }
    return $row;
}
/**
 * Funcion Para el Armado de los Combos desplegables (FK)
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function imp_combo($nombrecombo, $query, $db, $usu){
    
    $str.= "<tr><td>";
    $str.= '<select name="'.$nombrecombo.'" id="'.$nombrecombo.'" style="width: 175px">';
      $campos=array('CURSOID','CUATRIGRAMA');
      $result = sql_do_query($query, $db, $usu);
    $str.= '<option value="null">Seleccione</option>';
	while($row = sql_do_array($result, $db)){
       $str.= '<option value="'.$row[$campos['0']].'">'.$row[$campos['1']].'</option>';
    }
    $str.= '</select>';
    //$str.= '</td></tr>';
    return($str);
}

/**
 * Funcion Para el Armado de los Combos desplegables muy detallados(FK)
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function imp_combo_herr($nombrecombo, $tabla, $db, $usu, $id="id", $nombre="nombre", $actual="0", $limitador='0', $val_limitador='0' ){
    $str.= '<select name="'.$nombrecombo.'" id="'.$nombrecombo.'"'."onChange=enviarEnt(this.form,'3')>";
      $campos=array($id,$nombre);
    if($limitador!='0') $where = "WHERE ".$limitador."='".$val_limitador."'";
      $query = "SELECT * FROM $tabla $where ORDER BY $id ASC ";
      //echo $query; 
      $result = sql_do_query($query, $db, $usu);
      $str.= '<option value="null">Seleccione</option>';
	  while($row = sql_do_array($result, $db)){
            if($actual==$row[$campos['0']]){$sel=" SELECTED ";}else{$sel=" ";}
               $str.= '<option value="'.$row[$campos['0']].'"'.$sel.'>'.$row[$campos['1']].'</option>';
            }
      $str.= '</select>';
return($str);
}
/**
 * Funcion Para el Armado de los Combos desplegables muy detallados(FK)
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function imp_combo_herr_w($nombrecombo, $tabla, $db, $usu, $id="id", $nombre="nombre", $actual="0", $where="0" ){
	$str.= '<select name="'.$nombrecombo.'" id="'.$nombrecombo.'"'."onChange=enviarEnt(this.form,'3')>";
    $str.= '<option value="0">Seleccionar...</option>';
      $campos=array($id,$nombre);
      $query = "SELECT * FROM $tabla $where ORDER BY $id ASC ";
      //echo $query; 
      $result = sql_do_query($query, $db, $usu);
	  while($row = sql_do_array($result, $db)){
        if($actual==$row[$campos['0']]){$sel=" SELECTED ";}else{$sel=" ";}
           
           $str.= '<option value="'.$row[$campos['0']].'"'.$sel.'>'.$row[$campos['1']].'</option>';
	   }
    $str.= '</select>';
    return($str);
}

/**
 * Funcion Para el Armado de los Combos desplegables (FK)
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function imp_combo_w($nombrecombo, $query, $db, $usu, $actual='1'){
    
    //$str.= "<tr><td>";
    $str.= '<select name="'.$nombrecombo.'" id="'.$nombrecombo.'" style="width: 100%">';
      $campos=array('NOMBRE','ID');
      $result = sql_do_query($query, $db, $usu);
    $str.= '<option value="">Seleccione</option>';
	while($row = sql_do_array($result, $db)){
	   if($actual==$row[$campos['1']]){$sel=" SELECTED ";}else{$sel=" ";}
       $str.= '<option value="'.$row[$campos['1']].'" '.$sel.'>'.$row[$campos['0']].'</option>';
    }
    $str.= '</select>';
    //$str.= '</td></tr>';
    return($str);
}


/**
 * Funcion Para Enmascarar Fechas
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function enmascararFecha($fecha_old){
    $dd=substr($fecha_old,8,2);
    $mm=substr($fecha_old,5,2);
    $yy=substr($fecha_old,2,2);
    $fecha_new=$dd."/".$mm."/".$yy;
    if($mm==00)$fecha_new="";
    return $fecha_new;
}

function enmascararFechaMes($fecha_old){
    $mm=substr($fecha_old,5,2);
    $fecha_new=$mm;
    if($mm==00)$fecha_new="";
    return $fecha_new;
}

/**
 * Funcion Para Enmascarar Fechas en el Insert
-------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function enmascararFechaInsert($fecha_old){
    $dd=substr($fecha_old,0,2);
    $mm=substr($fecha_old,3,2);
    $yy=substr($fecha_old,6,4);
    $fecha_new=$yy."-".$mm."-".$dd;
    return $fecha_new;
}
//Funcion para filtrar meses de cursos
function monthSelector($sentencia, $month){
    if($month == '1'){
      $finalQuest = $sentencia."'%-01-%'";
    }
    if($month == '2'){
      $finalQuest = $sentencia."'%-02-%'";
    }
    if($month == '3'){
      $finalQuest = $sentencia."'%-03-%'";
    }
    if($month == '4'){
      $finalQuest = $sentencia."'%-04-%'";
    }
    if($month == '5'){
      $finalQuest = $sentencia."'%-05-%'";
    }
    if($month == '6'){
      $finalQuest = $sentencia."'%-06-%'";
    }
    if($month == '7'){
      $finalQuest = $sentencia."'%-07-%'";
    }
    if($month == '8'){
      $finalQuest = $sentencia."'%-08-%'";
    }
    if($month == '9'){
      $finalQuest = $sentencia."'%-09-%'";
    }
    if($month == '10'){
      $finalQuest = $sentencia."'%-10-%'";
    }
    if($month == '11'){
      $finalQuest = $sentencia."'%-11-%'";
    }
    if($month == '12'){
      $finalQuest = $sentencia."'%-12-%'";
    }
    return $finalQuest;
  } 
 
//Funcion para ejecucion de ediciones de cursos
  function editionSelection($month){
    if($months = '01'){
      $newQuery12 = monthSelector($sql12,1);
    }
    if($months = '02'){
      $newQuery12 = monthSelector($sql12,2);
    }
    if($months = '03'){
      $newQuery12 = monthSelector($sql12,3);
    }
    if($months = '04'){
      $newQuery12 = monthSelector($sql12,4);
    }
    if($months = '05'){
      $newQuery12 = monthSelector($sql12,5);
    }
    if($months = '06'){
      $newQuery12 = monthSelector($sql12,6);
    }
    if($months = '07'){
      $newQuery12 = monthSelector($sql12,7);
    }
    if($months = '08'){
      $newQuery12 = monthSelector($sql12,8);
    }
    if($months = '09'){
      $newQuery12 = monthSelector($sql12,9);
    }
    if($months = '10'){
      $newQuery12 = monthSelector($sql12,10);
    }
    if($months = '11'){
      $newQuery12 = monthSelector($sql12,11);
    }
    if($months = '12'){
      $newQuery12 = monthSelector($sql12,12);
    } 
   return $newQuery12; 
  }
/**
Esta Funcion es la encargada de actualizar dni
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/

function cargar_dni($parametros){
	
	if($parametros['dni']){
    	//echo "entra";
        $sql= "SELECT u.password
          		 FROM usu_usuarios u
         		WHERE u.nombredeusuario = '".$parametros['usuario']."' 
                   OR to_char(u.ndoc) = '".$parametros['usuario']."' ;" ;
        //echo $sql;
        $regusuario = sql_do_array(sql_do_query($sql,'1',$parametros['usuario']));
        $dbusuario['PASSWORD'] = $regusuario['PASSWORD'];
        //echo "<br>".$dbusuario['PASSWORD']." - ".$parametros['password'];
        if($dbusuario['PASSWORD'] == $parametros['password']){
        	//echo "entra";
           	$sql="UPDATE USU_USUARIOS SET ndoc='".$parametros['dni']."' 
				   WHERE nombredeusuario='".$parametros['usuario']."' 
				      OR to_char(ndoc) ='".$parametros['usuario']."';";
           	//echo $sql;
           	$result = sql_do_query($sql,'1',$parametros['usuario']);
           	if ($result){
				$error["error"] = 'Dni_Actualizado_Correctamente';
			}else{
				$error["error"] = 'No se actualizo Dni';
			}
        }else{
        	$error["error"] = 'Error_Credenciales';
        }
	$errores = assocToXML($error);
    return $errores;
	}
}

/**
Esta Funcion es la encargada de actualizar email
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/

function cargar_email($parametros){
	
	if($parametros['email']){
    	//echo "entra";
        $sql= "SELECT u.password
                 FROM usu_usuarios u
                WHERE u.nombredeusuario = '".$parametros['usuario']."' 
                   OR to_char(u.ndoc) = '".$parametros['usuario']."' ;" ;
        //echo $sql;
        $regusuario = sql_do_array(sql_do_query($sql,'1',$parametros['usuario']));
        $dbusuario['PASSWORD'] = $regusuario['PASSWORD'];
        //echo "<br>".$dbusuario['PASSWORD']." - ".$parametros['password'];
        if($dbusuario['PASSWORD'] == $parametros['password']){
           	//echo "entra";
           	$sql="UPDATE USU_USUARIOS SET email='".$parametros['email']."', 
                                          estado = 1 
			       WHERE nombredeusuario='".$parametros['usuario']."' 
				      OR to_char(ndoc) ='".$parametros['usuario']."' ;";
           	//echo $sql;
           	$result = sql_do_query($sql,'1',$parametros['usuario']);
            $num_log = acceso_usuario($ndoc,'Recarga de Email',$parametros['usuario'],0);
           	if ($result){
				$error["error" ]= 'Email_Actualizado_Correctamente';
                /**            * ENVIO DE EMAIL *            **/
        		$para["to"] = strtolower($parametros['email']);
        		$para["from"] = 'gonzalo.farinon@gmail.com';
        		$para["subject"] = 'VERIFICACION DE EMAIL USUARIO INTRANET';
        	    $para["msg"] = "PARA CONTINUAR CON EL ALTA DE USUARIO REVISE SU CORREO ELECTRONICO";
        	    $para["msg_error"] = "HUBO UN ERROR AL ENVIAR CORREO ELECTRONICO";	
        		$para["message"] = '
                    <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="25" class="tabla_con_letra_mediana3"><br />Para continuar con su tramite es necesario verificar su cuenta de correo, haz clic en el enlace que encontrar&aacutes abajo. Le recomendamos utilizar durante el proceso los navegadores <strong>Mozilla Firefox o Internet Explorer</strong>.<br /></td>
                      </tr>
        			  <br/>
        			  <tr>
                        <td height="24"><a href="http://valkiries.net/extranet/ASSB/usu_extranet/index.php?estado=24&log='.$num_log.'" target="_self"><b>Verificar E-Mail</b></a>
        				</td>
                      </tr>
                    </table>';
        		echo EnviarEmail($para);
			}else{
				$error["error"] = 'No se actualizo Email';
			}
        }else{
        	$error["error"] = 'Error_Credenciales';
        }
	$errores = assocToXML($error);
    return $errores;
	}
}

/**
Esta Funcion es la encargada de actualizar password
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function cargar_password($parametros){

	if($parametros['npassword'] AND $parametros['cpassword']){
		
		 $sql= "SELECT u.password
         		  FROM usu_usuarios u
        		 WHERE u.nombredeusuario = '".$parametros['usuario']."' 
                 OR to_char(u.ndoc) = '".$parametros['usuario']."' ;" ;
        //echo $sql;
        $regusuario = sql_do_array(sql_do_query($sql,'1',$parametros['usuario']));
        $dbusuario['PASSWORD'] = $regusuario['PASSWORD'];
       //echo "<br>".$dbusuario['PASSWORD']." - ".$parametros['password'];
        if($dbusuario['PASSWORD'] == $parametros['password']){
          if($parametros['npassword']){
            if($parametros['npassword']==$parametros['cpassword']){
                
                if(strlen($parametros['npassword'])>5){
                        if($parametros['npassword']!=$parametros['usuario']){
                                if(preg_match("/[0-9]/",$parametros['npassword']) AND preg_match("/[A-Z]/",$parametros['npassword']) AND preg_match("/[a-z]/",$parametros['npassword'])){
                                    $sql="UPDATE USU_USUARIOS SET password = '".$parametros['npassword']."', fechavenc = ('$hoy' + 60) WHERE nombredeusuario = '".$parametros['usuario']."' OR to_char(ndoc) = '".$parametros['usuario']."';";
                                    //echo $sql;
                                    sql_do_query($sql,'1',$parametros['usuario']);
                                    $error["error"]= 'Actualizado_Correctamente';
                                }else{
                                    $error["error"]= 'Error_Letras_Numeros';
                                }
                        }else{
                            $error["error"]= 'Error_Igual_Usuario';
                        }
                }else{
                    $error["error"]= 'Error_Largo';
                }
            }else{
                $error = 'Error_Distintos';
            }
          }
 		}else{
        	$error["error"]= 'Error_Credenciales';
        }
	$errores = assocToXML($error);
    return $errores;
    }
}


/**
Esta Funcion s la encargada de actualizar contrase�as, fecha de vencimiento y dni
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function actualizacion_usuario($parametros){
    
    if($parametros['dni'] OR ($parametros['npassword'] AND $parametros['cpassword'])){
        
        /**
         * Esto Hace la Consulta de la base de datos de usuarios
         * Para determinar la existencia de errores
         * Esta es la conexion:
         **/
         
        $sql= " SELECT   u.password
          		  FROM   usu_usuarios u
         		 WHERE   u.nombredeusuario = '".$parametros['usuario']."' 
                    OR to_char(u.ndoc) = '".$parametros['usuario']."'
       ;" ;
        //echo $sql;
        $regusuario = sql_do_array(sql_do_query($sql,'1',$parametros['usuario']));
        $dbusuario['PASSWORD'] = $regusuario['PASSWORD'];
       //echo "<br>".$dbusuario['PASSWORD']." - ".$parametros['password'];
        if($dbusuario['PASSWORD'] == $parametros['password']){
          if($parametros['npassword']){
            if($parametros['npassword']==$parametros['cpassword']){
                
                if(strlen($parametros['npassword'])>5){
                        if($parametros['npassword']!=$parametros['usuario']){
                                if(preg_match("/[0-9]/",$parametros['npassword']) AND preg_match("/[A-Z]/",$parametros['npassword']) AND preg_match("/[a-z]/",$parametros['npassword'])){
                                    $sql="UPDATE USU_USUARIOS SET password = '".$parametros['npassword']."', fechavenc = ('$hoy' + 60) WHERE nombredeusuario = '".$parametros['usuario']."' OR to_char(ndoc) = '".$parametros['usuario']."';";
                                    //echo $sql;
                                    sql_do_query($sql,'1',$parametros['usuario']);
                                    $error = "Actualizado_Correctamente";
                                }else{
                                    $error = "Error_Letras_Numeros";
                                }
                        }else{
                            $error = "Error_Igual_Usuario";
                        }
                }else{
                    $error = "Error_Largo";
                }
            }else{
                $error = "Error_Distintos";
            }
          }

          if($parametros['dni']){
                //echo "entra";
             $sql="UPDATE USU_USUARIOS SET ndoc='".$parametros['dni']."' WHERE nombredeusuario='".$parametros['usuario']."' OR to_char(ndoc) ='".$parametros['usuario']."';";
             //echo $sql;
             sql_do_query($sql,'1',$parametros['usuario']);
             $error = "Actualizado_Correctamente";
          }
            
        }else{
        $error = "Error_Credenciales";
        }
        return $error;
    }
}

/**
Esta Funcion Valida el usuario n la base y con los parametros correcto los actualiza
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function validacion_usuario($parametros){
	//var_dump($parametros);
     $sql= "SELECT us.nombredeusuario,
                   s.codigo AS nro_sistema, 
                   p.nro_priv, 
                   z.cuatrigrama AS destino,
                   u.fechavenc,
                   u.password,
				   u.email,
                   u.sec,
                   u.ndoc,
                   u.estado,
                   s.pagina_inicio AS pagina
              FROM usu_user_sist us, usu_usuarios u, usu_sistemas s, usu_sist_privilegio p, vm_empresas z
             WHERE us.nombredeusuario = u.nombredeusuario
               AND u.destino =z.id
               AND us.priv_sist_id = p.priv_sist_id
               AND us.codigo_sistema = s.codigo
               AND u.estado = 20
               AND us.estado = 19
               AND u.nombredeusuario = ".$parametros['usuario']." ;" ;
           	//echo $sql;
            $recusuario = sql_do_query($sql,'1',$parametros['usuario']);
            $n=0;
            while($regusuario = sql_do_array($recusuario)){
                //var_dump($regusuario);
                if($n==0){
                    $dbusuario['NOMBREDEUSUARIO'] = $regusuario['NOMBREDEUSUARIO'];
                    $dbusuario['DESTINO'] = $regusuario['DESTINO'];
                    $dbusuario['PASSWORD'] = $regusuario['PASSWORD'];
                    $dbusuario['DIRECCION'] = $regusuario['DIRECCION'];
                    $dbusuario['EMAIL'] = $regusuario['EMAIL'];
                    $d=substr($regusuario['FECHAVENC'],8,2);
                    $m=substr($regusuario['FECHAVENC'],5,2);
                    $y=substr($regusuario['FECHAVENC'],0,4);
                    $dbusuario['FECHAVENC'] = mktime(0, 0, 0, $m, $d , $y); ;
                    $dbusuario['DTO'] = $regusuario['DTO'];
                    $dbusuario['SEC'] = $regusuario['SEC'];
                    $dbusuario['NDOC'] = $regusuario['NDOC'];
                    $dbusuario['ESTADO'] = $regusuario['ESTADO'];
                    
                    $dbusuario['NRO_SISTEMA'][$n] = $regusuario['NRO_SISTEMA'];    
                    $dbusuario['NRO_PRIV'][$n] = $regusuario['NRO_PRIV'];
                    $dbusuario['PAGINA'][$n] = $regusuario['PAGINA'];
                }else{
                    $dbusuario['NRO_SISTEMA'][$n] = $regusuario['NRO_SISTEMA'];    
                    $dbusuario['NRO_PRIV'][$n] = $regusuario['NRO_PRIV'];
                    $dbusuario['PAGINA'][$n] = $regusuario['PAGINA'];
                }
                $n++;
            }
           if(!$dbusuario['NOMBREDEUSUARIO']){
                $resultado['error'] = "Error_Usuario";
            }else{
                if($dbusuario['PASSWORD'] != $parametros['password']){
                    $resultado['error'] = "Error_Password";
                }else{
                    if($dbusuario['FECHAVENC'] < mktime(0, 0, 0, date('m'), date('d') , date('y'))){ 
						$resultado['error'] = "Error_Vencido";
					}else{
					    if($dbusuario['NDOC'] == NULL){
							$resultado['error'] = "Error_DNI";
						}else{
							if($dbusuario['NDOC'] == NULL){ 
								$resultado['error'] = "Error_DNI";
							}else{
								if($dbusuario['EMAIL'] == NULL) 
									$resultado['error'] = "Error_Email_1";
								if($dbusuario['ESTADO'] != '20') 
									$resultado['error'] = "Error_Email_2";
							}
						}		
					}
                }
            }
            if(!$resultado['error']){
                $n=0;
                while($dbusuario['NRO_SISTEMA'][$n]){
                    if($dbusuario['NRO_SISTEMA'][$n] == $parametros['sistema']){
                        $nivacc = $dbusuario['NRO_PRIV'][$n];
                        $pagina = $dbusuario['PAGINA'][$n];
                        }
                    
                    $n++;
                }
                if(!$nivacc) $resultado['error'] = "Error_No_Permisos";
            }
            if(!$resultado['error']){
                $resultado = Array
                                    (
                                     'destino' => $dbusuario['DESTINO'],
                                     'nivacc'  => $nivacc,
                                     'pagina'  => $pagina,
                                     'usuario' => $dbusuario['NDOC']
                                    );
            }	
	//$res = assocToXML($resultado);
    return $resultado;
}

/**
Esta Funcion Valida el usuario n la base y con los parametros correcto los actualiza
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function validacion_usuario_liviano($parametros){
	
     $sql= "SELECT   u.nombredeusuario, u.password, u.ndoc
              FROM   usu_usuarios u
             WHERE   u.nombredeusuario = ".$parametros['usuario']." ;" ;
           	//echo $sql;
            $recusuario = sql_do_query($sql,'1',$parametros['usuario']);
            $n=0;
            while($regusuario = sql_do_array($recusuario)){
                if($n==0){
                    $dbusuario['NOMBREDEUSUARIO'] = $regusuario['NOMBREDEUSUARIO'];
                    $dbusuario['NDOC'] = $regusuario['NDOC'];
                    $dbusuario['PASSWORD'] = $regusuario['PASSWORD'];
                }
                $n++;
            }
            if(!$dbusuario['NOMBREDEUSUARIO']){
                   $resultado['error'] = "Error_Usuario";
            }else{
                if($dbusuario['PASSWORD'] != $parametros['password']){
                	$resultado['error'] = "Error_Password";
                }
            }               
                    
            if(!$resultado['error']){
                
                $ip=getenv("REMOTE_ADDR");
                $sql3= "INSERT INTO int_acceso_usuarios
					         VALUES(sq_int_acceso_usuarios.nextval,
							 		NULL,
									'DICO_001',
									'ACCESO LIVIANO CORRECTO',
									'$hoy',
									TO_CHAR('$hoy', 'HH24:MI:SS'),'".$ip."',
									".$parametros['usuario'].", 12);" ;
                //$query3 = odbc_exec($conn,$sql3);
                //$resultado['usuario'] = $dbusuario['NDOC'];
                $resultado = Array
                    (
                     'usuario' => $dbusuario['NDOC'],
                     'nivacc'  => '1'
                    );
            }
            
	$res = $resultado;

    return $res;
}


/**
Esta Funcion quita la basusara del xml
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function limpiarBasura ($vec) {
        $est = htmlentities($vec);
        return $est;
}

/**
Esta Funcion que convierte los datos de un Array o Registro a un XML
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function assocToXML ($theArray, $tabCount=2) { 
    
    $tabCount++; 
    $tabSpace = ""; 
    $extraTabSpace = ""; 
     for ($i = 0; $i<$tabCount; $i++) { 
        $tabSpace .= "\t"; 
     } 
     
     for ($i = 0; $i<$tabCount+1; $i++) { 
        $extraTabSpace .= "\t"; 
     } 
     
    foreach($theArray as $tag => $val) { 
        if (!is_array($val)) { 
            $theXML .= PHP_EOL.$tabSpace.'<'.$tag.'>'.htmlentities(limpiarBasura($val)).'</'.$tag.'>'; 
        } else { 
            $tabCount++; 
            $theXML .= PHP_EOL.$extraTabSpace.'<'.$tag.'>'.assocToXML($val, $tabCount); 
            $theXML .= PHP_EOL.$extraTabSpace.'</'.$tag.'>'; 
        } 
    } 
    
return $theXML; 
}

/**
Esta Funcion que Arma un XML como resultdo de la consulta de usuarios
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function ws_validar_usuario($parametros){
    $seccion = validacion_usuario($parametros);
    return assocToXML($seccion);
}

/**
Esta Funcion quita la basusara del xml
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function herramienta_pie ($usuario, $nivel) {
    
switch ($nivel) {
    case 1:
        $niveltex = "CONSULTA";
        break;
    case 4:
        $niveltex = "DCIA";
        break;
    case 6:
        $niveltex = "ABM";
        break;
    case 9:
        $niveltex = "ADMIN";
        break;
}

$str = '<style type="text/css">
			#contenedor {
			height: 36px;
			width: 350px; ';

if($usuario){
    $str .= 'background-image: url(img/usuario_on.jpg);';
}else{
    $str .= 'background-image: url(img/usuario_off.jpg);';
}

$str .= 'font-family: Verdana, Arial, Helvetica, sans-serif;
		 font-size: 11px; color:rgb(204, 25, 25); }

		 #espacio_sup {
			height: 8px;
			width: 100%;
		 }
		</style>
  <div id="contenedor">
  <div id="espacio_sup"></div>
    <table width="324" height="18" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="46" height="18">&nbsp;</td>
        <td width="128" align="center" bgcolor="#FFFFFF">'.$usuario.'&nbsp;</td>
        <td width="10">&nbsp;</td>
        <td width="160" align="center" bgcolor="#FFFFFF" class="Estilo3">'.$niveltex.'&nbsp;</td>
      </tr>
    </table>
  </div>
';
return $str;
}


/**
Esta Funcion averigua si existe el campo que se quiere crear
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function existe($valor,$campo,$tabla,$usu){
	$sql = "SELECT $campo
			  FROM $tabla
			 WHERE  UPPER($campo) = UPPER('$valor');" ;
	//echo $sql;
	$result=sql_do_query($sql,'1',$usu);
	if($row=sql_do_array($result)){ 
		   $existe = "SI";
	}else{ 
		   $existe = "NO"; 
    }
	return $existe;
}

/**
Esta Funcion guarda los movimientos de del usuario
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/

function acceso_usuario($nombredeusuario,$descripcion,$usu,$codigo){
	$id = get_id('USU_ACCESO_USUARIOS');
	if($codigo == 0)$codigo = "SCOM_001";
	if($nombredeusuario == 0)$nombredeusuario = '';
	$ip= $_SERVER['REMOTE_ADDR'];
    $sql = "INSERT INTO usu_acceso_usuarios
                 VALUES ($id,
				 		 '$nombredeusuario', 
						 '$codigo', 
						 '$descripcion', 
						 '$hoy', 
						 '$hoy', 
						 '$ip', 
						 12, 
						 $usu);";
                         //echo  $sql;
	$result = sql_do_query($sql,'1',$usu);	
	return $id;
}

/**
Esta Funcion enviar email
------------------------------------------------------------------------------------------------------------------------------------------------------------- **/
function EnviarEmail($parametros){
	if($parametros){
				
		$message = '
            <html>
            <head>
            <title>Alta de usuario</title>
            <meta name="author" content="Prefectura Naval Argentina">
            <style type="text/css">
            <!--
            img {border: 0}
            body,td {font-family: Verdana,Arial; font-size: 8pt; color: #000000}
            input {font-family: Verdana,Arial; font-size: 6.5pt}
            a:link, a:visited {text-decoration: none; color: #2030F0}
            a:hover, a:active {text-decoration: underline; color: #FF0000}
            .tabla_con_letra_mediana3 {
            	font-family: Arial, Helvetica, sans-serif;
            	font-size: 12px;
            	text-align: left;
            	background-color: #E7EBFA;
            	line-height: 19px;
            	font-weight: bold;
            	color: #3871A9;
            	border-left-style: solid;
            	border-left-width: 6px;
            	border-left-color: #C1CFDD;
            	text-indent: 2pt;
            }
            .tabla_con_letra_mediana4 {
            	font-family: Arial, Helvetica, sans-serif;
            	font-size: 10px;
            	text-align: center;
            	background-color: #E7EBFA;
            	font-weight: bold;
            	color: #00F;
            }
            -->
            </style>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
            </head> 
            <body bgcolor="#FFFFFF" text="#000000" leftmargin="0">
            <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="60" background="https://prefectura.gob.ar/web/tfa/modulos/budi/bin/img/email/logo-pna.jpg">&nbsp;
				</td>
              </tr>
            </table>
            <br>'.$parametros['message'].'<br>
            <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
			  <tr>
                <td height="25" class="tabla_con_letra_mediana3"><br />Para m&aacutes asesoramiento llame a los tel&eacutefonos: 03487-432904 o por email a gonzalo.farinon@gmail.com<br /></td>
              </tr>
			  <tr>
                <td height="25" class="tabla_con_letra_mediana3"><br /><strong>*No responda este correo electr&oacutenico, &eacutesta es una notificaci&oacuten autom&aacutetica*</strong><br /></td>
              </tr>
              <tr>
                <td height="24" class="tabla_con_letra_mediana4"><p align="center">Aviso de Inscripcion a Prefectura Naval Argentina</p>
				</td>
              </tr>
            </table>
            </body>
            </html>
        ';		

//    $email = new PHPMailer();
//    $email->SMTPDebug = 2;
//    $email->IsSMTP();
//    $email->SMTPAuth = true;
//    $email->SMTPSecure = "ssl";
//    $email->Host = "smtp.gmail.com";
//    $email->Port = 465;
//    $email->Username = 'assbpna@gmail.com';
//    $email->Password = "2017ASSBPNA"; 
//    $email->From = $parametros['from'];
//    $email->FromName = "MESA DE AYUDA ASSB";
//    $email->Subject = $parametros["subject"];
//    $email->MsgHTML($message);
//    $email->AddAddress($parametros['to'], "USUARIO");
//    $email->IsHTML(true);
    
//    if(!$email->Send()) {
//       //return "<b>Error:" . $email->ErrorInfo."</b><br/>";
//       return $return = "LA OPERACION SE EFECTUO CORRECTAMENTE";
//    }else{
//       //return "MENSAJE ENVIADO CORRECTAMENTE";
//       return $return = "LA OPERACION SE EFECTUO CORRECTAMENTE";
//    }
        
    }
    
    return $return = "LA OPERACION SE EFECTUO CORRECTAMENTE";
	return $return;
}

function EnviarEmail_old($parametros){
    
	if($parametros){
				
		$message = '
            <html>
            <head>
            <title>Alta de usuario</title>
            <meta name="author" content="Prefectura Naval Argentina">
            <style type="text/css">
            <!--
            img {border: 0}
            body,td {font-family: Verdana,Arial; font-size: 8pt; color: #000000}
            input {font-family: Verdana,Arial; font-size: 6.5pt}
            a:link, a:visited {text-decoration: none; color: #2030F0}
            a:hover, a:active {text-decoration: underline; color: #FF0000}
            .tabla_con_letra_mediana3 {
            	font-family: Arial, Helvetica, sans-serif;
            	font-size: 12px;
            	text-align: left;
            	background-color: #E7EBFA;
            	line-height: 19px;
            	font-weight: bold;
            	color: #3871A9;
            	border-left-style: solid;
            	border-left-width: 6px;
            	border-left-color: #C1CFDD;
            	text-indent: 2pt;
            }
            .tabla_con_letra_mediana4 {
            	font-family: Arial, Helvetica, sans-serif;
            	font-size: 10px;
            	text-align: center;
            	background-color: #E7EBFA;
            	font-weight: bold;
            	color: #00F;
            }
            -->
            </style>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
            </head> 
            <body bgcolor="#FFFFFF" text="#000000" leftmargin="0">
            <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="60" background="https://prefectura.gob.ar/web/tfa/modulos/budi/bin/img/email/logo-pna.jpg">&nbsp;
				</td>
              </tr>
            </table>
            <br>'.$parametros['message'].'<br>
            <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
			  <tr>
                <td height="25" class="tabla_con_letra_mediana3"><br />Para m&aacutes asesoramiento llame a los tel&eacutefonos: 011-1556295249 o por email a gonzalo.farinon@gmail.com<br /></td>
              </tr>
			  <tr>
                <td height="25" class="tabla_con_letra_mediana3"><br /><strong>*No responda este correo electr&oacutenico, &eacutesta es una notificaci&oacuten autom&aacutetica*</strong><br /></td>
              </tr>
              <tr>
                <td height="24" class="tabla_con_letra_mediana4"><p align="center">Aviso de Inscripcion a Prefectura Naval Argentina</p>
				</td>
              </tr>
            </table>
            </body>
            </html>
        ';		
		$to = $parametros['to'];
        $from = $parametros['from'];
		$msg = $parametros['msg'];
	    if($parametros['msg_error'])$msg_error = $parametros['msg_error'];
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'To: '.$to."\r\n";
        $headers .= 'From: '.$from."\r\n";
        $subject = $parametros["subject"];
        $envio_mail= @mail($to, $subject, $message, $headers);
		
		if($envio_mail) 
			$return = "<div class='texto_letra_mediana_predeter_bold'>".$msg."</div>"; 
		else 
			$return = "<div class='titulo_mediano_center_rojo'>".$msg_error."</div>";
	}else{	
		$return = "<div class='titulo_mediano_center_rojo'>".$msg_error."</div>";
	}
    
	return $return;
}

function url_validar($link)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
	   //aumentar el tiempo puede sobrecargar el uso del servidor
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 3); 
	   //numero de redirecciones que va a seguir
	$data = curl_exec($ch);
	curl_close($ch);
	preg_match_all("/HTTP\/1\.[1|0]\s(\d{3})/",$data,$matches);
 
	$code = end($matches[1]);
 
	if(!$data) 
	{
		return(false);
	} 
	else 
	{
		if($code==200) 
		{
			return(true);
		} 
		elseif($code==404) 
		{
			return(false);
		}
	}
}

?>
