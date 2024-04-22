<?php

namespace Drupal\program_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Date;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

Class CreateProgram extends FormBase {

    public function getFormId(){
        return 'create_program_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state){

        /**
         * {@inheritdoc}
         */
        
        $form['prgm_id'] = [
            '#type' => 'textfield',
            '#title' => $this ->t('Program ID:'),
            '#description' => $this->t('Program name should follow pattern like wwp-number'),
            '#required' => TRUE,
        ];
        $form['start_date'] = [
            '#type' => 'date',
            '#title' => $this->t('Start Date:'),
            '#description' => $this->t('Program start date'),
            '#required' => TRUE,
        ];
        $form['get_start_date'] = [
            '#type' => 'date',
            '#title' => $this->t('Get Started Date:'),
            '#required' => TRUE,
            //'#attributes' => array('id' => 'get_started_date',)
        ];
        $form['prgm_type'] = [
            '#type' => 'select',
            '#title' => $this->t("Program Type"),
            '#options' => array('go4fun' => 'Go4Fun', 'think' => 'Think eat and move'),
            '#required' => TRUE,
        ];
        $form['prgm_admin'] = [
            '#type' => 'textfield',
            '#title' => $this->t("Program Admin"),
            '#description' => $this->t('Enter 3 letters of the user'),
            '#autocomplete_route_name' => 'program_details.GetUsers',
            '#required' => TRUE,
        ];
        $form['preview'] = [
            '#type' => 'button',
            '#value' => $this->t("preview"),
            '#ajax'  => [
                'callback' => '::showpreview',
                'event' => 'mousedown',
                'wrapper' => 'select-attendees'
            ],
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t("submit"),
        ];
        $headers = array();           
        $options[] = array();      
        $form['table'] = [
            '#id'    => 'select-attendees',
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $options,
            '#empty' => $this->t('No users found'),
        ];
        $form['#attached']['library'][] = 'program_details/program-js';
        return $form;
    }

    public function showpreview(array &$form, FormStateInterface $form_state){
        $responce = new AjaxResponse();
        if(!empty($form_state->getValue('start_date')) && !empty($form_state->getValue('get_start_date'))){
            $prgm_dates = [];
            $prgm_dates['Get start date'] = $form_state->getValue('get_start_date');
            $prgm_dates['Week1'] = $form_state->getValue('start_date');
            $prgm_dates[] = array(
                'weekno' => 'Get start date',
                'weekdate' => $form_state->getValue('get_start_date'),
            );
            $prgm_dates[] = array(
                'weekno' => 'Week1',
                'weekdate' => $form_state->getValue('start_date'),
            );
            for ($i=2; $i<10; $i++) {
                $days = ($i-1)*7 . ' days';
                //$prgm_dates['Week' . $i] = date('Y-m-d', strtotime($form_state->getValue('start_date'). ' + ' .$days));
                $prgm_dates[] = array(
                    'weekno' => 'Week' . $i,
                    'weekdate' => date('Y-m-d', strtotime($form_state->getValue('start_date'). ' + ' .$days)),
                );
            }
        }
        if(!empty($prgm_dates)){
            $headers = array(
                'first_name' => $this->t('Week List'),
                'last_name' => $this->t('Scheduled Date'),
            );
            $form['table']['#rows'] = $prgm_dates;
            $form['table']['#header'] = $headers;
        }
     return $form['table'];;
    }


    public function submitForm(array &$form, FormStateInterface $form_state){
       
        /**
         * {@inheritdoc}
         */

        $month = date('m', strtotime($form_state->getValue('start_date')));
        $year = date('y', strtotime($form_state->getValue('start_date')));
        $prgm_name = $form_state->getValue('prgm_id').'-'.$form_state->getValue('prgm_type').'-'.$month.$year;
        \Drupal::messenger()->addMessage($prgm_name);
        if(!empty($form_state->getValue('start_date')) && !empty($form_state->getValue('get_start_date'))){
            $prgm_dates = [];
            $prgm_dates['Get start date'] = $form_state->getValue('get_start_date');
            $prgm_dates['Week1'] = $form_state->getValue('start_date');
            $prgm_dates[] = array(
                'weekno' => 'Get start date',
                'weekdate' => $form_state->getValue('get_start_date'),
            );
            $prgm_dates[] = array(
                'weekno' => 'Week1',
                'weekdate' => $form_state->getValue('start_date'),
            );
            for ($i=2; $i<10; $i++) {
                $days = ($i-1)*7 . ' days';
                $prgm_db_dates['Week' . $i] = date('Y-m-d', strtotime($form_state->getValue('start_date'). ' + ' .$days));
            }
        }
        if(!empty($form_state->getValue('prgm_id'))){
            $prgm_insert = \Drupal::database()->insert('program_details')
                      ->fields(array(
                          'prgm_id' => $form_state->getValue('prgm_id'),
                          'prgm_title' => $prgm_name,
                          'prgm_type' => $form_state->getValue('prgm_type'),
                          'prgm_admin' => $form_state->getValue('prgm_admin'),
                          'start_date' => $form_state->getValue('start_date'),
                        ))
                      ->execute();
            $schedule_insert = \Drupal::database()->insert('program_schedule')
                      ->fields(array(
                          'prgm_id' => $form_state->getValue('prgm_id'),
                          'get_start_date' => $form_state->getValue('get_start_date'),
                          'week1' => $form_state->getValue('start_date'),
                          'week2' => $prgm_db_dates['Week2'],
                          'week3' => $prgm_db_dates['Week3'],
                          'week4' => $prgm_db_dates['Week4'],
                          'week5' => $prgm_db_dates['Week5'],
                          'week6' => $prgm_db_dates['Week6'],
                          'week7' => $prgm_db_dates['Week7'],
                          'week8' => $prgm_db_dates['Week8'],
                          'week9' => $prgm_db_dates['Week9'],
                        ))
                      ->execute();            
        }
        \Drupal::messenger()->addMessage('Program Created Successfully');
        
    }

    


}