<?php
$cols = 'id, component, filepath, filename, userid, mimetype, referencefileid, timecreated';
// docs_to_search: INE, CURP, ACTA, TITULO, PROMEDIO, SINTESIS, IDIOMA, COMPROMISO, MOTIVOS
$search_names = ['tick', 'INE', 'CURP', 'ACTA', 'TITULO', 'PROMEDIO', 'SINTESIS', 'IDIOMA', 'COMPROMISO', 'MOTIVOS', 'smile', 'sad'];
// DB QUERIES
$doc_names = [
  'tick',
  'INE (INE)',
  'CURP (CURP)',
  'Acta de Nacimiento (ACTA)',
  'Titulo Universitario (TITULO)',
  'Documento de Promedio (PROMEDIO)',
  'Sintesis Curricular (SINTESIS)',
  'Acreditación de Idioma (IDIOMA)',
  'Carta Compromiso (COMPROMISO)',
  'Carta de Motivos (MOTIVOS)',
  'Smile',
  'Sad'];
$query_user_files = "SELECT $cols
            FROM {files}
              WHERE userid = 2
                AND (filename LIKE '%tick%'
                OR filename LIKE '%INE%'
                OR filename LIKE '%CURP%'
                OR filename LIKE '%ACTA%'
                OR filename LIKE '%TITULO%'
                OR filename LIKE '%PROMEDIO%'
                OR filename LIKE '%SINTESIS%'
                OR filename LIKE '%IDIOMA%'
                OR filename LIKE '%COMPROMISO%'
                OR filename LIKE '%MOTIVOS%'
                OR filename LIKE '%smile%'
                OR filename LIKE '%sad%')";
// Test Pending
$test_query2 = "SELECT $cols
FROM {files}
WHERE userid = 2
AND filename IN ($search_names)";
$old_data_query = "SELECT filename, referencefileid
                    FROM {files}
                      WHERE userid = 2
                        AND (filename LIKE '%tick%'
                        OR filename LIKE '%INE%'
                        OR filename LIKE '%CURP%'
                        OR filename LIKE '%ACTA%'
                        OR filename LIKE '%TITULO%'
                        OR filename LIKE '%PROMEDIO%'
                        OR filename LIKE '%SINTESIS%'
                        OR filename LIKE '%IDIOMA%'
                        OR filename LIKE '%COMPROMISO%'
                        OR filename LIKE '%MOTIVOS%'
                        OR filename LIKE '%smile%'
                        OR filename LIKE '%sad%')";
function field_verif_query($f_name) {
  return "SELECT *
            FROM {files}
              WHERE userid = 2
                AND filename LIKE '%{$f_name}%'";
}

function fix_records($recs) {
  //echo 'INSIDE fix_records';
  $new_arr = [];
  foreach($recs as $key=>$val) {
    //echo '<pre>'; print_r([$i, $val]); echo '</pre>';
    array_push($new_arr, $val);
  }
  return $new_arr;
}

function get_date($dt) {
  //echo '<pre>'; print_r($dt); echo '</pre>';
  return $dt ? gmdate("Y-m-d", $dt) : '';
}

//function get_prev_cboxes($val) {
//}

function conv_cbox_params($obj, $names) {
  $vars_arr = (object)[];
  //echo '<pre>'; print_r($obj); echo '</pre>';
  /*echo '<pre>'; print_r('obj1'); echo '</pre>';
  echo '<pre>'; print_r($obj->INE_en); echo '</pre>';
  echo '<pre>'; print_r($obj->{'INE_en'}); echo '</pre>';*/
  foreach ($names as $name) {
    foreach (['_en', '_co', '_ce'] as $sfx) {
      //echo '<pre>'; print_r('NAME'); echo '</pre>';
      $key = "$name$sfx";
      //echo '<pre>'; print_r($key); echo '</pre>';
      //$vars_arr[$name] = $vars_arr[$name] ? "$vars_arr[$name]$obj[$key]" : $obj[$key];
      /*echo '<pre>'; print_r($vars_arr); echo '</pre>';
      echo $obj->{$key};
      echo '<pre>'; print_r("{$obj->{$key}}"); echo '</pre>';
      */
      //echo '<pre>'; print_r("{$vars_arr->{$name}}{$obj->{$key}}"); echo '</pre>';
      $vars_arr->{$name} = isset($vars_arr->$name) ? "{$vars_arr->{$name}}{$obj->{$key}}" : "{$obj->{$key}}";  
    }
    //echo '<pre>'; print_r('VARS: '); echo '</pre>';
    //echo '<pre>'; print_r($vars_arr); echo '</pre>';
  }
  return $vars_arr;
  //(int)"$x$y";
}

function check_for_modif($old, $new) {
  echo '<pre>'; print_r('OLD then NEW DATA'); echo '</pre>';
  echo '<pre>'; print_r($old); echo '</pre>';
  echo '<pre>'; print_r($new); echo '</pre>';
  $modifs = [];
  // We iterate over the old_arr because it's the record with the list of actual images, and the new is the checkbox_list
  foreach ($old as $doc) {
    //if ($doc->referencefileid != $new->{$doc->filename} || !isset($doc->referencefileid)) {
    if ((isset($doc->referencefileid) && !($doc->referencefileid === $new->{rem_ext($doc->filename)})) || (!isset($doc->referencefileid) && $new->{rem_ext($doc->filename)} != '000')) {
      array_push($modifs, (object)['id'=>$doc->id, 'referencefileid'=>$new->{rem_ext($doc->filename)}]); 
      //$modifs->{$doc->filename} = $new->{$doc->filename};
    }
  }

  //echo '   Done!  Print $modifs: ';
  //echo '<pre>'; print_r($modifs); echo '</pre>';
  return $modifs;
}

function fix_old_data($dts) {
  //echo '<pre>'; print_r('INSIDE fix_old_data'); echo '</pre>';
  $data = fix_records($dts);
  foreach ($data as $dt) {
    $dt->filename = rem_ext($dt->filename);
  }
  //echo '<pre>'; print_r(fix_records($data)); echo '</pre>';
  return $data;
}

function rem_ext($word) {
  return substr($word, 0, strpos($word,'.')-strlen($word));
}

function f_alert($message) {
  echo "<script>alert('$message');</script>";
}

/*
Got from checkbox
    [checkbox_controller9] => 0
    [doc10_en] => 0
    [doc10_co] => 0
    [doc10_ce] => 0
    [checkbox_controller10] => 0
    [doc11_en] => 1
    [doc11_co] => 1
    [doc11_ce] => 0
    [checkbox_controller11] => 0
    [submitbutton] => Submit it

    [checkbox_controller9] => 1
    [smile_en] => 0
    [smile_co] => 0
    [smile_ce] => 0
    [checkbox_controller10] => 0
    [sad_en] => 1
    [sad_co] => 0
    [sad_ce] => 1
    [checkbox_controller11] => 0
*/

//class get_form_info {
/*function get_docs_info($db, $user) {
  echo 'INSIDE get_docs_info';
  echo '<pre>'; print_r($user->id); echo '</pre>';

  // Query the DB
  return $db->get_records_sql("SELECT $cols
                                  FROM {files}
                                    WHERE userid = 2
                                      AND (filename LIKE '%INE%'
                                       OR filename LIKE '%CURP%'
                                       OR filename LIKE '%ACTA%'
                                       OR filename LIKE '%smile%'
                                       OR filename LIKE '%sad%'
                                       OR filename LIKE '%tick%')
                                      ");
}*/
//}
