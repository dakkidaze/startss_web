<?php

namespace App\Services;


class Language
{
  
   public function __construct(){

   }
   
   public static function getLanguageSet(){
        	$lan_text = json_decode(file_get_contents(BASE_PATH.'/resources/lang/'.Config::get('lang').'.json'),true);
        	return $lan_text;
    }
}