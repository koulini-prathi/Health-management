<?php

namespace Drupal\program_details\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

Class ProgramList extends ControllerBase {

    /**
     * Provides the list of programs available in the site
     */
    public function prgList() {
        
        $prg_service = \Drupal::service('program_details.prgdata');
        $results = $prg_service->GenerateProgramData();
        //\Drupal::logger('result')->warning('<pre><code>' . print_r($results, TRUE) . '</code></pre>');
        $rows = [];
        $i=1;
        foreach ($results as $row => $content) {
            $prgm_start_date = date('d-m-Y', strtotime($content->start_date));
            /**
             * adding a link to text
             */
            $url = Url::fromRoute('program_details.ProgramInfo', array('prgid' => $content->prgm_id));
            $prgm_title = Link::fromTextAndUrl($content->prgm_title, $url);
            
            $rows[] = array('data' => array($i, $prgm_title, ucwords($content->prgm_type), $content->prgm_admin, $prgm_start_date));
            $i++;
        }
        $headers = array($this->t('S.NO'), $this->t('Program Name'), $this->t('Program Type'), $this->t('Program Admin'), $this->t('Program Start Date'));
        return [
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#title' => $this->t("Programs List"),
        ];
    }

    /**
     * provides the total info of a single program by passing parameter through url in routing file
     */
    public function prginfo($prgid) {
       
        $prg_service = \Drupal::service('program_details.prgdata');
        $results = $prg_service->GenerateProgramData($prgid);

        $rows = [];
        $prgm_start_date = date('d-m-Y', strtotime($results[0]->start_date));
        $headers = array($this->t('Program Details:'));
        $rows[] = array('data' => array('Program Name: ', $results[0]->prgm_title), 'colspan' => 12);
        $rows[] = array('data' => array('Program Type: ', ucwords($results[0]->prgm_type)), 'colspan' => 6);
        $rows[] = array('data' => array('Program Admin: ', $results[0]->prgm_admin), 'colspan' => 6);
        $rows[] = array('data' => array('Start Date: ', $prgm_start_date), 'colspan' => 12);
        $rows[] = array('data' => array(''), 'colspan' => 12);
        $form['prg_det_tab'] = [        
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
        ];

        $schheaders = array($this->t('Program Schedule:'));
        $schrows[] = array('data' => array(''), 'colspan' => 12);
        $form['prg_tab'] = [        
            '#type' => 'table',
            '#header' => $schheaders,
            '#rows' => $schrows,
        ];
 
                
        $schresults = $prg_service->GenerateProgramSchedule($prgid);
        $prgm_dates = [];
        foreach ($schresults as $row => $content) {
            $schedules = (array) $content;
            foreach($schedules as $schedule => $sch_date){
              if($schedule == 'get_start_date'){
                $schedule = 'get started date';
              }
              $prgm_dates[] = array(
                    'weekno' => ucwords($schedule),
                    'weekdate' => date('d-m-Y', strtotime($sch_date)),
                );
            }
        }
        
        //\Drupal::logger('prgm_dates')->warning('<pre><code>' . print_r($participantdata, TRUE) . '</code></pre>');
        $prgheaders = array(
            'week' => $this->t('Week List'),
            'date' => $this->t('Date'),
        );
        $form['prg_sch_tab'] = [        
            '#type' => 'table',
            '#header' => $prgheaders,
            '#rows' => $prgm_dates,
        ];

        
        $participantservice = \Drupal::service('program_details.particpantdata');
        $participantsdata = $participantservice->GetParticipantInfo($prgid);

        $partheaders = array($this->t('Participants List:'));
        $partrows[] = array('data' => array(''), 'colspan' => 12);
        $form['part_tab'] = [        
            '#type' => 'table',
            '#header' => $partheaders,
            '#rows' => $partrows,
        ];
        
        $participantheaders = array(
            'fullname' => $this->t('Full Name'),
            'email' => $this->t('Email ID'),
            'bmistatus' => $this->t('BMI Status'),
        );
        $participant_rows = [];
        foreach($participantsdata as $id => $participantinfo){
          $parturl = Url::fromRoute('program_details.ParticipantData', array('uid'=>$id,'prgid' => $prgid));
          $participant_fullname = Link::fromTextAndUrl($participantinfo->fname.' '.$participantinfo->lname, $parturl);
          $participant_rows[] = array(
            'fullname' => $participant_fullname,
            'email' => $participantinfo->email,
            'bmistatus' => $participantinfo->bmistatus,
          );
        }
        
        $form['part_data_tab'] = [        
            '#type' => 'table',
            '#header' => $participantheaders,
            '#rows' => $participant_rows,
        ];

        return $form;
    }
  
}