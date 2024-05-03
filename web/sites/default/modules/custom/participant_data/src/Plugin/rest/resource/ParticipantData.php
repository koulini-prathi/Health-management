<?php

 namespace Drupal\participant_data\Plugin\rest\resource;

 use Drupal\rest\Plugin\ResourceBase;
 use Drupal\rest\ResourceResponse;
 use Symfony\Component\HttpFoundation\Request;


 /**
 * Provides a REST Resource for retrieving the participant data
 *
 * @RestResource(
 *   id = "get_partdata_rest_resource",
 *   label = @Translation("Get Participant Data"),
 *   uri_paths = {
 *     "canonical" = "/part-data-rest/{prgid}"
 *   }
 * )
 */

 Class ParticipantData extends ResourceBase {
   
   public function get($prgid, request $request) {
   
    $part_data = [];
    $participantservice = \Drupal::service('user_registrations.participantsinfo');
    $partinfo = $participantservice->GetParticipantsData($prgid);
    $part_data =json_encode($partinfo);
    return new ResourceResponse($part_data);
   }

 }