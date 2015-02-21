<?php namespace components\wlm_table_gen; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{
  
  protected
    $permissions = array(
      'upload' => 0,
      'setup' => 0,
      'result' => 0
    );

  /*
    
    # The Sections.php file
    
    This is where you define sections.
    Sections are used to insert a part of a page that is for private use inside the component.
    This allows you to reuse pieces of HTML and allows you to reload by replacing the HTML.
    If your section is very autonomous and might be useful for other components,
    you probably should rewrite it as a Module.php function instead.
    
    Call a section from the client-side using:
      http://mysite.com/index.php?section=test/function_name
    
    Call a section from the server-side using:
      tx('Component')->sections('test')->get_html('function_name', Data($options));
    
    Read more about sections here:
      https://github.com/Tuxion/mokuji/wiki/Sections.php
    
  */

  protected function upload(){
    return $this->helper('getFooter');
  }
  
  protected function setup()
  {
    
    //Read CSV file.
   $csv = $this->helper( 'read_csv_file', tx('Data')->files->csv->tmp_name ,tx('Data')->post['delimiter']->get() );
    $head_options = "";
    for ($i=0; $i <count($csv[0]) ; $i++) { 
      $head_options.="<option value='$i'>".trim($csv[0][$i])."</option>";
    }
    return array(
      'cbs_nrs' =>  $this->table('CbsNr')->order('gemeente')->execute(),
      'table_headers' => $head_options,
      'table_data' => base64_encode(serialize($csv)),
      'footer' =>$this->helper('getFooter')
    );


  }
 
  
  protected function result()
  {
    ini_set('register_argc_argv', 1);
    $result = array();
    $settings = array();
    $settings_items = array();
    $current_item = "";
    //Get all POST data
    foreach ( tx('Data')->post as $key => $value) {
      switch ($key) {
        case 'cbs_nr_id':
         $result['cbs'] = $this->table('CbsNr')->pk($value->get())->execute_single();
          break;
        case 'wikigem': 
          $result['wikigem'] = $value->get();
          break;
        case 'url':
        case 'titel':
        case 'uitgever':
        case 'formaat':
        case 'datum':
          $result['bron'][$key] = $value->get();
          break;  
        case 'table_data':
          // $csv = utf8_encode(); 
          $csv = unserialize(base64_decode($value->get()));
          // $csv = utf8_decode($csv);
          break;
        case 'rd':
          $rd = $value->get();
          break;
        case 'latlong':
          $latlong =  $value->get();
          break;
        default:
          if ($value->get() != "n" && $value->get() != "" ){
            $key = explode("_", $key);
            for ($i =0; $i<count($key); $i=$i+2){
              if ($i == 0 && count($key) == 1){
                $settings[$key[$i]] = trim($value->get());
                $current_item = $key[$i];
                array_push($settings_items, $current_item);
              }
              elseif ($i != 0) {
                $settings[$current_item."A"][$key[($i-1)]][$key[$i]]= trim($value->get());
              }
            }
          }
          break;
      }

    }
    $result['placenames'] = '"'.$result['cbs']->gemeente->get().'", ';
    //Process settings
    $rows = array();
    $placenames = array();
    $placestats = array();
    for ($h =0; $h<(count($csv)-1); $h++){
      $rows[$h]['bouwjaar']  = "";
      $rows[$h]['oorspr-fun']= "";
      $rows[$h]['architect'] = "";
      $rows[$h]['object']    = "";
      $rows[$h]['adres']     = "";
      $rows[$h]['postcode']  = "";
      $rows[$h]['mip']       = "";
      $rows[$h]['xcoord']    = "";
      $rows[$h]['ycoord']    = "";
      $rows[$h]['mip']       = "";
      // $rows[$h]['coordinates']['lat']  = "";
      // $rows[$h]['coordinates']['long'] = "";
      for ($i = 0; $i < count($settings_items); $i++){
        if (isset($csv[($h+1)][$settings[$settings_items[$i]]])){
          $rows[$h][$settings_items[$i]] = $csv[($h+1)][$settings[$settings_items[$i]]];
        }
        //merge collumns
        if (isset($settings[$settings_items[$i]."A"])){
          for ($j=0; $j < count($settings[$settings_items[$i]."A"]); $j++){
            for ($k = 0; $k < (count($settings[$settings_items[$i]."A"][$j])-1); $k=$k+2){
              $rows[$h][$settings_items[$i]] = $this->helper('mergeItems', 
                $rows[$h][$settings_items[$i]], 
                $csv[($h+1)][$settings[$settings_items[$i]."A"][$j][($k+1)]],
                $settings[$settings_items[$i]."A"][$j][$k] );    
            }
          }
        }
      }

      // Break appart adres in prepreration of MIP requests

      //Rsults into:

      //$adres['positionering']
      //$adres['straat']
      //$adres['hnr'] 
      //$adres['toevoegsel'] 
      //$adres['postcode']
      //$adres['plaats']
      //$adres['gemeente']

      $adres = array();
      $collect_street = "";
      $adres['gemeente'] = $result['cbs']->gemeente->get();
      if (!(isset($rows[$h]['huisnummer']))){
        $adresParts = explode(" ", $rows[$h]['adres']);
        for ($k = 1; $k < count($adresParts); $k++){
          if (is_numeric($adresParts[$k])){
            $adres ['hnr'] = $adresParts[$k];
            if ((!(empty($adresParts[($k+1)]))) && (!(is_numeric($adresParts[($k+1)]))) ){
              if (strlen($adresParts[($k+1)]) == 1){
                $adres ['toevoegsel'] = $adresParts[($k+1)];
              }
              elseif (strtolower($adresParts[($k+1)]) == "bis" ) {
                $adres ['toevoegsel'] = $adresParts[($k+1)];
              } 
            }
            $collect_street = true;
          }
          elseif (is_numeric(trim(substr($adresParts[$k],0,-1)))){
            $adres ['hnr'] = trim(substr($adresParts[$k],0,-1));
            $adres ['toevoegsel'] = trim(mb_substr($adresParts[$k],-1));
            $collect_street = true;
          }
          elseif (is_numeric(trim(substr($adresParts[$k],0,-3))) && strtolower((trim(mb_substr($adresParts[$k],-3)))) == "bis"  ){
            $adres ['hnr'] = trim(substr($adresParts[$k],0,-3));
            $adres ['toevoegsel'] = trim(mb_substr($adresParts[$k],-3));
            $collect_street = true;
          }
          if ($collect_street == true){
            $adres ['straat'] = "";
            for ($l = 0; $l < $k; $l++){
              $adres ['straat'] .= $adresParts[$l]." ";
            }
            $adres ['straat'] = trim( $adres ['straat']);
            break;
          }
        }
      }
      if(isset($rows[$h]['plaats'])){
          $adres['plaats'] = $rows[$h]['plaats'];
      }
      if (isset($rows[$h]['positionering'])){
        $adres['positionering']  = $rows[$h]['positionering'];
        $rows[$h]['adres'] =  $rows[$h]['adres']." ".$rows[$h]['positionering'];
      }
      if (isset($rows[$h]['huisnummer'])){
        $adres['hnr'] = $rows[$h]['huisnummer'];
        if (!(is_numeric($adres['hnr'])) && is_numeric(trim(substr($adres['hnr'],0,-1)))){
          $adres ['hnr'] = trim(substr($adres['hnr'],0,-1));
          $adres ['toevoegsel'] = trim(mb_substr($adres['hnr'],-1));
        }
        elseif (is_numeric(trim(substr($adres['hnr'],0,-3))) && strtolower((trim(mb_substr($adres['hnr'],-3)))) == "bis"  ){
          $adres ['hnr'] = trim(substr($adres['hnr'],0,-3));
          $adres ['toevoegsel'] = trim(mb_substr($adres['hnr'],-3));
        }
        $rows[$h]['adres'] =  $rows[$h]['adres']." ".$rows[$h]['huisnummer'];
      }
      
      if (isset($rows[$h]['toevoegsel'])){
        $adres ['toevoegsel']  = $rows[$h]['toevoegsel'];
        $rows[$h]['adres'] =  $rows[$h]['adres'].$rows[$h]['toevoegsel'];
      }
      if (isset($rows[$h]['postcode'])){
        $adres ['postcode']  = $rows[$h]['postcode'];
        if (strlen($adres['postcode'] == 6)){
          $adres['postcode'] = substr($adres['postcode'],0,-2)." ".mb_substr($adres['postcode'],-2);
        }

        $rows[$h]['postcode'] = str_replace(".", "",$rows[$h]['postcode']);
      }

      //MIP data handling
     //  $mipresults = $this->helper('getMIPdata', $adres);

     //  //These settings need to come from the GUI
     //  $mipSetting['datering_o']  = '4';
     //  $mipSetting['oorspr_fun']  = '4';
     //  $mipSetting['architect']   = '4';
     //  $mipSetting['typech_obj']  = '4';
     //  $mipSetting['naam']        = '2';
     //  $mipSetting['pc']          = '4';
     //  $mipSetting['mip_sleutel'] = '4';
      
     // foreach($mipresults as $mipresult){
     //   $rows[$h]['bouwjaar']  = $this->helper('mergeItems', $rows[$h]['bouwjaar'],  $mipresult['datering_o'],  $mipSetting['datering_o'] );
     //   $rows[$h]['oorspr-fun']= $this->helper('mergeItems', $rows[$h]['oorspr-fun'],$mipresult['oorspr_fun'],  $mipSetting['oorspr_fun'] );
     //   $rows[$h]['architect'] = $this->helper('mergeItems', $rows[$h]['architect'], $mipresult['architect'],   $mipSetting['architect'] );
     //   $rows[$h]['object']    = $this->helper('mergeItems', $rows[$h]['object'],    $mipresult['typech_obj'],  $mipSetting['typech_obj'] );
     //   $rows[$h]['object']    = $this->helper('mergeItems', $rows[$h]['object'],    $mipresult['naam'],        $mipSetting['naam']);
     //   $rows[$h]['postcode']  = $this->helper('mergeItems', $rows[$h]['postcode'],  $mipresult['pc'],          $mipSetting['pc'] );
     //   $rows[$h]['mip']       = $this->helper('mergeItems', $rows[$h]['mip'],       $mipresult['mip_sleutel'], $mipSetting['mip_sleutel'] );

     //    $rows[$h]['coordinates'] = $this->helper('rd2wgs', $mipresult["x_coord"], $mipresult["y_coord"]);
     //    // $mip = true;
     // }  

      //Rijksdriehoekcoördinaten
      if ($rd == "true" && !(empty($rows[$h]['xcoord'])) && !(empty($rows[$h]['ycoord']))){
        $rows[$h]['coordinates'] = $this->helper('rd2wgs', $rows[$h]['xcoord'], $rows[$h]['ycoord']  );
      }
      if($latlong == "true"){
        $rows[$h]['coordinates']['lat']  = $rows[$h]['lat'];
        $rows[$h]['coordinates']['long'] = $rows[$h]['long'];
      }

      //insert {{sorteer}} template
      if (isset($rows[$h]['bouwjaar'])){
        $rows[$h]['bouwjaar'] = $this->helper('sortYearWiki', $rows[$h]['bouwjaar'] );
        $rows[$h]['bouwjaar'] = str_replace(".", "",$rows[$h]['bouwjaar']);
      }

      if (empty($rows[$h]['objectnr'])){
        $rows[$h]['objectnr'] = $this->helper('getObjnr', $h);
      }
      else{
        $rows[$h]['objectnr'] = str_replace(" ", "", $rows[$h]['objectnr'] );
      }

      //count number of items per placename
      if(isset($rows[$h]['plaats']) && $rows[$h]['plaats'] != "" ){
        $rows[$h]['plaats'] = trim($rows[$h]['plaats']);
        if (!(empty($placestats[$rows[$h]['plaats']]['count']))){ 
          $placestats[$rows[$h]['plaats']]['count']++;
        } 
       else{
          $placestats[$rows[$h]['plaats']]['count'] = 1;
          $result['placenames'] .= '"'.$rows[$h]['plaats'].'", ';
        }
      }
    }
    

    //sort
    // $rows = $this->helper('array_sort', $rows, "adres");
    // if (!(empty($placestats))){
    //   $rows = $this->helper('array_sort', $rows, "plaats");
    // //   foreach ($placestats as $key => $value) {
    // //     //center of place to be ruled out by geocoding process.
    // //   //  $placestats[$value]['coordinates'] = $this->helper('getGoogleMapsData', $value);
    // //   }
    // }
    // else{
    // //  $placestats[$result['cbs']->gemeente->get()] =  $this->helper('getGoogleMapsData', $result['cbs']->gemeente->get());
    //   //find center of municipality
    // }
    $result['placenames']         = substr($result['placenames'], 0,-2);
    $result['placestats']         = $placestats;
    $result['rows']               = $rows;
    $result['bron']['accessDate'] = $this->helper('nlDate', date('j F Y'));
    $result['provinceCat']        = $this->helper('getProvinceCategoryName', $result['cbs']->provincie->get());
    $result['gemeenteCat']        = $this->helper('getGemeenteCategoryName', $result['cbs']->bouwwerk_in_cat->get(), $result['cbs']->gemeente->get());
    $result['ISO']                = $this->helper('getProvinceISO', $result['cbs']->provincie->get());
    return $result;

  }

}