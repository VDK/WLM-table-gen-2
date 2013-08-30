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
    
  }
  
  protected function setup()
  {
    
    //Read CSV file.
    $csv = $this->helper( 'read_csv_file', tx('Data')->files->csv->tmp_name );
    $head_options = "";
    for ($i=0; $i <count($csv[0]) ; $i++) { 
      $head_options.="<option value='$i'>".trim($csv[0][$i])."</option>";
    }
    return array(
      'cbs_nrs' =>  $this->table('CbsNr')->order('gemeente')->execute(),
      'table_headers' => $head_options,
      'table_data' => base64_encode(serialize($csv))
    );


  }
  
  protected function result()
  {
    ini_set('register_argc_argv', 1);
    $result = array();
    $settings = array();
    $settings_items = array();
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
        default:
          $key = explode("_", $key);
          for ($i =0; $i<count($key); $i=$i+2){
            if ($value->get() != "n" && $value->get() != "" ){
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
      for ($i = 0; $i < count($settings_items); $i++){
        $rows[$h][$settings_items[$i]] = $csv[($h+1)][$settings[$settings_items[$i]]];
        //merge collumns
        if (isset($settings[$settings_items[$i]."A"])){
          for ($j=0; $j < count($settings[$settings_items[$i]."A"]); $j++){
            for ($k = 0; $k < (count($settings[$settings_items[$i]."A"][$j])-1); $k=$k+2){
              switch ($settings[$settings_items[$i]."A"][$j][$k]) {
                 case '0':
                   # spatie
                   $rows[$h][$settings_items[$i]] = $rows[$h][$settings_items[$i]]." "; 
                   break;
                case '1':
                  # geen spatie
                  break;
                case '2':
                    # spatie + cursief
                   $rows[$h][$settings_items[$i]] = $rows[$h][$settings_items[$i]]." ''";
                    break;  
                 case '3':
                   # streepje
                   $rows[$h][$settings_items[$i]] = $rows[$h][$settings_items[$i]]."-";
                   break;
                 case '4':
                   # comma
                   $rows[$h][$settings_items[$i]] = $rows[$h][$settings_items[$i]].", ";
                   break; 
               }
               $rows[$h][$settings_items[$i]] = $rows[$h][$settings_items[$i]].$csv[($h+1)][$settings[$settings_items[$i]."A"][$j][($k+1)]];
               if ($settings[$settings_items[$i]."A"][$j][$k] == '2'){
                  $rows[$h][$settings_items[$i]] = $rows[$h][$settings_items[$i]]."''";
               }
            }
          }
        }
      }

      //Break appart adres in prepreration of MIP requests
      $adres = array();
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
      }

      //$adres['positionering']
      //$adres ['straat']
      //$adres ['hnr'] 
      //$adres['toevoegsel'] 
      //$adres['postcode']
      //$adres['plaats']
      //$adres['gemeente']

      
    //  if ($mipresults){
    // //   while($row2 = mysqli_fetch_array($mipresults)) {
    // //     (empty($rows['bouwjaar']))? "" : $rows['bouwjaar'] = $rows['bouwjaar'].", ";
    // //     $rows['bouwjaar'] = $rows['bouwjaar'].$row2['datering_o']."&lt;ref name=MIP/&gt";
        
        
    // //     (empty($rows['oorspr-fun']))? "" : $rows['oorspr-fun'] = $rows['oorspr-fun'].", ";
    // //     $rows['oorspr-fun'] = $rows['oorspr-fun'].$row2['oorspr_fun'];
        
    // //     (empty($rows['architect']))? "" : $rows['architect'] = $rows['architect']."/ ";
    // //     $rows['architect'] = $rows['architect'].$row2['architect'];
        
    // //     (empty($rows['object']))? $rows['object'] = substr($row2['typech_obj'],11)."" : "";
    // //     ($row2['naam'] != "")? $rows['object'] = $rows['object']." ''".$row2['naam']."''" : "";
        
    // //     (empty($rows['postcode']))? $rows['postcode']  = $row2['pc'] : "";
       
    // //     (empty($rows['mip']))? "" : $rows['mip'] = $rows['mip'].", ";
    // //     $rows['mip'] = $rows['mip'].$row2['mip_sleute'];
    // //     $rows['coordinates'] = $this->helper('rd2wgs', $row2["x_coord"], $row2["y_coord"]);
    // //     $mip = true;
    // //   }
    //  }  

      //Rijksdriehoekcoördinaten
      if ($rd == "true"){
        $rows[$h]['coordinates'] = $this->helper('rd2wgs', $rows[$h]['xcoord'], $rows[$h]['ycoord']  );
      }

      //insert {{sorteer}} template
      if (isset($rows[$h]['bouwjaar'])){
        $rows[$h]['bouwjaar'] = $this->helper('sortYearWiki', $rows[$h]['bouwjaar'] );
      }

      if (empty($rows[$h]['objectnr'])){
        $rows[$h]['objectnr'] = $this->helper('getObjnr', $h);
      }
      else{
        $rows[$h]['objectnr'] = str_replace(" ", "", $rows[$h]['objectnr'] );
      }

      //count number of items per placename
      if(isset($rows[$h]['plaats'])){
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
    $result['provinceCat']        = $this->helper('getProvinceCategoryName', $result['cbs']->provincie);
    $result['ISO']                = $this->helper('getProvinceISO', $result['cbs']->provincie);
    return $result;

  }

}