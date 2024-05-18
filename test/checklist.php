<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class checklist_html_form extends moodleform {
    // Add elements to form.
    public function definition() {
        $mform = $this->_form; // Don't forget the underscore!
        echo 'Inside MY checklist form';
        // Add elements to your form.

        $mform->addElement('advcheckbox', 'test1', 'Test 1', null, ['group' => 1]);
        $mform->addElement('advcheckbox', 'test2', 'Test 2', null, ['group' => 1]);
        // Add a checkbox controller for all checkboxes in `group => 1`:
        $this->add_checkbox_controller(1);

        // These two elements are part of group 3.
        $mform->addElement('advcheckbox', 'test3', 'Test 3', null, ['group' => 3]);
        $mform->addElement('advcheckbox', 'ratingtime', get_string('ratingtime', 'forum'), 'Label displayed after checkbox', array('group' => 3), array(0, 1));
        $mform->addElement('advcheckbox', 'test4', 'Test 4', 'Label', ['group' => 3], array(0,1));
        // Add a checkbox controller for all checkboxes in `group => 3`.
        // This example uses a different wording isntead of Select all/none by passing the second parameter:
        $this->add_checkbox_controller(3, "Un/check todas");

        //To add submit action buttons
        $this->add_action_buttons('submitlabel', 'Submit it');
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}
