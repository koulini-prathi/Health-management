<?php

namespace Drupal\participant_questionnaries\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

Class FeedbackQuestionnaire extends FormBase {

    public function getFormId() {
        return 'mydata_fb_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $partid = NULL, $progid = NULL, $msrtype = NULL) {
         
        //product id retrieve
        $prodid_service = \Drupal::service('program_details.prgdata');
        $prodid_details = $prodid_service->GenerateProgramData($progid);
        $productid = $prodid_details[0]->prgm_type;
        
        if($productid == "G4FOAU1.0") {
            $qstnrid = 'GFFB010';
            $qstnrtable = 'mydata_gffb010';
        }else{
            $qstnrid = 'TMFB010';
            $qstnrtable = 'mydata_tmfb010';
        }
       
        //msrtypedata
        $qstservice = \Drupal::service('participant_questionnaries.qstdata');
        $msrdata = $qstservice->GetMSRTypes($qstnrid, $partid);
        //$msrdata = array('PRE' => 'PRE', 'POST' => 'POST');
        //var_dump($msrdata);

        //editform
        if(!empty($msrtype)) {
            $fbqstdet = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
            //var_dump($nqqstdet);
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
            '#title' => $this->t('How would you rate your overall satisfaction with the obesity control program?'),
            '#options' => array(
                'Very satisfied' => 'Very satisfied',
                'Satisfied' => 'Satisfied',
                'Neutral' => 'Neutral',
                'Dissatisfied' => 'Dissatisfied',
                'Very dissatisfied' => 'Very dissatisfied',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $fbqstdet[0]->q1,
        ];

        $form['q2'] = [
            '#type' => 'radios',
            '#title' => $this->t('How effective do you find the dietary advice provided by the program?'),
            '#options' => array(
                'Very effective' => 'Very effective',
                'Effective' => 'Effective',
                'Neutral' => 'Neutral',
                'Ineffective' => 'Ineffective',
                'Very Ineffective' => 'Very Ineffective',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $fbqstdet[0]->q2,
        ];

        $form['q3'] = [
            '#type' => 'radios',
            '#title' => $this->t('How useful are the physical activity recommendations in helping you incorporate exercise into your routine?'),
            '#options' => array(
                'Very useful' => 'Very useful',
                'Useful' => 'Useful',
                'Neutral' => 'Neutral',
                'Not very useful' => 'Not very useful',
                'Not useful at all' => 'Not useful at all',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $fbqstdet[0]->q3,
        ];

        $form['q4'] = [
            '#type' => 'radios',
            '#title' => $this->t("How would you rate the quality of support and communication from the program's staff or coaches?"),
            '#options' => array(
                'Excellent' => 'Excellent',
                'Good' => 'Good',
                'Neutral' => 'Neutral',
                'Poor' => 'Poor',
                'Very Poor' => 'Very Poor',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $fbqstdet[0]->q4,
        ];

        $form['q5'] = [
            '#type' => 'radios',
            '#title' => $this->t('How likely are you to recommend this obesity control program to others?'),
            '#options' => array(
                'Very likely' => 'Very likely',
                'Likely' => 'Likely',
                'Neutral' => 'Neutral',
                'Unlikely' => 'Unlikely',
                'Very unlikely' => 'Very unlikely',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $fbqstdet[0]->q5,
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
        
        if($form_state->getValue('opname') == "add"){
            $pa_insert = \Drupal::database()->insert('mydata_gffb010')
                        ->fields(array(
                            'childid' => $form_state->getValue('childid'),
                            'msrtype' => $form_state->getValue('msr_type'),
                            'q1' => $form_state->getValue('q1'),
                            'q2' => $form_state->getValue('q2'),
                            'q3' => $form_state->getValue('q3'),
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
            $query = \Drupal::database()->update('mydata_gffb010');
            $query->fields([
                          'q1' => $form_state->getValue('q1'),
                          'q2' => $form_state->getValue('q2'),
                          'q3' => $form_state->getValue('q3'),
                          'q4' => $form_state->getValue('q4'),
                          'q5' => $form_state->getValue('q5'),
                            ]);
            $query->condition('childid', $form_state->getValue('childid'), "=");
            $query->condition('msrtype', $form_state->getValue('msr_type'), "=");
            $query->execute();
        }

        $form_state->setRedirect('program_details.ParticipantData', array('uid' => $form_state->getValue('childid'), 'prgid' => $form_state->getValue('progid')));
        if($form_state->getValue('opname') == "add") {
            \Drupal::messenger()->addMessage('Feedback Questionnarie Added Successfully');
        }else {
            \Drupal::messenger()->addMessage('Feedback Questionnarie Updated Successfully');
        }
    }

}