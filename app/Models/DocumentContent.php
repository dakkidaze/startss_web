<?php


namespace App\Models;

use App\Utils\Tools;

class DocumentContent extends Model
{
    protected $table = "document_content";
	
	public function createDate(){
		$time = strtotime($this->attributes['datetime']);
        return date('m月d日',$time);
    }
	
}