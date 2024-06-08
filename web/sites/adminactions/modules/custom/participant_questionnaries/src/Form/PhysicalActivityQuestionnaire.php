<?php

namespace Drupal\participant_questionnaries\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

Class PhysicalActivityQuestionnaire extends FormBase {

    public function getFormId() {
        return 'mydata_pa_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $partid = NULL, $progid = NULL, $msrtype = NULL) {
         
        //product id retrieve
        $prodid_service = \Drupal::service('program_details.prgdata');
        $prodid_details = $prodid_service->GenerateProgramData($progid);
        $productid = $prodid_details[0]->prgm_type;
        
        if($productid == "G4FOAU1.0") {
            $qstnrid = 'GFPA010';
            $qstnrtable = 'mydata_gfpa010';
        }else{
            $qstnrid = 'TMPA010';
            $qstnrtable = 'mydata_tmpa010';
        }
       
        //msrtypedata
        $qstservice = \Drupal::service('participant_questionnaries.qstdata');
        $msrdata = $qstservice->GetMSRTypes($qstnrid, $partid);
        //$msrdata = array('PRE' => 'PRE', 'POST' => 'POST');
        //var_dump($msrdata);

        //editform
        if(!empty($msrtype)) {
            $paqstdet = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
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
            '#title' => $this->t('How many days per week do you engage in moderate to vigorous physical activity (e.g., brisk walking, running, cycling, gym workouts)?'),
            '#options' => array(
                '0 days' => '0 days',
                '1-2 days' => '1-2 days',
                '3-4 days' => '3-4 days',
                '5 or more days' => '5 or more days'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $paqstdet[0]->q1,
        ];

        $form['q2'] = [
            '#type' => 'radios',
            '#title' => $this->t('On average, how many minutes do you spend on physical activities per session?'),
            '#options' => array(
                'Less than 30 minutes' => 'Less than 30 minutes',
                '30-60 minutes' => '30-60 minutes',
                '61-90 minutes' => '61-90 minutes',
                'More than 90 minutes' => 'More than 90 minutes'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $paqstdet[0]->q2,
        ];

        $form['q3'] = [
            '#type' => 'radios',
            '#title' => $this->t('Which types of physical activities do you participate in regularly? (Select all that apply)'),
            '#options' => array(
                'None'   => 'None',
                'Walking' => 'Walking',
                'Running' => 'Running',
                'Cycling' => 'Cycling',
                'Swimming' => 'Swimming',
                'Gym workouts' => 'Gym workouts',
                'Yoga/Pilates' => 'Yoga/Pilates',
                'Sports (e.g., soccer, basketball)' => 'Sports (e.g., soccer, basketball)',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $paqstdet[0]->q3,
        ];

        $form['q4'] = [
            '#type' => 'radios',
            '#title' => $this->t('How do you usually commute to work or school?'),
            '#options' => array(
                'Walking' => 'Walking',
                'Bicycling' => 'Bicycling',
                'Public transportation' => 'Public transportation',
                'Driving' => 'Driving',
                'Working from home' => 'Working from home'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $paqstdet[0]->q4,
        ];

        $form['q5'] = [
            '#type' => 'radios',
            '#title' => $this->t('Do you use any tools or technologies to track your physical activity (e.g., fitness trackers, smartphone apps)?'),
            '#options' => array(
                'Yes, regularly' => 'Yes, regularly',
                'Yes, occasionally' => 'Yes, occasionally',
                'No, but I am interested in starting' => 'No, but I am interested in starting',
                'No, and I am not interested in tracking' => 'No, and I am not interested in tracking'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $paqstdet[0]->q5,
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
            $pa_insert = \Drupal::database()->insert('mydata_gfpa010')
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
            $query = \Drupal::database()->update('mydata_gfpa010');
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
            \Drupal::messenger()->addMessage('Physical Activity Questionnarie Added Successfully');
        }else {
            \Drupal::messenger()->addMessage('Physical Activity Questionnarie Updated Successfully');
        }
    }

}