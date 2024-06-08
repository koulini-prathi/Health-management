<?php

namespace Drupal\participant_questionnaries\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

Class SocialDataQuestionnaire extends FormBase {

    public function getFormId() {
        return 'mydata_so_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $partid = NULL, $progid = NULL, $msrtype = NULL) {
         
        //product id retrieve
        $prodid_service = \Drupal::service('program_details.prgdata');
        $prodid_details = $prodid_service->GenerateProgramData($progid);
        $productid = $prodid_details[0]->prgm_type;
        
        if($productid == "G4FOAU1.0") {
            $qstnrid = 'GFSO010';
            $qstnrtable = 'mydata_gfso010';
        }else{
            $qstnrid = 'TMSO010';
            $qstnrtable = 'mydata_tmso010';
        }
       
        //msrtypedata
        $qstservice = \Drupal::service('participant_questionnaries.qstdata');
        $msrdata = $qstservice->GetMSRTypes($qstnrid, $partid);
        //$msrdata = array('PRE' => 'PRE', 'POST' => 'POST');
        //var_dump($msrdata);

        //editform
        if(!empty($msrtype)) {
            $soqstdet = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
            $q2so = explode('~', $soqstdet[0]->q2);
            $q3so = explode('~', $soqstdet[0]->q3);
        }
        
        if(empty($msrtype)){  
            $form['msr_type'] = [
                '#type' => 'select',
                '#title' => $this->t('Measurement Type'),
                '#options' => $msrdata,
            ];
            $form['opname'] = [
                '#type' => 'hidden',
                '#value' => 'add',
            ];
        }else{
            $form['msrtypeval'] = [
                '#type' => 'markup',
                '#title' => $this->t('Measurement Type'),
                '#markup' => $this->t('Measurement Type: '.$msrtype),
            ]; 
            $form['msr_type'] = [
                '#type' => 'hidden',
                '#value' => $msrtype,
            ]; 
            $form['opname'] = [
                '#type' => 'hidden',
                '#value' => 'edit',
            ];
        }
        
        $form['qstnrid'] = [
            '#type' => 'hidden',
            '#value' => $qstnrid,
        ];

        $form['progid'] = [
            '#type' => 'hidden',
            '#value' => $progid,
        ];

        $form['childid'] = [
            '#type' => 'hidden',
            '#value' => $partid,
        ];

        $form['qstnrtable'] = [
            '#type' => 'hidden',
            '#value' => $qstnrtable,
        ];

        $form['q1'] = [
            '#type' => 'radios',
            '#title' => $this->t("What is your Parent's current marital status?"),
            '#options' => array(
                'Single' => 'Single',
                'Married' => 'Married',
                'Divorced' => 'Divorced',
                'Widowed' => 'Widowed',
                'In a relationship' => 'In a relationship'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $soqstdet[0]->q1,
        ];

        $form['q2a'] = [
            '#type' => 'radios',
            '#title' => $this->t("What is your mother's highest level of education completed?"),
            '#options' => array(
                'Less than high school' => 'Less than high school',
                'High school diploma or equivalent' => 'High school diploma or equivalent',
                'Associate degree' => 'Associate degree',
                'Bachelors degree' => 'Bachelors degree',
                'Graduate or professional degree' => 'Graduate or professional degree'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $q2so[0],
        ];

        $form['q2b'] = [
            '#type' => 'radios',
            '#title' => $this->t("What is your father's highest level of education completed?"),
            '#options' => array(
                'Less than high school' => 'Less than high school',
                'High school diploma or equivalent' => 'High school diploma or equivalent',
                'Associate degree' => 'Associate degree',
                'Bachelors degree' => 'Bachelors degree',
                'Graduate or professional degree' => 'Graduate or professional degree'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $q2so[1],
        ];

        $form['q3a'] = [
            '#type' => 'radios',
            '#title' => $this->t("What is your mother's employment status?"),
            '#options' => array(
                'Employed full-time'   => 'Employed full-time',
                'Employed part-time' => 'Employed part-time',
                'Self-employed' => 'Self-employed',
                'Unemployed' => 'Unemployed',
                'Retired' => 'Retired',
                'Student' => 'Student',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $q3so[0],
        ];

        $form['q3b'] = [
            '#type' => 'radios',
            '#title' => $this->t("What is your father's employment status?"),
            '#options' => array(
                'Employed full-time'   => 'Employed full-time',
                'Employed part-time' => 'Employed part-time',
                'Self-employed' => 'Self-employed',
                'Unemployed' => 'Unemployed',
                'Retired' => 'Retired',
                'Student' => 'Student',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $q3so[1],
        ];

        $form['q4'] = [
            '#type' => 'radios',
            '#title' => $this->t('What is your annual household income?'),
            '#options' => array(
                'Less than $25,000' => 'Less than $25,000',
                '$25,000 - $49,999' => '$25,000 - $49,999',
                '$50,000 - $74,999' => '$50,000 - $74,999',
                '$75,000 - $99,999' => '$75,000 - $99,999',
                '$100,000 - $149,999' => '$100,000 - $149,999',
                '$150,000 or more' => '$150,000 or more'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $soqstdet[0]->q4,
        ];

        $form['q5'] = [
            '#type' => 'radios',
            '#title' => $this->t('How would you describe your social support network? (e.g., friends, family, community)'),
            '#options' => array(
                'Very strong and supportive' => 'Very strong and supportive',
                'Moderately supportive' => 'Moderately supportive',
                'Somewhat supportive' => 'Somewhat supportive',
                'Not very supportive' => 'Not very supportive'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $soqstdet[0]->q5,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

      return $form; 
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      
        $cdate = date('Y-m-d H:i:s');
        $cuser = \Drupal::currentUser()->id();

        $q2 = $form_state->getValue('q2a').'~'.$form_state->getValue('q2b');
        $q3 = $form_state->getValue('q3a').'~'.$form_state->getValue('q3b');
        
        if($form_state->getValue('opname') == "add"){
            $so_insert = \Drupal::database()->insert('mydata_gfso010')
                        ->fields(array(
                            'childid' => $form_state->getValue('childid'),
                            'msrtype' => $form_state->getValue('msr_type'),
                            'q1' => $form_state->getValue('q1'),
                            'q2' => $q2,
                            'q3' => $q3,
                            'q4' => $form_state->getValue('q4'),
                            'q5' => $form_state->getValue('q5'),
                            ))
                        ->execute();

            $ref_insert = \Drupal::database()->insert('mydata_qstnr_ref')
                        ->fields(array(
                            'childid' => $form_state->getValue('childid'),
                            'msrtype' => $form_state->getValue('msr_type'),
                            'prgmid' => $form_state->getValue('progid'),
                            'qstnrid' => $form_state->getValue('qstnrid'),
                            'cdttm' => $cdate,
                            'cuser' => $cuser,
                            ))
                        ->execute();
        }else if($form_state->getValue('opname') == "edit") {
            $query = \Drupal::database()->update('mydata_gfso010');
            $query->fields([
                          'q1' => $form_state->getValue('q1'),
                          'q2' => $q2,
                          'q3' => $q3,
                          'q4' => $form_state->getValue('q4'),
                          'q5' => $form_state->getValue('q5'),
                            ]);
            $query->condition('childid', $form_state->getValue('childid'), "=");
            $query->condition('msrtype', $form_state->getValue('msr_type'), "=");
            $query->execute();
        }

        $form_state->setRedirect('program_details.ParticipantData', array('uid' => $form_state->getValue('childid'), 'prgid' => $form_state->getValue('progid')));
        if($form_state->getValue('opname') == "add") {
            \Drupal::messenger()->addMessage('Social Data Questionnarie Added Successfully');
        }else {
            \Drupal::messenger()->addMessage('Social Data Questionnarie Updated Successfully');
        }
    }

}