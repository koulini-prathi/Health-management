<?php

namespace Drupal\participant_surveys\Controller;

use Drupal\Core\Controller\ControllerBase;

Class SurveysList extends ControllerBase {
 
    public function listsurveys() {
        $surveys = [];
        $form['survey_txt'] = [
            '#type' => 'markup',
            '#markup' => $this->t('Welcome to our Obesity Control Program survey. Your participation is vital in helping us understand the key factors influencing obesity and in developing effective strategies to promote healthier lifestyles. This survey is designed to gather detailed information about your eating habits, physical activity levels, and family background. By providing accurate and honest responses, you will contribute significantly to our research and the development of personalized obesity control interventions.')
        ];
        
        return $form;
    }
}