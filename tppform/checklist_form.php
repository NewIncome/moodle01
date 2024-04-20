<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/tppform/lib.php');

class checklist_html_form extends moodleform {
    // Add elements to form.
    public function definition() {
        $mform = $this->_form; // Don't forget the underscore!
        //echo 'Inside MY checklist form';
        // Add elements to your form.
        $s_av = $this->_customdata[0];
        $names = $this->_customdata[1];
        $docs = $this->_customdata[2];
        //echo '<pre>'; print_r($this->_customdata); echo '</pre>';
        //echo '<pre>'; print_r($docs); echo '</pre>';
        foreach ( $names as $i=>$req_name ){
          $d = array_filter($docs, function($obj) use($s_av, $i) {
            return preg_match("/{$s_av[$i]}/i", $obj->filename);
          });
          $d = fix_records($d)[0];
          //echo '<pre>'; print_r($d); echo '</pre>';
          //$d = act_docs($i);

          /*echo '<pre>'; print_r(get_object_vars($d)); echo '</pre>';
          echo '<pre>'; print_r((array)$d); echo '</pre>';
          echo '<pre>'; print_r([$d->filename, $d->timecreated]); echo '</pre>';
          echo '<pre>'; print_r('NEEEEXT'); echo '</pre>';*/
          $mform->addElement('advcheckbox', "$s_av[$i]_en", $req_name, 'Entregado', ['group' => $i], array(0,1));
          $mform->addElement('advcheckbox', "$s_av[$i]_co", $d->filename ? $d->filename : '-', 'Cotejado', ['group' => $i], array(0,1));
          $mform->addElement('advcheckbox', "$s_av[$i]_ce", get_date($d->timecreated), 'Certificado', ['group' => $i], array(0,1));
          $this->add_checkbox_controller($i);
        }
        //echo '<pre>'; print_r($docs); echo '</pre>';
        /*
        $fecha_creacion = date('d/m/Y',$fecha_creacion);
        $mform->addElement('static', 'desc' , 'Fecha de Creación');
        $mform->setDefault('desc', $fecha_creacion);*/

        //To add submit action buttons
        $this->add_action_buttons('submitlabel', 'Submit it');
    }

    function my_fix_records($recs) {
      $new_arr = [];
      foreach($recs as $key=>$val) { array_push($new_arr, $val); }
      return $new_arr;
    }

    function my_get_date($dt) {
      //echo '<pre>'; print_r($dt); echo '</pre>';
      return $dt ? gmdate("Y-m-d \TH:i:s\Z", $dt) : '';
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}
