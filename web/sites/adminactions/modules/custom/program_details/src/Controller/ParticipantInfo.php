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
        
        $prgservice = \Drupal::service('program_details.prgdata');
        $prgdetails = $prgservice->GenerateProgramData($prgid);
        $prdtid = $prgdetails[0]->prgm_type;
        //var_dump($prdtid);
        $qstnr_service = \Drupal::service('product_details.productinfo');
        $qstnrdets = $qstnr_service->GenerateQuestionnaries($prdtid); 
        
        $qstnrheaders = array($this->t('S.NO'), $this->t('Questionnarie ID'), $this->t('Questionnarie Name'));
        $qi=1;
        foreach ($qstnrdets as $row=>$qstnr) {
          $qstnrrows[] = array('data' => array($qi, $qstnr->qstnrid, $qstnr->qstnrname));
          $qi++;
        }

        $form['qstnr_tab'] = [        
            '#type' => 'table',
            '#header' => $qstnrheaders,
            '#rows' => $qstnrrows,
        ];
      return $form;
    }
}