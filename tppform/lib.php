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

$query_req_doc_checks = "SELECT * FROM {student_reqs}";

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

/*
  Function to modify format of received info from params
*/
/* Received obj:
stdClass Object
(
  [tick_en] => 0
  [tick_co] => 0
  [tick_ce] => 0
  [checkbox_controller0] => 0
  [INE_en] => 0
  [INE_co] => 0
  [INE_ce] => 0
  [checkbox_controller1] => 0
  [CURP_en] => 0
  [CURP_co] => 0
  [CURP_ce] => 0
  ...
)
*/
/* Output obj:
stdClass Object
(
  [tick] => 000
  [INE] => 000
  [CURP] => 000
  ...
)
*/
function conv_cbox_params($obj, $names) {
  $vars_arr = (object)[];
  //echo '<pre>'; print_r('obj1'); echo '</pre>';
  //echo '<pre>'; print_r($obj); echo '</pre>';
  foreach ($names as $name) {
    foreach (['_en', '_co', '_ce'] as $sfx) {
      $key = "$name$sfx";

      $vars_arr->{$name} = isset($vars_arr->$name) ? "{$vars_arr->{$name}}{$obj->{$key}}" : "{$obj->{$key}}";  
    }
  }
  return $vars_arr;
}

/*
  Function to check for existing modifications,
  by comparing the received and formatted info against
  the info from the DB, from table 'doc_verify_checks'
*/
/* Data from DB 'student_reqs' table
Array
(
  [1] => stdClass Object
    (
      [id] => 1
      [doc_verify_checks] => 000
      [user_id] => 2
      [file_id] => 1
    )
  ...
)
*/
/* Data from DB 'files' table
Array
(
  [1] => stdClass Object
    (
      [id] => 1
      [component] => assignfeedback_editpdf
      [filepath] => /
      [filename] => smile.png
      [userid] => 2
      [mimetype] => image/png
      [referencefileid] => 0
      [timecreated] => 1712803897
    )
    ...
)
*/
function check_for_modif($old, $old_checks, $new) {
  /*echo '<pre>'; print_r('OLD then NEW DATA'); echo '</pre>';
  echo '<pre>'; print_r($old); echo '</pre>';
  echo '<pre>'; print_r($new); echo '</pre>';*/
  $modifs = (object)['new_regs'=>[], 'updates'=>[]];
  // We iterate over the old_arr because it's the record with the list of actual images, and the new is the checkbox_list
  foreach ($old as $db_doc) {
    //if ($doc->referencefileid != $new->{$doc->filename} || !isset($doc->referencefileid)) {
    /*
      Check if there's a register in 'student_reqs table'(reqdoc_checks) for the current Doc
      If there isn't and there is a change for it in $new, add it to $modifs->new_regs
      If there is, and the data from the 'student_reqs table'->doc_verify_checks is different than new, add it to $modifs->update
    */
    /*echo '<pre>'; print_r('Check for existance'); echo '</pre>';
    echo '<pre>'; print_r($db_doc->id); echo '</pre>';
    echo '<pre>'; print_r($old_checks[$db_doc->id]); echo '</pre>';
    echo '<pre>'; print_r(isset($old_checks[$db_doc->id]) ? 'true' : 'false'); echo '</pre>';
    echo '<pre>'; print_r([$old_checks[$db_doc->id]->doc_verify_checks, $new->{rem_ext($db_doc->filename)}]); echo '</pre>';*/

    $file_checks_reg = get_elems_by('file_id', $db_doc->id, $old_checks);
    if(empty($file_checks_reg)) {
      /*echo '<pre>'; print_r('----- INSIDE "IF" File checks register is empty ------'); echo '</pre>';
      echo '<pre>'; print_r($file_checks_reg); echo '</pre>';
      echo '<pre>'; print_r($db_doc->filename); echo '</pre>';
      echo '<pre>'; print_r(isset($new->{rem_ext($db_doc->filename)}) ? 'true' : 'false'); echo '</pre>';*/
      if(isset($new->{rem_ext($db_doc->filename)})) {
        array_push($modifs->new_regs, (object)['file_id'=>$db_doc->id, 'doc_verify_checks'=>$new->{rem_ext($db_doc->filename)}]);
      }
    } else {
      if($file_checks_reg->doc_verify_checks != $new->{rem_ext($db_doc->filename)}) {
        array_push($modifs->updates, (object)['checks_id'=>key($file_checks_reg), 'doc_verify_checks'=>$new->{rem_ext($db_doc->filename)}]);
      }
    }

    /*if ((!isset($old_checks[$db_doc->id]) && $new->{rem_ext($db_doc->filename)})
        || (isset($old_checks[$db_doc->id]) && $old_checks[$db_doc->id]->doc_verify_checks != $new->{rem_ext($db_doc->filename)})) {
      echo '<pre>'; print_r('INSIDE "IF"'); echo '</pre>';
      echo '<pre>'; print_r('[rem_ext($db_doc->filename), $new->{rem_ext($db_doc->filename)}]'); echo '</pre>';
      echo '<pre>'; print_r([rem_ext($db_doc->filename), $new->{rem_ext($db_doc->filename)}]); echo '</pre>';
      array_push($modifs, (object)['file_id'=>$db_doc->id, 'doc_verify_checks'=>$new->{rem_ext($db_doc->filename)}]); 
    }*/
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

function get_id_by_name($nm, $dcs) {
  //echo '<pre>'; print_r('INSIDE get_id_by_name'); echo '</pre>';
  //echo '<pre>'; print_r($nm); echo '</pre>';
  $tvar = array_filter($dcs, function($dc) use($nm) {
    //echo '<pre>'; print_r([$nm, $dc->filename]); echo '</pre>';
    return $nm == rem_ext($dc->filename);
  });
  /*echo '<pre>'; print_r('--- T V A R ---'); echo '</pre>';
  echo '<pre>'; print_r($tvar); echo '</pre>';
  echo '<pre>'; print_r(reset($tvar)->id); echo '</pre>';*/
  return empty($tvar) ? false : reset($tvar)->id;
}

function get_elems_by($by, $val, $elms) {
  return array_filter($elms, function($elm) use($by, $val) {
    return $val == $elm->{$by};
  });
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
