<?php
/* Configuracion de la Base de Datos */
/* Actualmente estan incorporadas en una funcion las dejo por las dudas pero no se usan*/
ini_set( 'extension', 'php_openssl.dll' );
ini_set( 'display_errors', 'off' );
header('Content-Type: text/html; charset=UTF-8');


#PRODUCCION
function sql_cnx_data($db='0'){
   switch ($db)
   {
    case '1':
        $data=array();
        $data['dns']="dedu";
        $data['user']="dedu";
        //$data['pws']="educ2020"; //desarrollo
        $data['pws']="k3r6ss"; //pro duccion
        //$data['db']="dedu_certificados";
    break;
    default:
        $data=array();
        $data['dns']="dedu";
        //$data['host']="192.168.9.29";
        $data['user']="dedu";
        //$data['pws']="educ2020"; //desarrollo
        $data['pws']="k3r6ss"; //produccion
        //$data['db']="dedu_certificados";
    
    }
return $data;
}

?>
