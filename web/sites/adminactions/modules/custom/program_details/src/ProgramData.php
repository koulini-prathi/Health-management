<?php

namespace Drupal\program_details;

Class ProgramData {

  public function GenerateProgramData($prg_id = NULL) {
    //\Drupal::logger('result')->warning('<pre><code>' . print_r($prg_id, TRUE) . '</code></pre>');
    if(!empty($prg_id)){
        $database = \Drupal::database();
        $query = $database->select('program_details', 'p')
                ->fields('p', ['prgm_title', 'prgm_type', 'prgm_admin', 'start_date'])
                ->condition('prgm_id', $prg_id, '=')
                ->range(0, 50);
    }else{
        $database = \Drupal::database();
        $query = $database->select('program_details', 'p')
                ->fields('p', ['prgm_id','prgm_title', 'prgm_type', 'prgm_admin', 'start_date'])
                ->range(0, 50);
    }
    $results = $query->execute()->fetchAll();

    return $results;
  }

  public function GenerateProgramSchedule($prg_id = NULL) {
    //\Drupal::logger('result')->warning('<pre><code>' . print_r($prg_id, TRUE) . '</code></pre>');
    $results = [];
    if(!empty($prg_id)){
        $database = \Drupal::database();
        $query = $database->select('program_schedule', 'ps')
                ->fields('ps', ['get_start_date', 'week1', 'week2', 'week3', 'week4', 'week5', 'week6', 'week7', 'week8', 'week9'])
                ->condition('prgm_id', $prg_id, '=')
                ->range(0, 50);
        $results = $query->execute()->fetchAll();
    }
    return $results;
  }

}