<?php

namespace Drupal\program_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Date;


Class CreateProgram extends FormBase {

    public function getFormId(){
        return 'create_program_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state){
        $form['prgm_name'] = [
            '#type' => 'textfield',
            '#title' => $this ->t('Program Name:'),
            '#description' => $this->t('Program name should follow pattern like wwp-number-go4fun/think-monthyear'),
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
            ],
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t("submit"),
        ];
        //$form['#attached']['library'][] = 'program_details/program-js';
        return $form;
    }
    public function showpreview(array &$form, FormStateInterface $form_state){
        \Drupal::messenger()->addMessage('preview');
    }
    public function submitForm(array &$form, FormStateInterface $form_state){
        \Drupal::messenger()->addMessage('submit');
    }


}