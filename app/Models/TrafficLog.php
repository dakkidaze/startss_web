<?php


namespace App\Models;

use App\Utils\Tools,App\Models\User;

class TrafficLog extends Model
{
    protected $table = "user_traffic_log";

    public function node()
    {
        return Node::find($this->attributes['node_id']);
    }

    public function totalUsed()
    {
        return Tools::flowAutoShow($this->attributes['u'] + $this->attributes['d']);
    }

	public function traffic()
    {
        return Tools::flowAutoShow($this->attributes['traffic']);
    }

    public function logTime()
    {
        return Tools::toDateTime($this->attributes['log_time']);
    }
	
	public function user_id()
    {
        return User::where('port',$this->attributes['user_id'])->get()[0]['email'];
    }
}