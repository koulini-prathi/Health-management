<?php

namespace Drupal\participant_questionnaries;

use Drupal\Core\Url;
use Drupal\Core\Link;

Class QuestionnarieData {

    /**
     * provides the questionnarie data of particpants
     */
    public function GetQuestionnarieData($partid = NULL) {

      $database = \Drupal::database();
      $query = $database->select('mydata_qstnr_ref', 'ref')
                 ->fields('ref', ['msrtype', 'qstnrid'])
                 ->condition('childid', $partid, '=')
                 ->range(0, 50);
      $results = $query->execute()->fetchAll();

      return $results;           
    }

    public function GetQuestionnarieInfo($prodid = NULL) {
        //$qstnr_data = [];
        if($prodid == "G4FOAU1.0") {
            $qstnr_data = array (
              'GFSO010' => 'Go4Fun Social Data Questionnarie',
              'GFNQ010' => 'Go4Fun Nutrition Questionnarie',
              'GFMS010' => 'Go4Fun Measurement Questionnarie',
              'GFPA010' => 'Go4Fun Physical Activity Questionnarie',
              'GFFB010' => 'Go4Fun Feedback Questionnarie',
              'GFSQ010' => 'Go4Fun Self Esteem Questionnarie'
            );
        }else if($prodid == "TEAMAU1.0") {
            $qstnr_data = array (
              'TMSO010' => 'TEAM Social Data Questionnarie',
              'TMNQ010' => 'TEAM Nutrition Questionnarie',
              'TMMS010' => 'TEAM Measurement Questionnarie',
              'TMPA010' => 'TEAM Physical Activity Questionnarie',
              'TMFB010' => 'TEAM Feedback Questionnarie',
              'TMSQ010' => 'TEAM Self Esteem Questionnarie'
            );
        }
        return $qstnr_data;
    }

    public function GetQuestionnarieTable($prodid = NULL) {
      //$qstnr_data = [];
      if($prodid == "G4FOAU1.0") {
          $qstnr_tb = array (
            'GFSO010' => 'mydata_gfso010',
            'GFNQ010' => 'mydata_gfnq010',
            'GFMS010' => 'mydata_gfms010',
            'GFPA010' => 'mydata_gfpa010',
            'GFFB010' => 'mydata_gffb010',
            'GFSQ010' => 'mydata_gfsq010'
          );
      }else if($prodid == "TEAMAU1.0") {
          $qstnr_tb = array (
            'TMSO010' => 'mydata_tmso010',
            'TMNQ010' => 'mydata_tmnq010',
            'TMMS010' => 'mydata_tmms010',
            'TMPA010' => 'mydata_tmpa010',
            'TMFB010' => 'mydata_tmfb010',
            'TMSQ010' => 'mydata_tmsq010'
          );
      }
      return $qstnr_tb;
  }

    public function GetMSRTypes($qstnrid = NULL, $childid = NULL) {
      
      if($qstnrid == "GFFB010" || $qstnrid == "TMFB010"){
        $msr_types = array(
          'POST' => 'POST'
        );
      }else{
        $msr_types = array(
          'PRE' => 'PRE',
          'POST' => 'POST'
        );
      }
      if(!empty($qstnrid)) {
        $database = \Drupal::database();
        $query = $database->select('mydata_qstnr_ref', 'ref')
                 ->fields('ref', ['msrtype'])
                 ->condition('childid', $childid, '=')
                 ->condition('qstnrid', $qstnrid, '=')
                 ->range(0, 50);
        $results = $query->execute()->fetchAll();   

        if(!empty($results)) {
          for($i=0; $i<count($results); $i++) {
            //$msr_types[] =  $results[$i]->msrtype;
            unset($msr_types[$results[$i]->msrtype]);
          }
        }       
      }
      return $msr_types; 
    }

    public function GetQuestionnarieDetails($partid = NULL, $msrtype = NULL, $qstnrtable = NULL) {
      $database = \Drupal::database();
      $query = $database->select($qstnrtable, 'qst')
               ->fields('qst', ['q1', 'q2', 'q3', 'q4', 'q5'])
               ->condition('childid', $partid, '=')
               ->condition('msrtype', $msrtype, '=')
               ->range(0, 50);
      $results = $query->execute()->fetchAll();

      return $results;
    }

    public function QuestionnaireURL($op=NULL, $qstnrid=NULL, $qstnrname=NULL, $uid=NULL, $prgid=NULL, $msrtype=NULL) {
      $qstnrurl = $qstnrname = "";
      if($op == "view") {
        if(preg_match('/NQ010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.viewnutrition', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/PA010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.viewphysical', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/SO010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.viewsocial', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/MS010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.viewmeasurement', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/SQ010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.viewselfesteem', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/FB010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.viewfeedback', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }
      }else if($op == "add") {
        if(preg_match('/NQ010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.nutrition', array('partid' => $uid, 'progid' => $prgid));
        }else if(preg_match('/PA010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.physicalactivity', array('partid' => $uid, 'progid' => $prgid));
        }else if(preg_match('/SO010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.socialdata', array('partid' => $uid, 'progid' => $prgid));
        }else if(preg_match('/MS010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.measurement', array('partid' => $uid, 'progid' => $prgid));
        }else if(preg_match('/SQ010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.selfesteem', array('partid' => $uid, 'progid' => $prgid));
        }else if(preg_match('/FB010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.feedback', array('partid' => $uid, 'progid' => $prgid));
        }
      }else if($op == "edit") {
        if(preg_match('/NQ010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.editnutrition', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/PA010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.editphysicalactivity', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/SO010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.editsocialdata', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/MS010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.editmeasurement', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/SQ010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.editselfesteem', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }else if(preg_match('/FB010/',$qstnrid)) {
          $qstnrurl = Url::fromRoute('participant_questionnaries.editfeedback', array('partid' => $uid, 'progid' => $prgid, 'msrtype' => $msrtype));
        }
      }
      //var_dump($qstnrurl);
      return $qstnrurl;
    }

    public function renderImage($path=NULL) {
      // Assuming the image is located in the site's public files directory.
      $editimg = Url::fromUri('base:sites/adminactions/files/img/edit.png', ['absolute' => TRUE])->toString();  
      return [
        '#theme' => 'image',
        '#uri' => $editimg,
        '#attributes' => ['class' => ['my-custom-image-class']],
      ];
    }
}