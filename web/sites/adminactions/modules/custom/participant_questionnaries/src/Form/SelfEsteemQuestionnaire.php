<?php

namespace Drupal\participant_questionnaries\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

Class SelfEsteemQuestionnaire extends FormBase {

    public function getFormId() {
        return 'mydata_se_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $partid = NULL, $progid = NULL, $msrtype = NULL) {
         
        //product id retrieve
        $prodid_service = \Drupal::service('program_details.prgdata');
        $prodid_details = $prodid_service->GenerateProgramData($progid);
        $productid = $prodid_details[0]->prgm_type;
        
        if($productid == "G4FOAU1.0") {
            $qstnrid = 'GFSQ010';
            $qstnrtable = 'mydata_gfsq010';
        }else{
            $qstnrid = 'TMSQ010';
            $qstnrtable = 'mydata_tmsq010';
        }
       
        //msrtypedata
        $qstservice = \Drupal::service('participant_questionnaries.qstdata');
        $msrdata = $qstservice->GetMSRTypes($qstnrid, $partid);
        //$msrdata = array('PRE' => 'PRE', 'POST' => 'POST');
        //var_dump($msrdata);

        //editform
        if(!empty($msrtype)) {
            $sqqstdet = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
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
            '#title' => $this->t('How often do you feel confident in your abilities and skills?'),
            '#options' => array(
                'Always' => 'Always',
                'Often' => 'Often',
                'Sometimes' => 'Sometimes',
                'Rarely' => 'Rarely',
                'Never' => 'Never',             
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $sqqstdet[0]->q1,
        ];

        $form['q2'] = [
            '#type' => 'radios',
            '#title' => $this->t('How do you generally feel about yourself?'),
            '#options' => array(
                'Very positive' => 'Very positive',
                'Positive' => 'Positive',
                'Neutral' => 'Neutral',
                'Negative' => 'Negative',
                'Very negative' => 'Very negative',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $sqqstdet[0]->q2,
        ];

        $form['q3'] = [
            '#type' => 'radios',
            '#title' => $this->t('When faced with a challenging task, how often do you believe you will succeed?'),
            '#options' => array(
                'Always' => 'Always',
                'Often' => 'Often',
                'Sometimes' => 'Sometimes',
                'Rarely' => 'Rarely',
                'Never' => 'Never',  
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $sqqstdet[0]->q3,
        ];

        $form['q4'] = [
            '#type' => 'radios',
            '#title' => $this->t('How comfortable are you with receiving compliments or praise from others?'),
            '#options' => array(
                'Very comfortable' => 'Very comfortable',
                'Comfortable' => 'Comfortable',
                'Neutral' => 'Neutral',
                'Uncomfortable' => 'Uncomfortable',
                'Very Uncomfortable' => 'Very Uncomfortable',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $sqqstdet[0]->q4,
        ];

        $form['q5'] = [
            '#type' => 'radios',
            '#title' => $this->t('How often do you engage in self-criticism or negative self-talk?'),
            '#options' => array(
                'Always' => 'Always',
                'Often' => 'Often',
                'Sometimes' => 'Sometimes',
                'Rarely' => 'Rarely',
                'Never' => 'Never',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $sqqstdet[0]->q5,
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
            $pa_insert = \Drupal::database()->insert('mydata_gfsq010')
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
            $query = \Drupal::database()->update('mydata_gfsq010');
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
            \Drupal::messenger()->addMessage('Self Esteem Questionnarie Added Successfully');
        }else {
            \Drupal::messenger()->addMessage('Self Esteem Questionnarie Updated Successfully');
        }
    }

}