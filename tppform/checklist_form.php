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
        $doc_checks = $this->_customdata[3];
        //echo '<pre>'; print_r($this->_customdata); echo '</pre>';
        //echo '<pre>'; print_r($docs); echo '</pre>';
        foreach ( $names as $i=>$req_name ){
          $d = array_filter($docs, function($obj) use($s_av, $i) {
            return preg_match("/{$s_av[$i]}/i", $obj->filename);
          });
          $d = fix_records($d)[0];

          //echo '<pre>'; print_r('--- Checks Filter Loop ---'); echo '</pre>';
          /*
            Loop to check if the 
          */
          $cks = array_filter($doc_checks, function($dc) use($docs, $i, $s_av) {
            //echo '<pre>'; print_r('----- $ d o c _ c h e c k -----'); echo '</pre>';
            /*echo '<pre>'; print_r($dc); echo '</pre>';
            echo '<pre>'; print_r(['i', $i, $docs[$i]->filename, $docs[$i]->id, $dc->file_id, $docs[$i]->id == $dc->file_id ? 'true' : 'false']); echo '</pre>';
            echo '<pre>'; print_r([$docs[$dc->file_id]->filename, $req_name]); echo '</pre>';*/
            //return $docs[$dc->file_id]->filename == $s_av[$i];
            //with name get file-id, with id filter over student_docs regs
            //echo '<pre>'; print_r([$i, $s_av[$i]]); echo '</pre>';
            $g_id = get_id_by_name($s_av[$i], $docs);
            /*echo '<pre>'; print_r('Got ID ?'); echo '</pre>';
            echo '<pre>'; print_r($g_id); echo '</pre>';*/
            if(empty($g_id)) { return false; }
            return $dc->file_id == $g_id;
          });

          /*if(!empty($cks)) {
            echo '<pre>'; print_r('--- - - - - - - ---'); echo '</pre>';
            echo '<pre>'; print_r('--- C H E C K S ---'); echo '</pre>';
            echo '<pre>'; print_r('--- - - - - - - ---'); echo '</pre>';
            echo '<pre>'; print_r($cks); echo '</pre>';
            echo '<pre>'; print_r(fix_records($cks)); echo '</pre>';
            echo '<pre>'; print_r(fix_records($cks)[0]->doc_verify_checks); echo '</pre>';
            //$cks = $cks[0]->doc_verify_checks;
          }*/

          if(!empty($cks)) {
            $cks = fix_records($cks);
            $cks = fix_records($cks)[0]->doc_verify_checks;
          } else {
            $cks = '';
          }

          //$d = act_docs($i);

          /*echo '<pre>'; print_r(get_object_vars($d)); echo '</pre>';
          echo '<pre>'; print_r((array)$d); echo '</pre>';
          echo '<pre>'; print_r([$d->filename, $d->timecreated]); echo '</pre>';
          echo '<pre>'; print_r('NEEEEXT'); echo '</pre>';*/
          $mform->addElement('advcheckbox', "$s_av[$i]_en", $req_name, 'Entregado',
                              ['group' => $i, 'data-mydat' => $cks, 'data-mynam' => 'ent'], array(0,1));
          $mform->addElement('advcheckbox', "$s_av[$i]_co", $d->filename ? $d->filename : '-', 'Cotejado',
                              ['group' => $i, 'data-mydat' => $cks, 'data-mynam' => 'cot'], array(0,1));
          $mform->addElement('advcheckbox', "$s_av[$i]_ce", get_date($d->timecreated), 'Certificado',
                              ['group' => $i, 'data-mydat' => $cks, 'data-mynam' => 'cer'], array(0,1));
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

    /*function my_fix_records($recs) {
      $new_arr = [];
      foreach($recs as $key=>$val) { array_push($new_arr, $val); }
      return $new_arr;
    }

    function my_get_date($dt) {
      //echo '<pre>'; print_r($dt); echo '</pre>';
      return $dt ? gmdate("Y-m-d \TH:i:s\Z", $dt) : '';
    }

    function put_dat($dt) {
      
    }*/

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}
