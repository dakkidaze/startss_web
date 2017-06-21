<?php

namespace App\Controllers;

use App\Models\InviteCode;
use App\Models\Node;
use App\Models\CouponCode;
use App\Models\User;
use App\Utils\Tools;
use App\Services\Auth;
use App\Services\Analytics;
use App\Services\Messages;
use App\Models\TrafficLog;
/**
 *  Admin Controller
 */

class AdminController extends BaseController
{

    public function index()
    {
        $sts = new Analytics();
        return $this->view()->assign('sts',$sts)->display('admin/index.tpl');
    }

    public function node(){
        $nodes = Node::all();
        return $this->view()->assign('nodes',$nodes)->display('admin/node.tpl');
    }

    public function sys()
    {
        return $this->view()->display('admin/index.tpl');
    }

    public function invite()
    {
        $codes = InviteCode::where('user_id','=','0')->get();
        return $this->view()->assign('codes',$codes)->display('admin/invite.tpl');
    }

    public function addInvite($request, $response, $args)
    {
        $n =  $request->getParam('num');
        $prefix = $request->getParam('prefix');
        $uid =  $request->getParam('uid');
        if ($n < 1){
            $res['ret'] = 0;
            return $response->getBody()->write(json_encode($res));
        }
        for ($i = 0; $i < $n; $i++ ){
            $char = Tools::genRandomChar(32);
            $code = new InviteCode();
            $code->code = $prefix.$char;
            $code->user_id = $uid;
            $code->save();
        }
        $res['ret'] = 1;
        $res['msg'] = "邀请码添加成功";
        return $response->getBody()->write(json_encode($res));
    }

	public function trafficLog($request, $response, $args){
        $pageNum = 1;
        if(isset($request->getQueryParams()["page"])){
            $pageNum = $request->getQueryParams()["page"];
        }
        $traffic = TrafficLog::orderBy('id', 'desc')->paginate(15,['*'],'page',$pageNum);
        $traffic->setPath('/admin/trafficlog');
        return $this->view()->assign('logs', $traffic)->display('admin/trafficlog.tpl');
    }

    public function couponcode()
    {
        $codes = CouponCode::where('userid','>','0')->get();
        return $this->view()->assign('codes',$codes)->display('admin/couponcode.tpl');
    }
	public function addCouponCode($request, $response, $args)
    {
        $n =  $request->getParam('num');
        $prefix = $request->getParam('prefix');
        $port =  $request->getParam('port');
        if ($n < 1){
            $res['ret'] = 0;
			$res['msg'] = "錯誤數量";
            return $response->getBody()->write(json_encode($res));
        }
		
		$user = User::where('port',$port)->first();
		if ($user == null)
		{
			$res['ret'] = 0;
			$res['msg'] = "錯誤端口";
            return $response->getBody()->write(json_encode($res));
		}
        for ($i = 0; $i < $n; $i++ ){
            $char = $port."-".Tools::genRandomChar(32);
            $code = new CouponCode();
            $code->code = $prefix.$char;
            $code->userid = $user->id;
			$code->status = '1';
			$code->createuser = Auth::getUser()->id;
			$code->createdate = date('Y-m-d');
			$code->expiredate = date('Y-m-d',time()+2678400);
            $code->save();
        }
        $res['ret'] = 1;
        $res['msg'] = "成功生成到".$user->email;
        return $response->getBody()->write(json_encode($res));
    }
    public function sendWarn($request, $response, $args){
    	
    	// $pageNum = 1;
    	// error_reporting(E_ALL);
    	// ini_set("display_errors", 1);
     	
    	$result = Messages::sendLateCheckinWarning();
    	
    	if(is_array($result)){
    		$res['ret'] = 0;
    		$res['msg'] = $result;
//     		$res['debug'] = sizeof($result);
    	}else{
    		$res['ret'] = 1;
    		$res['msg'] = "成功";
    	}
 
    	return $response->getBody()->write(json_encode($res));
    }
}