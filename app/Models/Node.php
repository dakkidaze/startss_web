<?php

namespace App\Models;
use App\Models\TrafficLog;
use App\Utils\Tools;
/**
 * Node Model
 */

class Node extends Model

{
    protected $table = "ss_node";
	public function avgFlow(){
		if($this->attributes['id'] == 3){
			$lastminupload = TrafficLog::where('log_time','>',(time()-60-(57960)))->where('log_time','<=',time()-(57960))->where('node_id',$this->attributes['id'])->sum('u');
			$lastmindownload = TrafficLog::where('log_time','>',(time()-60-(57960)))->where('log_time','<=',time()-(57960))->where('node_id',$this->attributes['id'])->sum('d');
		}else{
			$lastminupload = TrafficLog::where('log_time','>',(time()-60))->where('log_time','<=',time())->where('node_id',$this->attributes['id'])->sum('u');
			$lastmindownload = TrafficLog::where('log_time','>',(time()-60))->where('log_time','<=',time())->where('node_id',$this->attributes['id'])->sum('d');
		}
		return Tools::flowAutoShowBits(($lastminupload+$lastmindownload)/60)."ps";
	}
	public function avgPpl(){
		if($this->attributes['id'] == 3){
			$lastminuser = TrafficLog::where('log_time','>',(time()-60-(57960)))->where('log_time','<=',time()-(57960))->where('node_id',$this->attributes['id'])->count('user_id');
			
		}else{
			$lastminuser = TrafficLog::where('log_time','>',(time()-60))->where('log_time','<=',time())->where('node_id',$this->attributes['id'])->count('user_id');
			
		}
		return $lastminuser;
	}
	public function totalFlow(){
		$lastminupload = TrafficLog::where('node_id',$this->attributes['id'])->sum('u');
		$lastmindownload = TrafficLog::where('node_id',$this->attributes['id'])->sum('d');
		return Tools::flowAutoShow($lastminupload+$lastmindownload);
	}
}
