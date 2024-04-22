<?php

namespace Drupal\program_details\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetUsers extends ControllerBase {

    public function UserList(Request $request) {
        $matches = [];
        $search_string = $request->query->get('q');
        if(strlen($search_string) >=3 ){
            $user_query = \Drupal::entityQuery('user')
            ->accessCheck(FALSE)
            ->condition('status', 1)
            ->condition('roles', 'program_editor')
            ->condition('name', '%' . $search_string . '%', 'LIKE')
            ->range(0,10);
            $uids = $user_query->execute();
            if(!empty($uids)) {
                foreach ($uids as $uid){
                    $account = \Drupal\user\Entity\User::load($uid); 
                    $matches[] = [
                        'value' => $account->getDisplayName(),
                        'title' => $account->getDisplayName(),                  
                    ];
                }
            }else{
                $matches[] = [
                    'value' => '',
                    'title' => $this->t('No User Found'),
                ];
            }
        }
      return new JsonResponse($matches);  
    }

}