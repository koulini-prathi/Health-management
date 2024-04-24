<?php

namespace Drupal\admin_restresource\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a REST Resource for retrieving the program IDS
 *
 * @RestResource(
 *   id = "get_prgid_rest_resource",
 *   label = @Translation("Get Program Ids resource"),
 *   uri_paths = {
 *     "canonical" = "/prgids-rest"
 *   }
 * )
 */

Class AdminRestResource extends ResourceBase{
  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
   
   public function get() {  
    
    $prg_IDS = [];
    $prgservice = \Drupal::service('program_details.prgdata');
    $prg_data = $prgservice->GenerateProgramData();
    foreach($prg_data as $row => $content){
        $prg_IDS[$content->prgm_type][] = $content->prgm_id;
    }
    //$response = ['message' => 'Hello, this is a rest service'];
    $response = $prg_IDS;
    return new ResourceResponse($response);
  }
}