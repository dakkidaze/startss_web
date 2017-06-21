<?php

namespace App\Controllers;



use App\Services\Auth;
use App\Services\View;
use App\Services\Config;
/**
 * BaseController
 */

class BaseController
{

    public $view;

    public $smarty;

    public function construct__(){

    }

    public function smarty(){
        $this->smarty = View::getSmarty();
        return $this->smarty;
    }

    public function view(){
    	@session_start();
    	if((Config::get('shell')=='true')&&(!isset($_SESSION['isstartss']) || !isset($_SESSION['authtime']) || ($_SESSION['authtime'] < (time()-Config::get('authtimeout'))))){
        	if($_SERVER['REQUEST_URI'] != '/'){
        		session_destroy();
        		header("Location: /");
			die();
        	}
		}else{
			$_SESSION['authtime'] = time();
		}
        return $this->smarty();
    }

    /**
     * @param $response
     * @param $res
     * @return mixed
     */
    public function echoJson($response,$res){
        return $response->getBody()->write(json_encode($res));
    }
}