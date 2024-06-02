<?php

namespace Drupal\product_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

Class CreateProduct extends FormBase{

    public function getFormId(){
        return 'create_product_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state){
        $form['productname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Product name'),
            '#description' => $this->t('Product humanreadable name'),
            '#required' => TRUE,
        ];
        $form['productID'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Product ID'),
            '#description' => $this->t('Product ID'),
            '#required' => TRUE,
        ];
        $form['weeks'] = [
            '#type' => 'select',
            '#title' => $this->t('Number of weeks'),
            '#options' => array (
                '8' => '8',
                '9' => '9',
                '10' => '10'
            ),
            '#required' => TRUE,
        ];
        $form['questionnarie'] = [
            '#type' => 'select',
            '#title' => $this->t('List of questionnarie'),
            '#options' => array(
                'SO010' => 'Social Data Questionnarie',
                'PA010' => 'Physical Activity Questionnarie',
                'NQ010' => 'Nutrition Questionnarie',
                'MS010' => 'Measurement Questionnarie',
                'FB010' => 'Feedback Questionnarie',
                'SQ010' => 'Self Esteem Questionnarie'
            ),
            '#multiple' => TRUE,
            '#required' => TRUE,
        ];
        $form['qstnrindex'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Questionnarie Index'),
            '#required' => TRUE,
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t("submit"),
        ];
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state){
        $questionnaries =  [];
        $questionnaries = $form_state->getValue('questionnarie');
        $qstnr_titles = array(
            'SO010' => 'Social Data Questionnarie',
            'PA010' => 'Physical Activity Questionnarie',
            'NQ010' => 'Nutrition Questionnarie',
            'MS010' => 'Measurement Questionnarie',
            'FB010' => 'Feedback Questionnarie',
            'SQ010' => 'Self Esteem Questionnarie'
        );
        
        //product insert in the table

        $prdct_insert = \Drupal::database()->insert('mydata_products')
                      ->fields(array(
                          'productname' => $form_state->getValue('productname'),
                          'productid' => $form_state->getValue('productID'),
                          'weeks' => $form_state->getValue('weeks'),
                        ))
                      ->execute();
        
        foreach ($questionnaries as $questionnarie) {
            $questionnarieid = $form_state->getValue('qstnrindex').$questionnarie;
            $qstnr_name = $form_state->getValue('productname').' '.$qstnr_titles[$questionnarie];
            $qstnr_insert = \Drupal::database()->insert('mydata_questionnaries')
                      ->fields(array(
                          'productid' => $form_state->getValue('productID'),
                          'qstnrid' => $questionnarieid,
                          'qstnrname' => $qstnr_name,
                        ))
                      ->execute();
        }
        \Drupal::messenger()->addMessage('Product Created Successfully');
        $form_state->setRedirect('product_details.productslist');
    }

}