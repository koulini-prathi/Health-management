<?php

namespace Drupal\admin_restresource\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a REST Resource for retrieving the program schedule dates
 *
 * @RestResource(
 *   id = "get_prgsch_rest_resource",
 *   label = @Translation("Get Schedule Dates"),
 *   uri_paths = {
 *     "canonical" = "/prgsch-rest/{prgid}"
 *   }
 * )
 */

Class PrgscheduleRestResource extends ResourceBase{
  /**
   * Responds to entity get requests.
   * @return \Drupal\rest\ResourceResponse
   */
   
   public function get($prgid) {    
    $schdates = [];
    if(!$prgid){
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Node not found.');
        $response = ['message' => 'Hello, this is a rest service'];
    }else{
        $prgsch = \Drupal::service('program_details.prgdata');
        $schdates = $prgsch->GenerateProgramSchedule($prgid);   
        $response = json_encode($schdates);
    }
    return new ResourceResponse($response);
  }
}