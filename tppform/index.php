<?php
//Include simplehtl_form.php
require('../config.php'); //to have access to global variables like the following
global $CFG, $DB, $USER, $OUTPUT, $PAGE;
//require_once($CFG->dirroot.'/tppform/form.php');
require($CFG->dirroot.'/tppform/checklist_form.php');
require_once($CFG->dirroot.'/tppform/lib.php');
//require_once($CFG->dirroot.'/test/_ppform.html');  // this automatically renders the whole html at the top

$PAGE->requires->js('/tppform/js/main.js', true); // the value true is optional, and used to call this command in the header of the html

echo $OUTPUT->header();

// TO Query the DB for the DOCs
$docs = $DB->get_records_sql($query_user_files);
//echo '<pre>'; print_r('Fix records: '); echo '</pre>';
//echo '<pre>'; print_r(fix_records($docs)); echo '</pre>';
//echo 'The updated DOCs:';
//echo '<pre>'; print_r($docs); echo '</pre>';

$q1 = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'mdl_files'";
echo '<pre>'; print_r('---Record Types---'); echo '</pre>';
echo '<pre>'; print_r($DB->get_records_sql($q1)); echo '</pre>';

$my_var1 = "hello";
//echo '<pre>'; print_r('Start Test! '); echo '</pre>';
//conv_cbox_params($test_cbox_params, $search_names);
// CHECKLIST to be processed here
$cform = new checklist_html_form(null, [$search_names, $doc_names, fix_records($docs)]);
// Form processing and displaying is done here.
if ($cform->is_cancelled()) {

  echo 'You have clicked on checklist_html_form CANCEL button.';

} else if ($fromform = $cform->get_data()) {

  echo '<pre>'; print_r('Inside sent Params from C_Form: '); echo '</pre>';
  echo $fromfrom;
  /*echo $fromfrom;
  echo '<pre>'; print_r($fromform); echo '</pre>';
  */
  //$old_data = fix_old_data($DB->get_records_sql($old_data_query));
  //$old_data = $DB->get_records_sql($old_data_query);
  $new_data = conv_cbox_params($fromform, $search_names);
  $modifications = check_for_modif($docs, $new_data);

  if (isset($modifications) && !empty($modifications)) {
    echo '<pre>'; print_r('Modifications: '); echo '</pre>';
    echo '<pre>'; print_r($modifications); echo '</pre>';
    // *** *** Your CODE here to insert into DATABASE
    // Set DB records
    echo '<pre>'; print_r('IN if-isset($modifications)'); echo '</pre>';
    /*$uniqueattendance = new stdclass;                             //WORKS!!!!!!!
    $uniqueattendance->id = 4;
    $uniqueattendance->referencefileid = 101;
    if ($DB->update_record('files', $uniqueattendance)) {
        echo '<pre>'; print_r('Update SUCCESS!'); echo '</pre>';
      } else {
        echo '<pre>'; print_r('Update FAIL!!'); echo '</pre>';
      }*/

    foreach ($modifications as $file) {
      if (empty($file)) {
      } else {
        $aclrecord = new stdclass;
        $aclrecord->id = $file->id;
        $aclrecord->referencefileid = sprintf('%03d', $file->referencefileid);
        // sprintf('%03d', $value); is needed because the DB update col is a Int, so it converts the string
        echo '<pre>'; print_r('Modif to DB: '); echo '</pre>';
        echo '<pre>'; print_r($aclrecord); echo '</pre>';
        echo gettype($file->referencefileid) . "<br>";
        if ($DB->update_record('files', $aclrecord)) {
          echo `console.log('DB data Set, OK!')`;
          echo '<pre>'; print_r('Update SUCCESS!!'); echo '</pre>';
        } else {
          echo `console.log('ERROR with DB data Set')`;
          echo '<pre>'; print_r('Update FAIL!!'); echo '</pre>';
        }
      }
    }
    f_alert('Update Success!');
    echo '<script type="text/javascript">',
     'mark_checks();',
     '</script>'
    ;
    $cform->set_data($toform);
    $cform->display();
    echo '""The updated DOCs:';
    echo '<pre>'; print_r($DB->get_records_sql($query_user_files)); echo '</pre>';
  } else {
    //echo '<pre>'; print_r('In ELSE-isset($modifications)'); echo '</pre>';
    echo 'No changes are needed';
    f_alert('No changes are needed');
    $cform->display();
  }
} else {
  $cform->set_data($toform);
  // Display the form.
  $cform->display();
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
