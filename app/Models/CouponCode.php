<?php


namespace App\Models;

use App\Utils\Tools;

class CouponCode extends Model
{
    protected $table = "coupon_code";
	
	public function createDate(){
        return $this->attributes['createdate'];
    }
	public function expireDate(){
		if (strtotime($this->attributes['expiredate']) >= time()){
        	return $this->attributes['expiredate'];
		}else{
			return '<font color="red">'.$this->attributes['expiredate']."</font>";
		}
    }
	public function status(){
		$str_status = "";
        switch ($this->attributes['status']) {
            case '-1':
                $str_status = "已使用";
                break;
            case '1':
                $str_status = "未使用";
                break;
            
            default:
                $str_status = "未定義";
                break;
        }
		return $str_status;
    }
}