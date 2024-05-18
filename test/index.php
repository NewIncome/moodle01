<?php
//Include simplehtl_form.php
require('../config.php'); //to have access to global variables like the following
global $CFG, $DB, $USER, $OUTPUT;
require_once($CFG->dirroot.'/test/form.php');
require_once($CFG->dirroot.'/test/checklist.php');
//require_once($CFG->dirroot.'/test/_ppform.html');  // this automatically renders the whole html at the top

echo $OUTPUT->header();
// Instantiate the myform form from within the plugin.
$mform = new simplehtml_form();

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    // If there is a cancel element on the form, and it was pressed,
    // then the `is_cancelled()` function will return true.
    // You can handle the cancel operation here.
    echo 'You have clicked on cancel button.';
} else if ($fromform = $mform->get_data()) {
    // When the form is submitted, and the data is successfully validated,
    // the `get_data()` function will return the data posted in the form.

    print_r($fromform);

    // *** *** Your CODE here to insert into DATABASE

} else {
    // This branch is executed if the form is submitted but the data doesn't
    // validate and the form should be redisplayed or on the first display of the form.

    // Set anydefault data (if any).
    $mform->set_data($toform);

    // Display the form.
    $mform->display();
}


$fform = new file_manage_form();
// Form processing and displaying is done here.
if ($fform->is_cancelled()) {
  echo 'You have clicked on cancel button.';
} else if ($fromform = $fform->get_data()) {

  print_r($fromform);
  // *** *** Your CODE here to insert into DATABASE

} else {
  $fform->set_data($toform);
  // Display the form.
  $fform->display();
}


// CHECKLIST to be processed here
$cform = new checklist_html_form();
// Form processing and displaying is done here.
if ($cform->is_cancelled()) {
  echo 'You have clicked on checklist_html_form cancel button.';
} else if ($fromform = $cform->get_data()) {

  print_r($fromform);
  // *** *** Your CODE here to insert into DATABASE

} else {
  $cform->set_data($toform);
  // Display the form.
  $cform->display();
}

echo 'Testin PPs form';
/*class pp_render {
  
  function Main() {
    $this->PpPage();
  }

  function PpPage() {
    $vars['TITLE'] = "My Page";
    // render page
    $output = Template("_ppform.html", $vars);
    echo $output;
  }
}*/
/*$data = [
  'name' => 'Lorem ipsum',
  'description' => format_text($description, FORMAT_HTML),
];*/
echo $OUTPUT->render_from_template('core/ppform', []);


class list_vs_base {
  function Main() {
    $this->clist();
  }

  function clist() {
    $vars['exists?'] = "get";
    // render page
    $output = Template(chl(), $vars);
    echo $output;
  }
}
//echo $CHL['html'];

//**************************************************** */
// WRITE TO THE DB
/*$ins = (object)array('name' => $name, 'email' => $email);
$ins->id = insert_record('your_table_name', $ins);
*/

// READ FROM THE DB
//$cols = ['id', 'component', 'filepath', 'filename', 'userid', 'mimetype', 'referencefileid'];
$cols = 'id, component, filepath, filename, userid, mimetype, referencefileid';
//echo '<pre>'; print_r($DB->get_record('user', ['id' => '2'])); echo '</pre>';
//$files = $DB->get_record('files', ['userid' => '2']);   // Only gets one record
//$files = $DB->get_records_sql('SELECT * FROM {files} WHERE userid = 2;');
$files = $DB->get_records_sql("SELECT $cols FROM {files} WHERE userid = 2;");
/*$files = $DB->get_records_sql(
  $table = 'mdl_files',
  $params = ['userid = 2'],
  $fields = '*',
  $strictness = IGNORE_MISSING
);*/
echo '<pre>'; print_r($files); echo '</pre>';



//get: id, component, filepath, filename, userid, mimetype, referencefileid
/*
Record from 'files' table
stdClass Object
(
    [id] => 1
    [contenthash] => 5f8e911d0da441e36f47c5c46f4393269211ca56
    [pathnamehash] => 508e674d49c30d4fde325fe6c7f6fd3d56b247e1
    [contextid] => 1
    [component] => assignfeedback_editpdf
    [filearea] => stamps
    [itemid] => 0
    [filepath] => /
    [filename] => smile.png
    [userid] => 2
    [filesize] => 1085
    [mimetype] => image/png
    [status] => 0
    [source] => 
    [author] => 
    [license] => 
    [timecreated] => 1712803897
    [timemodified] => 1712803897
    [sortorder] => 0
    [referencefileid] => 
)
*/
