<?php

namespace App\Controllers;

use App\Models\InviteCode;
use App\Services\Config;
use App\Utils\Check;
use App\Utils\Tools;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\Password;
use App\Utils\Hash;
use App\Services\Auth;
use App\Models\User;

/**
 *  AuthController
 */

class AuthController extends BaseController
{

    public function login()
    {
    	session_start();
    	
    	if((Config::get('shell')=='true')&&(!isset($_SESSION['isstartss']) || !isset($_SESSION['authtime']) || ($_SESSION['authtime'] < (time()-Config::get('authtimeout'))))){
        	return $this->view()->display('baidu.tpl');
		}else{
			$_SESSION['authtime'] = time();
		}
		
        return $this->view()->display('auth/login.tpl');
    }

	public function loginMsg($request, $response, $args)
    {
    	session_start();
    	
    	if((Config::get('shell')=='true')&&(!isset($_SESSION['isstartss']) || !isset($_SESSION['authtime']) || ($_SESSION['authtime'] < (time()-Config::get('authtimeout'))))){
        	return $this->view()->display('baidu.tpl');
		}else{
			$_SESSION['authtime'] = time();
		}
		
        return $this->view()->assign('code',$args['message'])->display('auth/login.tpl');
    }

    public function loginHandle($request, $response, $args)
    {
        // $data = $request->post('sdf');
        $email =  $request->getParam('email');
		
		
        $passwd = $request->getParam('passwd');
        $rememberMe = $request->getParam('remember_me');
		if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			$type = "E";
			$email = strtolower($email);
			$user = User::where('email','=',$email)->first();
		}else{
			$type = "U";
			$user = User::where('user_name','=',$email)->first();
		}
		
        
        // Handle Login
        

        if ($user == null){
            $rs['ret'] = 0;
            $rs['msg'] = "401 郵箱或者密碼錯誤".$type;
            return $response->getBody()->write(json_encode($rs));
        }

		
        if (!Hash::checkPassword($user->pass,$passwd)){
            $rs['ret'] = 0;
            $rs['msg'] = "402 郵箱或者密碼錯誤";
            return $response->getBody()->write(json_encode($rs));
        }
		
		if($user->enable == 0){
			 $rs['ret'] = 0;
            $rs['msg'] = "403 帳號未啟動，請檢查郵箱";
            return $response->getBody()->write(json_encode($rs));
		}
		
        // @todo
        $time =  3600*24;
        if($rememberMe){
            $time = 3600*24*7;
        }
        Auth::login($user->id,$time);
        $rs['ret'] = 1;
        $rs['msg'] = "歡迎回來";
        return $response->getBody()->write(json_encode($rs));
    }

    public function register($request, $response, $next)
    {
    	session_start();
    	
    	if((Config::get('shell')=='true')&&(!isset($_SESSION['isstartss']) || !isset($_SESSION['authtime']) || ($_SESSION['authtime'] < (time()-Config::get('authtimeout'))))){
	        	return $this->view()->display('baidu.tpl');
		}else{
			$_SESSION['authtime'] = time();
		}
		
        $ary = $request->getQueryParams();
        $code = "";
        if(isset($ary['code'])){
            $code = $ary['code'];
        }
        return $this->view()->assign('code',$code)->display('auth/register.tpl');
    }

    public function registerHandle($request, $response, $next)
    {
        $name =  $request->getParam('name');
        $email =  $request->getParam('email');
        $email = strtolower($email);
        $passwd = $request->getParam('passwd');
        $repasswd = $request->getParam('repasswd');
        $code = $request->getParam('code');
        // check code
        $c = InviteCode::where('code',$code)->first();
        if ( $c == null) {
            $res['ret'] = 0;
            $res['msg'] = "邀請碼無效";
            return $response->getBody()->write(json_encode($res));
        }else{
        	if($c->use_time == null || $c->use_time < time()-60*60*3)
			{
				//old expire user cleanup
				$c->use_time == null;
            	$c->save();
				if($c->used_id != null){
					$old_user = User::where('id','=',$c->used_id)->first();
					if($old_user!=null){
						$old_user->delete();
					}
				}
			}else{
				$res['ret'] = 0;
	            $res['msg'] = "邀請碼未失效";
	            return $response->getBody()->write(json_encode($res));
			}
        }

        // check email format
        if(!Check::isEmailLegal($email)){
            $res['ret'] = 0;
            $res['msg'] = "郵箱無效";
            return $response->getBody()->write(json_encode($res));
        }
		 // check email blacklist
        if(!Check::isEmailBackListed($email)){
            $res['ret'] = 0;
            $res['msg'] = "郵箱禁止註冊";
            return $response->getBody()->write(json_encode($res));
        }
        // check pwd length
        if(strlen($passwd)<8){
            $res['ret'] = 0;
            $res['msg'] = "密碼太短";
            return $response->getBody()->write(json_encode($res));
        }

        // check pwd re
        if($passwd != $repasswd){
            $res['ret'] = 0;
            $res['msg'] = "兩次密碼輸入不符";
            return $response->getBody()->write(json_encode($res));
        }

        // check email
        $user = User::where('email',$email)->first();
        if ( $user != null) {
            $res['ret'] = 0;
            $res['msg'] = "郵箱已經被註冊了";
            return $response->getBody()->write(json_encode($res));
        }

        // do reg user
        $user = new User();
        $user->user_name = $name;
        $user->email = $email;
        $user->pass = Hash::passwordHash($passwd);
        $user->passwd = Tools::genRandomChar(6);
        $user->port = Tools::getLastPort()+1;
		$user->enable = 0;
        $user->t = 0;
        $user->u = 0;
        $user->d = 0;
        $user->transfer_enable = Tools::toGB(Config::get('defaultTraffic'));
		
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		   $user->reg_ip = "HTTP_CLIENT_IP: ". $_SERVER['HTTP_CLIENT_IP'];
		}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		   $user->reg_ip = "HTTP_X_FORWARDED_FOR: ".$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
		   $user->reg_ip = "REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR'];
		}
		
        $user->invite_num = Config::get('inviteNum');
        $user->ref_by = $c->user_id;

        if($user->save()){
        	Password::sendActiveEmail($email, $user->id, md5($user->pass.$user->passwd));
            $res['ret'] = 1;
            $res['msg'] = "註冊成功，請檢查郵箱";
			$c->used_id = $user->id;
			$c->use_time = time();
            $c->save();
            return $response->getBody()->write(json_encode($res));
        }
        $res['ret'] = 0;
        $res['msg'] = "未知錯誤";
        return $response->getBody()->write(json_encode($res));
    }

    public function logout($request, $response, $next){
        Auth::logout();
        $newResponse = $response->withStatus(302)->withHeader('Location', '/auth/login');
        return $newResponse;
    }

}