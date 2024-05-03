<?php

namespace Drupal\user_registrations;

Class ParticipantsInfo {

    /**
     * Implementing function for retriving participants data based on programid
     */

  public function GetParticipantsData($prgid = NULL) {

    $database = \Drupal::database();
    $query = $database->select('child_registrations', 'cr')
            ->fields('cr')
            ->condition('prgmid', $prgid, '=')
            ->range(0, 50);
    $results = $query->execute()->fetchAll();
    
    //\Drupal::logger('results')->warning('<pre><code>' . print_r($results, TRUE) . '</code></pre>');
    return $results;
  }

}