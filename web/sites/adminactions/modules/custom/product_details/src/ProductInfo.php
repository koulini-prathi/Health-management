<?php

namespace Drupal\product_details;

Class ProductInfo {

    public function GenerateProductData($prdtid = NULL) {
        if(empty($prdtid)) {
            $database = \Drupal::database();
            $query = $database->select('mydata_products', 'p')
                ->fields('p', ['productid','productname','weeks'])
                ->range(0, 50);
        }else {
            $database = \Drupal::database();
            $query = $database->select('mydata_products', 'p')
                ->fields('p', ['productid','productname', 'weeks'])
                ->condition('productid', $prdtid, '=')
                ->range(0, 50);
        }
        $results = $query->execute()->fetchAll();

        return $results;
    }

    public function GenerateProgramDatabyID($prdtid = NULL) {
        //\Drupal::logger('result')->warning('<pre><code>' . print_r($prg_id, TRUE) . '</code></pre>');
        if(!empty($prdtid)){
            $database = \Drupal::database();
            $query = $database->select('program_details', 'p')
                    ->fields('p', ['prgm_title', 'prgm_type', 'prgm_admin', 'start_date', 'prgm_id'])
                    ->condition('prgm_type', $prdtid, '=')
                    ->range(0, 50);
        }
        $results = $query->execute()->fetchAll();
    
        return $results;
    }

    public function GenerateQuestionnaries($prdtid = NULL) {
        if(!empty($prdtid)) {
            $database = \Drupal::database();
            $query = $database->select('mydata_questionnaries','qst')
                     ->fields('qst', ['qstnrid', 'qstnrname'])
                     ->condition('productid', $prdtid, '=')
                     ->range(0, 50);
        }
        $results = $query->execute()->fetchAll();
        return $results;
    }
}