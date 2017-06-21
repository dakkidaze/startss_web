<?php


namespace App\Models;

use App\Utils\Tools;

class Packages extends Model
{
    protected $table = "user_packages";
	
	public function package_type()
	{
	return Tools::toPackageType($this->attributes['package_type']);
	}
	public function package_traffic_m()
	{
	return Tools::flowAutoShow($this->attributes['package_traffic_m']);
	}
	public function price()
	{
	return Tools::toCredit($this->attributes['price']);
	}
}