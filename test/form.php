<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class simplehtml_form extends moodleform {
    // Add elements to form.
    public function definition() {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form; // Don't forget the underscore!

        // Add elements to your form.
        $mform->addElement('text', 'email', get_string('email'));
        // Set type of element.
        $mform->setType('email', PARAM_NOTAGS);
        // Default value.
        $mform->setDefault('email', 'Please enter email');

        //To add submit action buttons
        $this->add_action_buttons('submitlabel', 'Submit it');
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}

class file_manage_form extends moodleform {
  // Add elements to form.
  public function definition() {
      // A reference to the form is stored in $this->form.
      // A common convention is to store it in a variable, such as `$mform`.
      $mform = $this->_form; // Don't forget the underscore!

      // TESTING the File Manager API
      $maxbytes = get_max_upload_sizes();
      $mform->addElement(
        'filemanager',
        'my_attachments',
        get_string('attachment', 'moodle'),
        null,
        [
            'subdirs' => 0,
            'maxbytes' => $maxbytes,
            'areamaxbytes' => 10485760,
            'maxfiles' => 50,
            'accepted_types' => ['document'],
            'return_types' => FILE_INTERNAL | FILE_EXTERNAL,
        ]
    );
  }

  // Custom validation should be added here.
  function validation($data, $files) {
      return [];
  }
}