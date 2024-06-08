<?php

namespace Drupal\participant_questionnaries\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;


Class ViewQuestionnaire extends ControllerBase {

    public function ViewNutrition($partid = NULL, $msrtype = NULL, $progid = NULL) {

        //product id retrieve
      $prodid_service = \Drupal::service('program_details.prgdata');
      $prodid_details = $prodid_service->GenerateProgramData($progid);
      $productid = $prodid_details[0]->prgm_type;

      if($productid == "G4FOAU1.0"){
        $qstnrtable = "mydata_gfnq010";
      }else {
        $qstnrtable = "mydata_tmnq010";
      }
      
      $qstservice = \Drupal::service('participant_questionnaries.qstdata');
      $nqdata = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
      
      $headers = array($this->t('Nutrition Questionnaire Data:'));

      $rows[] = array('data' => array('Child ID: ', $partid), 'colspan' => 12);
      $rows[] = array('data' => array('MSR Type: ', $msrtype), 'colspan' => 12);    
      $rows[] = array('data' => array('How many meals do you typically eat per day?: ', $nqdata[0]->q1), 'colspan' => 12);
      $rows[] = array('data' => array('How often do you eat fast food in a week?: ', $nqdata[0]->q2), 'colspan' => 12);
      $rows[] = array('data' => array('How many servings of fruits and vegetables do you consume daily?: ', $nqdata[0]->q3), 'colspan' => 12);
      $rows[] = array('data' => array('Do you usually drink sugary beverages (soda, sweetened tea, etc.)?: ', $nqdata[0]->q4), 'colspan' => 12);
      $rows[] = array('data' => array('Do you keep track of your calorie intake?: ', $nqdata[0]->q5), 'colspan' => 12);

        
      $form['nq_det_tab'] = [        
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
      ];

      return $form;
    }

    public function ViewPhysicalActivity($partid = NULL, $msrtype = NULL, $progid = NULL) {

        //product id retrieve
      $prodid_service = \Drupal::service('program_details.prgdata');
      $prodid_details = $prodid_service->GenerateProgramData($progid);
      $productid = $prodid_details[0]->prgm_type;

      if($productid == "G4FOAU1.0"){
        $qstnrtable = "mydata_gfpa010";
      }else {
        $qstnrtable = "mydata_tmpa010";
      }
      
      $qstservice = \Drupal::service('participant_questionnaries.qstdata');
      $padata = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
      
      $headers = array($this->t('Physical Activity Questionnaire Data:'));

      $rows[] = array('data' => array('Child ID: ', $partid), 'colspan' => 12);
      $rows[] = array('data' => array('MSR Type: ', $msrtype), 'colspan' => 12);    
      $rows[] = array('data' => array('How many days per week do you engage in moderate to vigorous physical activity (e.g., brisk walking, running, cycling, gym workouts)?: ', $padata[0]->q1), 'colspan' => 12);
      $rows[] = array('data' => array('On average, how many minutes do you spend on physical activities per session?: ', $padata[0]->q2), 'colspan' => 12);
      $rows[] = array('data' => array('Which types of physical activities do you participate in regularly? (Select all that apply): ', $padata[0]->q3), 'colspan' => 12);
      $rows[] = array('data' => array('How do you usually commute to work or school?: ', $padata[0]->q4), 'colspan' => 12);
      $rows[] = array('data' => array('Do you use any tools or technologies to track your physical activity (e.g., fitness trackers, smartphone apps)?: ', $padata[0]->q5), 'colspan' => 12);

        
      $form['pa_det_tab'] = [        
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
      ];

      return $form;
    }

    public function ViewSocialData($partid = NULL, $msrtype = NULL, $progid = NULL) {

        //product id retrieve
      $prodid_service = \Drupal::service('program_details.prgdata');
      $prodid_details = $prodid_service->GenerateProgramData($progid);
      $productid = $prodid_details[0]->prgm_type;

      if($productid == "G4FOAU1.0"){
        $qstnrtable = "mydata_gfso010";
      }else {
        $qstnrtable = "mydata_tmso010";
      }
      
      $qstservice = \Drupal::service('participant_questionnaries.qstdata');
      $sodata = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);

      $q2sodata = explode('~', $sodata[0]->q2);
      $q3sodata = explode('~', $sodata[0]->q3);

      
      $headers = array($this->t('Social Data Questionnaire Data:'));

      $rows[] = array('data' => array('Child ID: ', $partid), 'colspan' => 12);
      $rows[] = array('data' => array('MSR Type: ', $msrtype), 'colspan' => 12);    
      $rows[] = array('data' => array("What is your Parent's current marital status?: ", $sodata[0]->q1), 'colspan' => 12);
      $rows[] = array('data' => array("What is your mother's highest level of education completed?: ", $q2sodata[0]), 'colspan' => 12);
      $rows[] = array('data' => array("What is your father's highest level of education completed?: ", $q2sodata[1]), 'colspan' => 12);
      $rows[] = array('data' => array("What is your mother's employment status?: ", $q3sodata[0]), 'colspan' => 12);
      $rows[] = array('data' => array("What is your father's employment status?: ", $q3sodata[0]), 'colspan' => 12);
      $rows[] = array('data' => array('What is your annual household income?: ', $sodata[0]->q4), 'colspan' => 12);
      $rows[] = array('data' => array('How would you describe your social support network? (e.g., friends, family, community): ', $sodata[0]->q5), 'colspan' => 12);
        
      $form['so_det_tab'] = [        
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
      ];

      return $form;
    }

    public function ViewMeasurement($partid = NULL, $msrtype = NULL, $progid = NULL) {

      //product id retrieve
    $prodid_service = \Drupal::service('program_details.prgdata');
    $prodid_details = $prodid_service->GenerateProgramData($progid);
    $productid = $prodid_details[0]->prgm_type;

    if($productid == "G4FOAU1.0"){
      $qstnrtable = "mydata_gfms010";
    }else {
      $qstnrtable = "mydata_tmms010";
    }
    
    $qstservice = \Drupal::service('participant_questionnaries.qstdata');
    $msdata = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
    
    $headers = array($this->t('Measurement Questionnaire Data:'));

    $rows[] = array('data' => array('Child ID: ', $partid), 'colspan' => 12);
    $rows[] = array('data' => array('MSR Type: ', $msrtype), 'colspan' => 12);    
    $rows[] = array('data' => array('What is your current height?: ', $msdata[0]->q1), 'colspan' => 12);
    $rows[] = array('data' => array('What is your current weight?: ', $msdata[0]->q2), 'colspan' => 12);
    $rows[] = array('data' => array('What is your waist circumference?: ', $msdata[0]->q3), 'colspan' => 12);
    $rows[] = array('data' => array('What is your Body Mass Index (BMI)?: ', $msdata[0]->q4), 'colspan' => 12);
    $rows[] = array('data' => array('Have you experienced any significant weight changes in the past year?: ', $msdata[0]->q5), 'colspan' => 12);

      
    $form['ms_det_tab'] = [        
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
    ];

    return $form;
  }

  public function ViewSelfEsteem($partid = NULL, $msrtype = NULL, $progid = NULL) {

        //product id retrieve
      $prodid_service = \Drupal::service('program_details.prgdata');
      $prodid_details = $prodid_service->GenerateProgramData($progid);
      $productid = $prodid_details[0]->prgm_type;

      if($productid == "G4FOAU1.0"){
        $qstnrtable = "mydata_gfsq010";
      }else {
        $qstnrtable = "mydata_tmsq010";
      }
      
      $qstservice = \Drupal::service('participant_questionnaries.qstdata');
      $sqdata = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
      
      $headers = array($this->t('Self Esteem Questionnaire Data:'));

      $rows[] = array('data' => array('Child ID: ', $partid), 'colspan' => 12);
      $rows[] = array('data' => array('MSR Type: ', $msrtype), 'colspan' => 12);    
      $rows[] = array('data' => array('How often do you feel confident in your abilities and skills?: ', $sqdata[0]->q1), 'colspan' => 12);
      $rows[] = array('data' => array('How do you generally feel about yourself?: ', $sqdata[0]->q2), 'colspan' => 12);
      $rows[] = array('data' => array('When faced with a challenging task, how often do you believe you will succeed?: ', $sqdata[0]->q3), 'colspan' => 12);
      $rows[] = array('data' => array('How comfortable are you with receiving compliments or praise from others?: ', $sqdata[0]->q4), 'colspan' => 12);
      $rows[] = array('data' => array('How often do you engage in self-criticism or negative self-talk?: ', $sqdata[0]->q5), 'colspan' => 12);

        
      $form['sq_det_tab'] = [        
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
      ];

      return $form;
    }
    public function ViewFeedback($partid = NULL, $msrtype = NULL, $progid = NULL) {

      //product id retrieve
    $prodid_service = \Drupal::service('program_details.prgdata');
    $prodid_details = $prodid_service->GenerateProgramData($progid);
    $productid = $prodid_details[0]->prgm_type;

    if($productid == "G4FOAU1.0"){
      $qstnrtable = "mydata_gffb010";
    }else {
      $qstnrtable = "mydata_tmfb010";
    }
    
    $qstservice = \Drupal::service('participant_questionnaries.qstdata');
    $fbdata = $qstservice->GetQuestionnarieDetails($partid, $msrtype, $qstnrtable);
    
    $headers = array($this->t('Feedback Questionnaire Data:'));

    $rows[] = array('data' => array('Child ID: ', $partid), 'colspan' => 12);
    $rows[] = array('data' => array('MSR Type: ', $msrtype), 'colspan' => 12);    
    $rows[] = array('data' => array('How would you rate your overall satisfaction with the obesity control program?: ', $fbdata[0]->q1), 'colspan' => 12);
    $rows[] = array('data' => array('How effective do you find the dietary advice provided by the program?: ', $fbdata[0]->q2), 'colspan' => 12);
    $rows[] = array('data' => array('How useful are the physical activity recommendations in helping you incorporate exercise into your routine?: ', $fbdata[0]->q3), 'colspan' => 12);
    $rows[] = array('data' => array("How would you rate the quality of support and communication from the program's staff or coaches?: ", $fbdata[0]->q4), 'colspan' => 12);
    $rows[] = array('data' => array('How likely are you to recommend this obesity control program to others?: ', $fbdata[0]->q5), 'colspan' => 12);

      
    $form['fb_det_tab'] = [        
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
    ];

    return $form;
  }
}