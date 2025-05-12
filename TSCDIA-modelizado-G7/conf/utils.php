<?php
  include_once('conf/conf.php');
  include_once('conf/funcion.php');
  include_once('conf/queries.php');
  
  

  //----file:cursos.php----
  //Query and loop to divide query based on 'CURSO_REGULAR' acording css layout
  $result2 = sql_do_query($sql1,'1');
  while($row2 = sql_do_array($result2))
    {
      $value2=($row2['RESUMEN_CURSOID']);
      $fullarray2[]= $value2;    
    }

  //Query and loop to divide query based on 'CURSO_CORTO' acording css layout
  $result3 = sql_do_query($sql3,'1');
  while($row3 = sql_do_array($result3))
    {
      $value3=($row3['RESUMEN_CURSOID']);
      $fullarray3[]= $value3;    
    }
   
    $result3b = sql_do_query($sql3b,'1');
    while($row3b = sql_do_array($result3b))
      {
        $value3b=($row3b['RESUMEN_CURSOID']);
        $fullarray3b[]= $value3b;    
      }
    
    $result3c = sql_do_query($sql3c,'1');
    while($row3c = sql_do_array($result3c))
      {
        $value3c=($row3c['RESUMEN_CURSOID']);
        $fullarray3c[]= $value3c;    
      }

    
    //Used for dinamic dorpdown menu
function optionSelection($query, $menuName, $styleClass, $dstPage){

  //define array fields to bring menu
  $result = sql_do_query($query, '1');
  
  //option menu creation
  $str="";
  $str.='<select name="'.$menuName.'" id="'.$menuName.'" class="'.$styleClass.'" action="'.$dstPage.'">'; 
  $str.= '<option value="null">Filtrar por Escuela</option>';
    while($row22 = sql_do_array($result)){
      
      $str.= '<option value="'.$row22['CUATRIGRAMA'].'">'.$row22['ESCUELA'].'</option>';
    }
  $str.='<select>';

return($str);

}
  

 
?>

