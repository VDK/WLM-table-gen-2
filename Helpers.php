<?php namespace components\wlm_table_gen; if(!defined('TX')) die('No direct access.');

class Helpers extends \dependencies\BaseComponent
{
  
  protected
    $permissions = array(
      'getFooter' => 0,
      'read_csv_file' => 0,
      'getProvinceISO' => 0,
      'getGemeenteCategoryName' => 0,
      'getProvinceCategoryName' => 0,
      'rd2wgs' =>0,
      'sortYearWiki' => 0,
      'getObjnr' => 0,
      'getGoogleMapsData' => 0,
      'normalizer' => 0,
      'array_sort' => 0,
      'getMIPdata' => 0,
      'nlDate' => 0


    );

  /*
    
    # The Helpers.php file
    
    This is where you define helper functions.
    Helpers are used to make common operations easier for programmers to implement.
    If you find yourself copying the same pieces of code over and over again
    you probably want to make a function here to easily maintain your code.
    
    Call a helper using:
      tx('Component')->helpers('component_name')->call('function_name' array($arguments));
    
    Read more about actions here:
      https://github.com/Tuxion/mokuji/wiki/Helpers.php
    
  */

  public function getMIPdata($address = array()){
     // $this->table('MIP')->
    //   $query = 'SELECT * FROM _mip 
    // WHERE gemeente = "'.$GLOBALS['gemeente-naam'].'" 
    // AND straat = "'.$straat.'" 
    // AND hne = "'.$hnr.'" 
    // AND provincie="'.$GLOBALS['provincie'].'"';
    
    // ($toevoegsel != "")? $query = $query.' 
    //   AND toevoeging ="'.$toevoegsel.'";' : $query = $query.' AND toevoeging is NULL;';
    
    // $mipresults = mysqli_query($con,$query);
    // 

  }
  public function getFooter(){
    return '<div id="footer">Made with the power of stroopwafels by <a href="http://www.veradekok.nl" target="_blanc">Vera de Kok</a><br/>with help from <a href="https://github.com/Avaq" target="_blanc">Avaq</a> and <a href="http://www.bartroorda.nl" target="_blanc">Bart Roorda</a><br/></div>';
  }
  
  public function read_csv_file($filename, $delimiter)  {
    iconv_set_encoding("internal_encoding", "UTF-8");
    //Open file.
    $csv =  array();
    $handler = fopen($filename, "r");
    //Loop file rows.
    $row =0;
   while (($data = fgetcsv($handler, 1000, $delimiter)) !== FALSE) {
      for ($i=0; $i <count($data); $i++){
        
        $data[$i] = mb_convert_encoding( $data[$i], 'UTF-8');
        $data[$i] = trim($data[$i]);
      }
      $csv[$row] = $data;    
      $row++;
    }

   //  //Close file handler.
    fclose($handler);
    return $csv;

  }

  public function getGemeenteCategoryName($categorySet, $gemName){
    if ($categorySet == 1){
      return "Bouwwerk in ".$gemName;
    }
    else{
      return $gemName;
    }

  }
  public function getProvinceISO($province = ""){
    $province = strtolower($province);
    switch ($province){
      case "drenthe":       return "nl-dr"; break;
      case "flevoland":     return "nl-fl"; break;
      case "fryslân":
      case "friesland":     return "nl-fr"; break;
      case "gelderland":    return "nl-ge"; break;
      case "groningen":     return "nl-gr"; break;
      case "limburg":       return "nl-li"; break;
      case "noord-brabant": return "nl-nb"; break;
      case "noord-holland": return "nl-nh"; break;
      case "overijssel":    return "nl-ov"; break;
      case "utrecht":       return "nl-ut"; break;
      case "zeeland":       return "nl-ze"; break;
      case "zuid-holland":  return "nl-zh"; break;
     }

  }
  public function getProvinceCategoryName($province=""){
  $province = strtolower($province);
  switch ($province){
    case "drenthe":
    case "flevoland":
    case "friesland":
    case "gelderland":
    case "overijssel":
    case "zeeland": return ucfirst($province); break;

    case "noord-brabant":
    case "noord-holland":
    case "zuid-holland":
      $nameParts= explode ("-", $province);
      return ucfirst($nameParts[0])."-".ucfirst($nameParts[1]); break;
    
    case "groningen":
    case "utrecht": return ucfirst($province." (provincie)"); break;
    
    case "limburg": return "Limburg (Nederland)"; break;
    case "fryslân": return "Friesland"; break;
   }

  }


    public function sortYearWiki ($year){
      if ($year != ""){
        $yearparts = explode (" ", $year);
        // look for 4 digit years
        if (is_numeric(mb_substr($yearparts[0], 0,4))){
          return $year;
        }
        for ($i = 1; $i < count($yearparts); $i++){
          if (is_numeric(mb_substr($yearparts[$i],0,4))){
            return "{{Sorteer|".mb_substr($yearparts[$i],0,4)."|".$year."}}";
          }
        }
        //look for 2 digit century names
        for ($i = 0; $i < count($yearparts); $i++){
          if (is_numeric(mb_substr($yearparts[$i],0,2))){
            return "{{Sorteer|".(mb_substr($yearparts[$i],0,2)-1)."00|".$year."}}";
          }
        }
      }
    return $year;
  }

public function array_sort($a, $subkey) {
    foreach($a as $k=>$v) {
      $b[$k] = strtolower($v[$subkey]);
    }
    asort($b);
    foreach($b as $key=>$val) {
      $c[] = $a[$key];
    }
    return $c;
}

  public function getObjnr($i){
    $label ="WN";
    if (($i+1) <10){
      $label .= "00";
    }
    elseif (($i+1) <100){
      $label .= "0";
    }
    $label .= ($i+1);
    return $label;
  }
  public function nlDate($datum){
      /*
   
      $datum = date($parameters);
     */
      // Vervang de maand,  klein
      $daRum = str_replace("january",      "januari",      $datum);
      $datum = str_replace("februay",      "februari",     $datum);
      $datum = str_replace("march",        "maart",        $datum);
      $datum = str_replace("may",          "mei",          $datum);
      $datum = str_replace("june",         "juni",         $datum);
      $datum = str_replace("july",         "juli",         $datum);
      $datum = str_replace("august",       "augustus",     $datum);
      $datum = str_replace("october",      "oktober",      $datum);
     
      // Vervang de maand, hoofdletters
      $datum = str_replace("January",      "januari",      $datum);
      $datum = str_replace("February",     "februari",     $datum);
      $datum = str_replace("March",        "maart",        $datum);
      $datum = str_replace("April",        "april",        $datum);
      $datum = str_replace("May",          "mei",          $datum);
      $datum = str_replace("June",         "juni",         $datum);
      $datum = str_replace("July",         "juli",         $datum);
      $datum = str_replace("August",       "augustus",     $datum);
      $datum = str_replace("September",    "september",    $datum);
      $datum = str_replace("December",     "december",     $datum);
      $datum = str_replace("October",      "oktober",      $datum);
      $datum = str_replace("November",     "november",     $datum);
     
      // Vervang de maand, kort
      $datum = str_replace("May",          "Mei",          $datum);
      $datum = str_replace("Oct",          "Okt",          $datum);
      $datum = str_replace("Mar",          "Maa",          $datum);
     
      // Vervang de dag, klein
      $datum = str_replace("monday",       "maandag",      $datum);
      $datum = str_replace("tuesday",      "dinsdag",      $datum);
      $datum = str_replace("wednesday",    "woensdag",     $datum);
      $datum = str_replace("thursday",     "donderdag",    $datum);
      $datum = str_replace("friday",       "vrijdag",      $datum);
      $datum = str_replace("saturday",     "zaterdag",     $datum);
      $datum = str_replace("sunday",       "zondag",       $datum);
   
      // Vervang de dag, hoofdletters
      $datum = str_replace("Monday",       "Maandag",      $datum);
      $datum = str_replace("Tuesday",      "Dinsdag",      $datum);
      $datum = str_replace("Wednesday",    "Woensdag",     $datum);
      $datum = str_replace("Thursday",     "Donderdag",    $datum);
      $datum = str_replace("Friday",       "Vrijdag",      $datum);
      $datum = str_replace("Saturday",     "Zaterdag",     $datum);
      $datum = str_replace("Sunday",       "Zondag",       $datum);
   
      // Vervang de verkorting van de dag, hoofdletters
      $datum = str_replace("Mon",          "Maa",          $datum);
      $datum = str_replace("Tue",          "Din",          $datum);
      $datum = str_replace("Wed",          "Woe",          $datum);
      $datum = str_replace("Thu",          "Don",          $datum);
      $datum = str_replace("Fri",          "Vri",          $datum);
      $datum = str_replace("Sat",          "Zat",          $datum);
      $datum = str_replace("Sun",          "Zon",          $datum);
   
      return $datum;
  }

  /* geocoding functions */
   
  public function getGoogleMapsData($address){
    $address = rawurlencode($this->helper('normalizer',$address));
    $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
    $output= json_decode($geocode);
    if ($output->status =="OK"){
      $geo['lat'] = $output->results[0]->geometry->location->lat;
      $geo['long'] = $output->results[0]->geometry->location->lng;
      @$geo['zip'] = $output->results[0]->address_components[6]->long_name;
      return $geo;
      break;
    }
    else if ($output->status=="OVER_QUERY_LIMIT"){
      switch (rand(0,3)){
      case 0:
        echo "<h1>Google Maps is mad, wait a second and reload this page</h1>";
        break;
      case 1:
        echo "<h1>Google Maps is feeling cranky, wait a second and reload this page</h1>";
        break;
      case 2:
        echo "<h1>Google Maps thinks you ask too many questions, waith a second and reload this page</h1>";
        break;
      case 3:
        echo "<h1>Google Maps is feeling upset, take a sip of tea and relaod this page</h1>";
        break;
      }
    }
  }
  // changes é into e etc.. Because Google Maps is weird.
  public function normalizer($original_string){
    $some_special_chars = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ñ", "â", "Â", "ë", "Ë");
    $replacement_chars = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "a", "A", "e", "E");

    $replaced_string = str_replace($some_special_chars, $replacement_chars, $original_string);

    return $replaced_string;
  }
  public function rd2wgs ($x, $y){
    // Calculate WGS84 coördinates
    /* retrieved at http://www.god-object.com/2009/10/23/convert-rijksdriehoekscordinaten-to-latitudelongitude/ */
    $dX = ($x - 155000) * pow(10, - 5);
    $dY = ($y - 463000) * pow(10, - 5);
    $SomN = (3235.65389 * $dY) + (- 32.58297 * pow($dX, 2)) + (- 0.2475 *
         pow($dY, 2)) + (- 0.84978 * pow($dX, 2) *
         $dY) + (- 0.0655 * pow($dY, 3)) + (- 0.01709 *
         pow($dX, 2) * pow($dY, 2)) + (- 0.00738 *
         $dX) + (0.0053 * pow($dX, 4)) + (- 0.00039 *
         pow($dX, 2) * pow($dY, 3)) + (0.00033 * pow(
            $dX, 4) * $dY) + (- 0.00012 *
         $dX * $dY);
    $SomE = (5260.52916 * $dX) + (105.94684 * $dX * $dY) + (2.45656 *
         $dX * pow($dY, 2)) + (- 0.81885 * pow(
            $dX, 3)) + (0.05594 *
         $dX * pow($dY, 3)) + (- 0.05607 * pow(
            $dX, 3) * $dY) + (0.01199 *
         $dY) + (- 0.00256 * pow($dX, 3) * pow(
            $dY, 2)) + (0.00128 *
         $dX * pow($dY, 4)) + (0.00022 * pow($dY,
            2)) + (- 0.00022 * pow(
            $dX, 2)) + (0.00026 *
         pow($dX, 5));
 
    $Latitude = 52.15517 + ($SomN / 3600);
    $Longitude = 5.387206 + ($SomE / 3600);
 
    return array(
        'lat' => $Latitude ,
        'long' => $Longitude);
    }
}
