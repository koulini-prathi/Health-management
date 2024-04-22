<?php

namespace Drupal\user_registrations;

Class BMIServices {

    /**
     * Generates Height Options 
     */

  public function heightlist() {
	$height_array = [];
	for($i=54;$i<=250;$i++){
		$height_array[$i] = $i . " cms";
	}
	return $height_array;
  }

  /**
   * Generates weight options
   */
  public function weightlist() {
    $weight_array = [];
    for($j=50; $j<=150; $j = $j + 0.1) {
        //\Drupal::messenger()->addMessage($j);
        $weight_array[] = round($j, 1) . " kgs";
    }
    return $weight_array;
  }

  /**
   * Generate BMI value based on height and weight
   */

  public function CalculateBmi($height, $weight) {

    $numerator_bmi = $weight * 2.2;
    $height_mt = $height*0.393701;
    $denominator_bmi = $height_mt * $height_mt;
    $bmi_value = round($numerator_bmi/$denominator_bmi * 703,2);
    if($bmi_value < 18.5){
        $bmi_status = "underweight";
    }else if($bmi_value >= 18.5 && $bmi_value < 25) {
        $bmi_status = "Normal";
    }else if ($bmi_value >= 25 && $bmi_value < 30) {
        $bmi_status = "Overweight";
    }else if ($bmi_value >= 30) {
        $bmi_status = "Obese";
    }
    return $bmi_status;
  }

}
