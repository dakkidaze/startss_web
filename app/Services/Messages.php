<?php


namespace App\Services;

use App\Models\User;
use App\Utils\Tools;
/***
 * Class Messages
 * @package App\Services
 */

class Messages
{
    /**
     * @param $email string
     * @return bool
     */
    public static function sendLateCheckinWarning(){
    	
    	$lateUsers = User::where('last_check_in_time', '<', time()-Config::get('latecheckin')*86400);
//     	$lateUsers = User::where('email', '=', "windshadow.lam@gmail.com");
//     	$pageNum =ceil($lateUser.length/50);
//     	return $pageNum;
    	$lateUserss = $lateUsers;
    	$sendCount = 0;
    	$fault_list = array();
    	
    	$subject = Config::get('appName')." Checkin Overdue";
    	try{
    		Mail::send('admin@weedstudio.hk',"Checkin Overdue Send in ".date('m/d/Y h:i:s a', time()),"Started");
    	}catch (Exception $e){
    		
    	}
    	foreach ($lateUserss->get() as $lateUser){
    		try{
//     			echo $lateUser->email;
    			$text = Config::get('appName')."將清理".Config::get('latecheckin')."天內未簽到用戶，請立即簽到，否則會被清理。";
    			Mail::send($lateUser->email,$subject,$text);
    			$sendCount++;
    		}catch (Exception $e){
    			array_push($fault_list, $lateUser->email);
    		}
    		
    	}
//     	echo json_encode($fault_list);
    	try{
    		Mail::send('admin@weedstudio.hk',"Checkin Overdue Sent in ".date('m/d/Y h:i:s a', time()),"End");
    	}catch (Exception $e){
    	
    	}
    	if($sendCount == $lateUsers->count()){return true;}else{return $fault_list;}
        
    }


    public static function resetBy($token,$password){

    }

}