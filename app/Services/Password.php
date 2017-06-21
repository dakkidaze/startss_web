<?php


namespace App\Services;

use App\Models\PasswordReset;
use App\Utils\Tools;
/***
 * Class Password
 * @package App\Services
 */

class Password
{
    /**
     * @param $email string
     * @return bool
     */
    public static function sendResetEmail($email){
        $pwdRst = new PasswordReset();
        $pwdRst->email = $email;
        $pwdRst->init_time = time();
        $pwdRst->expire_time = time() + 3600*24; // @todo
        $pwdRst->token = Tools::genRandomChar(64);
        if(!$pwdRst->save()){
            return false;
        }
        $subject = Config::get('appName')." Password Reset";
        $text    = '請訪問此鏈接申請重置密碼'.Config::get('baseUrl')."/password/token/".$pwdRst->token;
        try{
            Mail::send($email,$subject,$text);
        }catch (Exception $e){
            return false;
        }
        return true;
    }

	public static function sendActiveEmail($email, $id ,$activetoken){
        
        $subject = Config::get('appName')." Account Active";
        $text    = '請訪問此鏈接申請啟動帳號'.Config::get('baseUrl')."/password/active/".$id."/".$activetoken;
        try{
            Mail::send($email,$subject,$text);
        }catch (Exception $e){
            return false;
        }
        return true;
    }
    public static function resetBy($token,$password){

    }

}