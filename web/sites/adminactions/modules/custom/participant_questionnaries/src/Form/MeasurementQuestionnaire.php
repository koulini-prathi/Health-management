<?php

namespace Drupal\participant_questionnaries\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

Class MeasurementQuestionnaire extends FormBase {

    public function getFormId() {
        return 'mydata_ms_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $partid = NULL, $progid = NULL, $msrtype = NULL) {
         
        //product id retrieve
        $prodid_service = \Drupal::service('program_details.prgdata');
        $prodid_details = $prodid_service->GenerateProgramData($progid);
        $productid = $prodid_details[0]->prgm_type;
        
        if($productid == "G4FOAU1.0") {
            $qstnrid = 'GFMS010';
            $qstnrtable = 'mydata_gfms010';
        }else{
            $qstnrid = 'TMMS010';
            $qstnrtable = 'mydata_tmms010';
        }
       
        //msrtypedata
        $qstservice = \Drupal::service('participant_questionnaries.qstdata');
        $msrdata = $qstservice->GetMSRTypes($qstnrid, $partid);
        //$msrdata = array('PRE' => 'PRE', 'POST' => 'POST');
        //var_dump($msrdata);

        //editform
        if(!empty($msrtype)) {
            $msqstdet = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
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
            '#title' => $this->t('What is your current height?'),
            '#options' => array(
                "Less than 150 cm / 4'11" => "Less than 150 cm / 4'11",
                "150 - 159 cm / 4'11 - 5'2" => "150 - 159 cm / 4'11 - 5'2",
                "160 - 169 cm / 5'3 - 5'6" => "160 - 169 cm / 5'3 - 5'6",
                "170 - 179 cm / 5'7 - 5'10" => "170 - 179 cm / 5'7 - 5'10",
                "180 - 189 cm / 5'11 - 6'2" => "180 - 189 cm / 5'11 - 6'2",
                "190 cm / 6'3 or more" => "190 cm / 6'3 or more",
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $msqstdet[0]->q1,
        ];

        $form['q2'] = [
            '#type' => 'radios',
            '#title' => $this->t('What is your current weight?'),
            '#options' => array(
                'Less than 50 kg / 110 lbs' => 'Less than 50 kg / 110 lbs',
                '50 - 59 kg / 110 - 130 lbs' => '50 - 59 kg / 110 - 130 lbs',
                '60 - 69 kg / 131 - 150 lbs' => '60 - 69 kg / 131 - 150 lbs',
                '70 - 79 kg / 151 - 175 lbs' => '70 - 79 kg / 151 - 175 lbs',
                '80 - 89 kg / 176 - 195 lbs' => '80 - 89 kg / 176 - 195 lbs',
                '90 - 99 kg / 196 - 220 lbs' => '90 - 99 kg / 196 - 220 lbs',
                '100 kg / 221 lbs or more' => '100 kg / 221 lbs or more',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $msqstdet[0]->q2,
        ];

        $form['q3'] = [
            '#type' => 'radios',
            '#title' => $this->t('What is your waist circumference?'),
            '#options' => array(
                'Less than 70 cm / 27 inches' => 'Less than 70 cm / 27 inches',
                '70 - 79 cm / 27 - 31 inches' => '70 - 79 cm / 27 - 31 inches',
                '80 - 89 cm / 32 - 35 inches' => '80 - 89 cm / 32 - 35 inches',
                '90 - 99 cm / 36 - 39 inches' => '90 - 99 cm / 36 - 39 inches',
                '100 - 109 cm / 40 - 43 inches' => '100 - 109 cm / 40 - 43 inches',
                '110 cm / 44 inches or more' => '110 cm / 44 inches or more',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $msqstdet[0]->q3,
        ];

        $form['q4'] = [
            '#type' => 'radios',
            '#title' => $this->t('What is your Body Mass Index (BMI)?'),
            '#options' => array(
                'Underweight (BMI less than 18.5)' => 'Underweight (BMI less than 18.5)',
                'Normal weight (BMI 18.5 - 24.9)' => 'Normal weight (BMI 18.5 - 24.9)',
                'Overweight (BMI 25 - 29.9)' => 'Overweight (BMI 25 - 29.9)',
                'Obesity Class I (BMI 30 - 34.9)' => 'Obesity Class I (BMI 30 - 34.9)',
                'Obesity Class II (BMI 35 - 39.9)' => 'Obesity Class II (BMI 35 - 39.9)',
                'Obesity Class III (BMI 40 or more)' => 'Obesity Class III (BMI 40 or more)',
                'Not sure' => 'Not sure',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $msqstdet[0]->q4,
        ];

        $form['q5'] = [
            '#type' => 'radios',
            '#title' => $this->t('Have you experienced any significant weight changes in the past year?'),
            '#options' => array(
                'Yes, gained less than 5 kg / 11 lbs' => 'Yes, gained less than 5 kg / 11 lbs',
                'Yes, gained 5-10 kg / 11-22 lbs' => 'Yes, gained 5-10 kg / 11-22 lbs',
                'Yes, gained more than 10 kg / 22 lbs' => 'Yes, gained more than 10 kg / 22 lbs',
                'Yes, lost less than 5 kg / 11 lbs' => 'Yes, lost less than 5 kg / 11 lbs',
                'Yes, lost 5-10 kg / 11-22 lbs' => 'Yes, lost 5-10 kg / 11-22 lbs',
                'Yes, lost more than 10 kg / 22 lbs' => 'Yes, lost more than 10 kg / 22 lbs',
                'No significant changes' => 'No significant changes',
            ),
            '#required' => TRUE,
            '#default_value' => (empty($msrtype)) ? "" : $msqstdet[0]->q5,
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
            $pa_insert = \Drupal::database()->insert('mydata_gfms010')
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
            $query = \Drupal::database()->update('mydata_gfms010');
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
            \Drupal::messenger()->addMessage('Measurement Questionnarie Added Successfully');
        }else {
            \Drupal::messenger()->addMessage('Measurement Questionnarie Updated Successfully');
        }
    }

}