<?php

namespace Drupal\program_details\Controller;

use Drupal\Core\Controller\ControllerBase;

class ParticipantInfo extends ControllerBase {

    public function DisplayParticipantData($uid=Null, $prgid=Null) {
        $partid_data = [];
        $form = [];
        $participantservice = \Drupal::service('program_details.particpantdata');
        $participantsdata = $participantservice->GetParticipantInfo($prgid);
        $partid_data = $participantsdata[$uid]; 
        $dob = date('d-m-Y', strtotime($partid_data->dob));
        $headers = array($this->t('Participant Details:'));
        $rows[] = array('data' => array('Participant Full Name: ', $partid_data->fname .' '. $partid_data->lname), 'colspan' => 12);
        $rows[] = array('data' => array('Gender: ', ucwords($partid_data->gender)), 'colspan' => 6);
        $rows[] = array('data' => array('Email Id: ', $partid_data->email), 'colspan' => 6);
        $rows[] = array('data' => array('Date of Birth: ', $dob), 'colspan' => 12);
        $rows[] = array('data' => array('Height: ', $partid_data->height), 'colspan' => 12);
        $rows[] = array('data' => array('Weight: ', $partid_data->weight), 'colspan' => 12);
        $rows[] = array('data' => array('BMI Status: ', $partid_data->bmistatus), 'colspan' => 12);
        $rows[] = array('data' => array(''), 'colspan' => 12);
        $form['prg_det_tab'] = [        
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
        ];


      return $form;
    }
}