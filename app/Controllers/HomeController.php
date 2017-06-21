<?php

namespace App\Controllers;

use App\Models\InviteCode;
use App\Services\Auth;
use App\Services\Config;

/**
 *  HomeController
 */
class HomeController extends BaseController
{

    public function index()
    {
    	session_start();
    	if((Config::get('shell')=='true')&&(!isset($_SESSION['isstartss']) || !isset($_SESSION['authtime']) || ($_SESSION['authtime'] < (time()-Config::get('authtimeout'))))){
        	return $this->view()->display('baidu.tpl');
		}else{
			$_SESSION['authtime'] = time();
		}
		
			return $this->view()->display('index.tpl');
			
		
    }
    public function baidu($request, $response, $args)
    {
    	// var_dump($request);
    	session_start();
    	if($request->getParam('wd')==Config::get('shellspell')){
    		$_SESSION['isstartss'] = true;
			$_SESSION['authtime'] = time();
        	return $this->view()->display('index.tpl');
		}else{
			// return $this->view()->display('baidu.tpl');
			$newResponse = $response->withStatus(302)->withHeader('Location', 'https://www.baidu.com/baidu?myselectvalue=0&word='.$request->getParam('wd'));
			return $newResponse;
		}
    }
    public function code()
    {
    	session_start();
    	if((Config::get('shell')=='true')&&(!isset($_SESSION['isstartss']) || !isset($_SESSION['authtime']) || ($_SESSION['authtime'] < (time()-Config::get('authtimeout'))))){
        	return $this->view()->display('baidu.tpl');
		}else{
			$_SESSION['authtime'] = time();
		}
		        $codes = InviteCode::where('user_id', '=', '0')->take(10)->get();
        return $this->view()->assign('codes', $codes)->display('code.tpl');
    }

    public function down()
    {

    }

    public function tos()
    {
    	session_start();
    	if((Config::get('shell')=='true')&&(!isset($_SESSION['isstartss']) || !isset($_SESSION['authtime']) || ($_SESSION['authtime'] < (time()-Config::get('authtimeout'))))){
        	return $this->view()->display('baidu.tpl');
		}else{
			$_SESSION['authtime'] = time();
		}
        return $this->view()->display('tos.tpl');
    }

}