<?php namespace components\wlm_table_gen; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{
  
  protected
    $permissions = array(
      'table_gen' => 0
    );

  /*
    
    # The Views.php file
    
    This is where you define views.
    Views are used to provide you with an entire page worth of content.
    If you are using a view inside another view, you probably want to use
    a module or section instead. Views can only be loaded from the server-side.
    This discourages you from reloading them by replacing HTML, since they are
    intended to be entire pages.
    
    Call a view from the server-side using:
      tx('Component')->views('test')->get_html('function_name', Data($options));
    
    Read more about views here:
      https://github.com/Tuxion/mokuji/wiki/Views.php
    
  */

  protected function table_gen()
  {

    // 1: Intro + upload-veld
    // 2: Definieer kolommen + nog wat dingen
    // 3: Genereer en toon resultaat

    switch( tx('Data')->get->s )
    {
      case 'upload':
      case 'setup':
      case 'result':
        $section = tx('Data')->get->s;
        break;
      default:
        $section = 'upload';
        break;
    }

    return $this->section( $section );

  }
  
}



