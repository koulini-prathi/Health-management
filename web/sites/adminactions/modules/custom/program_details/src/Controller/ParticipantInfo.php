<?php

namespace Drupal\program_details\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

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
        
        $qstnrheaders = array($this->t('Questionnarie List:'));
        $qstservice = \Drupal::service('participant_questionnaries.qstdata');

        foreach ($qstnrdets as $row=>$qstnr) { 
          $msrrows =  $qstservice->GetMSRTypes($qstnr->qstnrid, $uid);
          if(!empty(count($msrrows))){        
            $qstnrname = $qstnr->qstnrname;
            $qstnraddurl = $qstservice->QuestionnaireURL('add', $qstnr->qstnrid, $qstnrname, $uid, $prgid);
            $qstnraddtitle = Link::fromTextAndUrl($qstnrname, $qstnraddurl);
            $qstnrrows[] = array('data' => array($qstnraddtitle));
          }
        }

        $form['qstnr_tab'] = [        
            '#type' => 'table',
            '#header' => $qstnrheaders,
            '#rows' => $qstnrrows,
        ];

        $qstdata = $qstservice->GetQuestionnarieData($uid);

        //var_dump($qstdata);
        //$qstdata = [];
        $qstdataheaders = array($this->t('Delete'), $this->t('MSR Type'), $this->t('Questionnarie Name'));
        $qstnrinfo = $qstservice ->GetQuestionnarieInfo($prdtid);
        $del_img = "";
        $editimg = Url::fromUri('base:sites/adminactions/files/img/edit.png', ['absolute' => TRUE])->toString();
        $img = [
          '#theme' => 'image',
          '#uri' => $editimg,
          '#alt' => $this->t('My Custom Image'),
          '#attributes' => ['class' => ['my-custom-image-class']],
        ];
        if(count($qstdata) == 0) {
          $qstdatarows[] = array('data' => array('No data Found'), 'colspan' => 12);
        }else {
          for($i=0;$i<count($qstdata);$i++) { 
            $qstnrtitle = $qstnrinfo[$qstdata[$i]->qstnrid];
            $qstnrviewurl = $qstservice->QuestionnaireURL('view', $qstdata[$i]->qstnrid, $qstnrtitle, $uid, $prgid, $qstdata[$i]->msrtype);
            $qstnrviewtitle = Link::fromTextAndUrl($qstnrtitle, $qstnrviewurl);
            $qstdatarows[] = array('data' => array($img, $qstdata[$i]->msrtype, $qstnrviewtitle));
            
          }
        }

        $form['qstnr_data_tab'] = [
          '#type' => 'table',
          '#header' => $qstdataheaders,
          '#rows' => $qstdatarows,
        ];

      return $form;
    }

}