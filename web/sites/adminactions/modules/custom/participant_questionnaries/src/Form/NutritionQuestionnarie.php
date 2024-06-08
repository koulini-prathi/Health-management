<?php

namespace Drupal\participant_questionnaries\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

Class NutritionQuestionnarie extends FormBase {

    public function getFormId() {
        return 'mydata_nq_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $partid = NULL, $progid = NULL, $opname = NULL, $msrtype = NULL) {
         
        //product id retrieve
        $prodid_service = \Drupal::service('program_details.prgdata');
        $prodid_details = $prodid_service->GenerateProgramData($progid);
        $productid = $prodid_details[0]->prgm_type;

        if($productid == "G4FOAU1.0") {
            $qstnrid = 'GFNQ010';
            $qstnrtable = 'mydata_gfnq010';
        }else{
            $qstnrid = 'TMNQ010';
            $qstnrtable = 'mydata_tmnq010';
        }
       
        //msrtypedata
        $qstservice = \Drupal::service('participant_questionnaries.qstdata');
        $msrdata = $qstservice->GetMSRTypes($qstnrid, $partid);

        //editform
        if(!empty($msrtype)) {
            $nqqstdet = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
            //var_dump($nqqstdet);
        }

        //var_dump($msrdata);
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
            '#title' => $this->t('How many meals do you typically eat per day?'),
            '#options' => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4 or more' => '4 or more'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $nqqstdet[0]->q1,
        ];

        $form['q2'] = [
            '#type' => 'radios',
            '#title' => $this->t('How often do you eat fast food in a week?'),
            '#options' => array(
                'Never' => 'Never',
                '1-2 times' => '1-2 times',
                '3-4 times' => '3-4 times',
                '5 or more times' => '5 or more times'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $nqqstdet[0]->q2,
        ];

        $form['q3'] = [
            '#type' => 'radios',
            '#title' => $this->t('How many servings of fruits and vegetables do you consume daily?'),
            '#options' => array(
                'None' => 'None',
                '1-2 servings' => '1-2 servings',
                '3-4 servings' => '3-4 servings',
                '5 or more servings' => '5 or more servings'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $nqqstdet[0]->q3,
        ];

        $form['q4'] = [
            '#type' => 'radios',
            '#title' => $this->t('Do you usually drink sugary beverages (soda, sweetened tea, etc.)?'),
            '#options' => array(
                'Never' => 'Never',
                'Rarely' => 'Rarely',
                'Sometimes' => 'Sometimes',
                'Often' => 'Often'
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $nqqstdet[0]->q4,
        ];

        $form['q5'] = [
            '#type' => 'radios',
            '#title' => $this->t('Do you keep track of your calorie intake?'),
            '#options' => array(
                'Yes' => 'Yes',
                'No' => 'No',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $nqqstdet[0]->q5,
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
            $nq_insert = \Drupal::database()->insert('mydata_gfnq010')
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
            $query = \Drupal::database()->update('mydata_gfnq010');
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
            \Drupal::messenger()->addMessage('Nutrition Questionnarie Added Successfully');
        }else {
            \Drupal::messenger()->addMessage('Nutrition Questionnarie Updated Successfully');
        }
    }

}