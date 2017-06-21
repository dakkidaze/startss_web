<?php


namespace App\Utils;
use App\Services\Config;

class Check
{
    //
    public static function  isEmailLegal($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }else{
            return false;
        }
    }
	 //
    public static function  isEmailBackListed($email){
    	$eDomain = "@".substr(strrchr($email, "@"), 1);
		$eDomain = strtolower($eDomain);
		switch (Config::get('mail_filter_mode')) {
			case 'black':
				{
					$domainList = Config::get('mail_blacklist')?Config::get('mail_blacklist'):"";
					$pos = strpos($domainList, $eDomain);
			        if ($pos === false) {
			            return true;
			        }else{
			            return false;
			        }
				}
				break;
			case 'white':
				{
					$domainList = Config::get('mail_whitelist')?Config::get('mail_whitelist'):"";
					$pos = strpos($domainList, $eDomain);
			        if ($pos === true) {
			            return true;
			        }else{
			            return false;
			        }
				}
				break;
			default:
				return true;
				break;
		}
		
    }
	
}