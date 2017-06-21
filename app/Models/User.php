<?php

namespace App\Models;

/**
 * User Model
 */

use App\Utils\Tools;
use App\Utils\Hash;
use App\Models\InviteCode;
use App\Services\Config;

class User extends Model

{
    protected $table = "user";

    public $isLogin;

    public $isAdmin;

    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "https://secure.gravatar.com/avatar/$hash";
    }

    public function isAdmin(){
        return $this->attributes['is_admin'];
    }

	public function canChangeSSMethod(){
		if (Config::get('changeSSMethod') == 'true'){
			return true;
		}else{
			return false;
		}
        
    }

    public function lastSsTime(){
    	if($this->attributes['t'] == 0){
            return "从未連線";
        }
        return Tools::toDateTime($this->attributes['t']);
    }

    public function lastCheckInTime(){
        if($this->attributes['last_check_in_time'] == 0){
            return "从未签到";
        }
        return Tools::toDateTime($this->attributes['last_check_in_time']);
    }

    public function lastDrawTime(){
        if($this->attributes['last_gamble_time'] == 0){
            return "从未签到";
        }
        return Tools::toDateTime($this->attributes['last_gamble_time']);
    }

    public function regDate(){
        return $this->attributes['reg_date'];
    }

    public function updatePassword($pwd){
        $this->pass = Hash::passwordHash($pwd);
        $this->save();
    }

    public function updateSsPwd($pwd){
        $this->passwd = $pwd;
        $this->save();
    }

    public function updateMethod($method){
        $this->method = $method;
        $this->save();
    }

    public function addInviteCode(){
        $uid = $this->attributes['id'];
        $code = new InviteCode();
        $code->code = Tools::genRandomChar(32);
        $code->user = $uid;
        $code->save();
    }

    public function addManyInviteCodes($num){
        for($i = 0; $i < $num; $i++){
            $this->addInviteCode();
        }
    }

    public function trafficUsagePercent(){
        $total = $this->attributes['u'] + $this->attributes['d'];
        $transfer_enable = $this->attributes['transfer_enable'];
        $percent = $total/$transfer_enable;
        $percent = round($percent,2);
        $percent = $percent*100;
        return $percent;
    }

    public function enableTraffic(){
        $transfer_enable = $this->attributes['transfer_enable'];
        return Tools::flowAutoShow($transfer_enable);
    }

    public function usedTraffic(){
        $total = $this->attributes['u'] + $this->attributes['d'];
        return Tools::flowAutoShow($total);
    }

    public function unusedTraffic(){
        $total = $this->attributes['u'] + $this->attributes['d'];
        $transfer_enable = $this->attributes['transfer_enable'];
        return Tools::flowAutoShow($transfer_enable-$total);
    }
	
	public function trafficRatio(){
        $total = $this->attributes['u'] + $this->attributes['d'];
        $transfer_enable = $this->attributes['transfer_enable'];
		$used_ratio = $total / $transfer_enable;
        return round($used_ratio*100, 2);
    }
	
	public function guessUnusedTraffic($amount){
        $total = $this->attributes['u'] + $this->attributes['d'];
        $transfer_enable = $this->attributes['transfer_enable'];
        return Tools::flowAutoShow($transfer_enable-$total+$amount);
    }

    public function isAbleToCheckin(){
        $last = $this->attributes['last_check_in_time'];
        if(date('Ymd') <= date('Ymd', $last)){
        	return false ;
        }else{
        	return true;
        }
    }
    public function isAbleToDraw(){
        $last = $this->attributes['last_gamble_time'];
        if(date('Ymd') <= date('Ymd', $last)){
            $dayCount = $this->attributes['game_daytime_count'];
            if($dayCount<Config::get('limitedBet'))
            {
                return true;
            }else{
                return false ;
            }
        }else{
        	return true;
        }
    }
    public function drawChance(){
        $last = $this->attributes['last_gamble_time'];
        if(date('Ymd') <= date('Ymd', $last)){
            $dayCount = $this->attributes['game_daytime_count'];
            return (Config::get('limitedBet')-$dayCount);
        }else{
        	return Config::get('limitedBet');
        }
    }
    /*
     * @param traffic 单位 MB
     */
    public function addTraffic($traffic){
    }

    public function inviteCodes(){
        $uid = $this->attributes['id'];
        // return InviteCode::where('user_id',$uid)->get();
        return InviteCode::where('user_id',$uid)->where('used_id',null)->get();
        
    }
	
	public function credit(){
		return Tools::toCredit($this->attributes['credit']);
	}

}
