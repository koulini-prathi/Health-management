<?php

namespace Drupal\product_details\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

Class ProductList extends ControllerBase {

   public function listproducts() {
     $products_service = \Drupal::service('product_details.productinfo');
     $products = $products_service->GenerateProductData();
     $rows = [];
     $i=1;
     foreach ($products as $product) {
        $url = Url::fromRoute('product_details.productinfo', array('prdtid' => $product->productid));
        $productname = Link::fromTextAndUrl($product->productname, $url);
        $rows[] = array('data' => array($i, $productname, $product->productid));
        //var_dump($rows);
        $i++;
     }
     $headers = array($this->t('S.no'), $this->t('Product Name'), $this->t('Product Id'));
     return [
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
        '#title' => $this->t("Products List"),
    ];
   }

   public function ProductInformation($prdtid = NULL) {

     $rows = $progrows = $qstnrrows = $qstrows = [];

     $prdt_service = \Drupal::service('product_details.productinfo');
     $prdt_info = $prdt_service->GenerateProductData($prdtid);

     $headers = array($this->t('Product Details:'));
     $rows[] = array('data' => array('Product Name: ', $prdt_info[0]->productname), 'colspan' => 12);
     $rows[] = array('data' => array('Product ID: ', $prdt_info[0]->productid), 'colspan' => 12);
     $rows[] = array('data' => array('Product Scheduled for: ', $prdt_info[0]->weeks.' weeks'), 'colspan' => 12);
     $form['prdt_det_tab'] = [        
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
    ];

     $qstheaders = array($this->t('Questionnarie Details:'));

     $form['qst_tab'] = [        
        '#type' => 'table',
        '#header' => $qstheaders,
        '#rows' => $qstrows,
     ];

     $qstnrs = $prdt_service->GenerateQuestionnaries($prdtid);
     $qstnrheaders = array($this->t('S.NO'), $this->t('Questionnarie ID'), $this->t('Questionnarie Name'));
     $qi=1;
     foreach ($qstnrs as $row=>$qstnr) {
       $qstnrrows[] = array('data' => array($qi, $qstnr->qstnrid, $qstnr->qstnrname));
       $qi++;
     }

     $form['qstnr_tab'] = [        
        '#type' => 'table',
        '#header' => $qstnrheaders,
        '#rows' => $qstnrrows,
     ];

     $prgmheaders = array($this->t('Program Details:'));
     $prgms_info = $prdt_service->GenerateProgramDatabyID($prdtid);
     $prgmrows[] = array('data' => array('Number Of Programs: ', count($prgms_info)), 'colspan' => 12);
     $i=1;
     $progheaders = array($this->t('S.NO'), $this->t('Program Name'), $this->t('Program Type'), $this->t('Program Admin'), $this->t('Program Start Date'));
     
     foreach ($prgms_info as $row => $content) {
        $prgm_start_date = date('d-m-Y', strtotime($content->start_date));     
        $url = Url::fromRoute('program_details.ProgramInfo', array('prgid' => $content->prgm_id));
        $prgm_title = Link::fromTextAndUrl($content->prgm_title, $url);       
        $progrows[] = array('data' => array($i, $prgm_title, ucwords($content->prgm_type), $content->prgm_admin, $prgm_start_date));
        $i++;
     }

     $form['prgm_det_tab'] = [        
        '#type' => 'table',
        '#header' => $prgmheaders,
        '#rows' => $prgmrows,
     ];

     $form['prog_det_tab'] = [        
        '#type' => 'table',
        '#header' => $progheaders,
        '#rows' => $progrows,
     ];
     return $form;
   }
}