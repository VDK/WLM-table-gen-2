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
    $this->helper( 'read_csv_file', tx('Data')->files->csv->tmp_name );
    return array(
      'cbs_nrs' =>  $this
        ->table('CbsNr')
        ->order('gemeente')
        ->execute()
    );

  }
  
  protected function result()
  {

    $cbs = $this->table('CbsNr')->pk(tx('Data')->post->cbs_nr_id)->execute_single();

    return array(
      'gemeente' => $cbs,
      'provinceCat' => $this->helper('getProvinceCategoryName', $cbs->provincie),
      'ISO' => $this->helper('getProvinceISO', $cbs->provincie),
      'bron' => array ('url'       => tx('Data')->post->url,
                       'titel'     => tx('Data')->post->titel,
                       'uitgever'  => tx('Data')->post->uitgever,
                       'formaat'   => tx('Data')->post->formaat,
                       'pubDate'   => tx('Data')->post->datum,
                       'accessDate'=> $this->helper('nlDate', date('j F Y'))),
      'wikigem'=>tx('Data')->post->wikigem

    );

  }
  
}
