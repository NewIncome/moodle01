<?php
//Include simplehtl_form.php
require('../config.php'); //to have access to global variables like the following
global $CFG, $DB, $USER, $OUTPUT;
//require_once($CFG->dirroot.'/tppform/form.php');
require($CFG->dirroot.'/tppform/checklist_form.php');
require_once($CFG->dirroot.'/tppform/lib.php');
//require_once($CFG->dirroot.'/test/_ppform.html');  // this automatically renders the whole html at the top

echo $OUTPUT->header();

// TO Query the DB for the DOCs
$docs = $DB->get_records_sql($query_user_files);
//echo '<pre>'; print_r('Fix records: '); echo '</pre>';
//echo '<pre>'; print_r(fix_records($docs)); echo '</pre>';
echo 'The DOCs:';
echo '<pre>'; print_r($docs); echo '</pre>';

//echo '<pre>'; print_r('Start Test! '); echo '</pre>';
//conv_cbox_params($test_cbox_params, $search_names);
// CHECKLIST to be processed here
$cform = new checklist_html_form(null, [$search_names, $doc_names, fix_records($docs)]);
// Form processing and displaying is done here.
if ($cform->is_cancelled()) {

  echo 'You have clicked on checklist_html_form cancel button.';

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

  if (isset($modifications)) {
    // *** *** Your CODE here to insert into DATABASE
    // Set DB records
    foreach ($modifications as $file=>$checks) {

      $rec_id = $DB->get_record_sql(field_verif_query($file));
      if (empty($aclrecord)) {
        /*$aclrecord = new stdClass();
        $aclrecord->mnet_host_id = $user->mnethostid;
        $aclrecord->username = $user->username;
        $aclrecord->accessctrl = $accessctrl;
        $DB->update_record('mnet_sso_access_control', $aclrecord);*/
        echo 'Error: Record info to update was empty.';
      } else {
        /*  $uniqueattendance = new stdclass;
        $uniqueattendance->id = $anabsentee->number;
        $uniqueattendance->field1 = 'some data';
        $uniqueattendance->field2 = 'something else';
        if (update_record('mytable', $uniqueattendance)) {
        /// Success!
        } else {
        /// Fail!
        }*/
        /*$DB->update_record('files', $aclrecord);
        echo 'Record info updated OK';
        */

        echo '<pre>'; print_r('UPDATED $aclrecord: '); echo '</pre>';
        echo '<pre>'; print_r($aclrecord); echo '</pre>';
      }
    }


  } else {
    echo 'No changes are needed';
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
