<?php

namespace App\Controllers;

use App\Models\InviteCode;
use App\Services\Auth;
use App\Models\Node,App\Models\TrafficLog,App\Models\Packages,App\Models\CheckInLog,App\Models\PurchaseHistory,App\Models\CouponCode,App\Models\DocumentContent;
use App\Services\Config;
use App\Utils\Hash,App\Utils\Tools;


/**
 *  HomeController
 */
class UserController extends BaseController
{

    private $user;

    public function __construct()
    {
        $this->user = Auth::getUser();
    }

    public function index()
    {
    	$announces = DocumentContent::where('type', 'user_announcement')->where('sort_order', '>', 0)->orderBy('sort_order', 'desc')->get();
        return $this->view()->assign('announces', $announces)->display('user/index.tpl');
    }

    public function node()
    {
        $user = Auth::getUser();
        $nodes = Node::where('type', 1)->orderBy('sort')->get();
        return $this->view()->assign('nodes', $nodes)->assign('user', $user)->display('user/node.tpl');
    }


    public function nodeInfo($request, $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);

        if ($node == null) {

        }
        $ary['server'] = $node->server;
        $ary['server_port'] = $this->user->port;
        $ary['password'] = $this->user->passwd;
        $ary['method'] = $node->method;
        if ($node->custom_method) {
            $ary['method'] = $this->user->method;
        }
        $json = json_encode($ary);
        $json_show = json_encode($ary, JSON_PRETTY_PRINT);
        $ssurl = $ary['method'] . ":" . $ary['password'] . "@" . $ary['server'] . ":" . $ary['server_port'];
        $ssqr = "ss://" . base64_encode($ssurl);

        $surge_base = Config::get('baseUrl') . "/downloads/ProxyBase.conf";
        $surge_proxy = "#!PROXY-OVERRIDE:ProxyBase.conf\n";
        $surge_proxy .= "[Proxy]\n";
        $surge_proxy .= "Proxy = custom," . $ary['server'] . "," . $ary['server_port'] . "," . $ary['method'] . "," . $ary['password'] . "," . Config::get('baseUrl') . "/downloads/SSEncrypt.module";
        return $this->view()->assign('json', $json)->assign('json_show', $json_show)->assign('ssqr', $ssqr)->assign('surge_base', $surge_base)->assign('surge_proxy', $surge_proxy)->display('user/nodeinfo.tpl');
    }

    public function profile()
    {
        return $this->view()->display('user/profile.tpl');
    }

    public function edit()
    {
        return $this->view()->display('user/edit.tpl');
    }


    public function invite()
    {
        $codes = $this->user->inviteCodes();
        return $this->view()->assign('codes', $codes)->display('user/invite.tpl');
    }

    public function doInvite($request, $response, $args)
    {
        $n = $this->user->invite_num;
        if ($n < 1) {
            $res['ret'] = 0;
            return $response->getBody()->write(json_encode($res));
        }
        for ($i = 0; $i < $n; $i++) {
            $char = Tools::genRandomChar(32);
            $code = new InviteCode();
            $code->code = $char;
            $code->user_id = $this->user->id;
            $code->save();
        }
        $this->user->invite_num = 0;
        $this->user->save();
        $res['ret'] = 1;
        return $this->echoJson($response, $res);
    }

    public function sys()
    {
        return $this->view()->assign('ana', "")->display('user/sys.tpl');
    }

    public function updatePassword($request, $response, $args)
    {
        $oldpwd = $request->getParam('oldpwd');
        $pwd = $request->getParam('pwd');
        $repwd = $request->getParam('repwd');
        $user = $this->user;
        if (!Hash::checkPassword($user->pass, $oldpwd)) {
            $res['ret'] = 0;
            $res['msg'] = "旧密码错误";
            return $response->getBody()->write(json_encode($res));
        }
        if ($pwd != $repwd) {
            $res['ret'] = 0;
            $res['msg'] = "两次输入不符合";
            return $response->getBody()->write(json_encode($res));
        }

        if (strlen($pwd) < 8) {
            $res['ret'] = 0;
            $res['msg'] = "密码太短啦";
            return $response->getBody()->write(json_encode($res));
        }
        $hashPwd = Hash::passwordHash($pwd);
        $user->pass = $hashPwd;
        $user->save();

        $res['ret'] = 1;
        $res['msg'] = "ok";
        return $this->echoJson($response, $res);
    }

    public function updateSsPwd($request, $response, $args)
    {
        $user = Auth::getUser();
        $pwd = $request->getParam('sspwd');
        $user->updateSsPwd($pwd);
        $res['ret'] = 1;
        return $this->echoJson($response, $res);
    }

    public function updateMethod($request, $response, $args)
    {
        $user = Auth::getUser();
        $method = $request->getParam('method');
        $method = strtolower($method);
        $user->updateMethod($method);
        $res['ret'] = 1;
        return $this->echoJson($response, $res);
    }

	public function updateBuypackage($request, $response, $args)
    {
        $user = Auth::getUser();
        $traffpackage = $request->getParam('traffpackage');
        $package = Packages::find($traffpackage);
		if(($user->credit - $package->price) >= 0){
        $user->transfer_enable = $user->transfer_enable + $package->package_traffic_m;
		$user->credit = $user->credit - $package->price;
        $user->save();
		$ph = new PurchaseHistory();
		$ph->user_id = $user->id;
		$ph->package_info = ($package);
		$ph->user_snapshot = ($user);
		
		$ph->save();
		$res['ret'] = 1;
		$res['msg'] = "可用流量為".$user->unusedTraffic()." (增加了".$package->package_traffic_m().")";
		}else{
			$res['ret'] = 0;
			$res['msg'] = "結餘不足";
		}
        
        return $this->echoJson($response, $res);
    }

    public function logout($request, $response, $args)
    {
    	session_start();
    	session_destroy();
        Auth::logout();
        $newResponse = $response->withStatus(302)->withHeader('Location', '/auth/login');
        return $newResponse;
    }

    public function doCheckIn($request, $response, $args)
    {
        if (!$this->user->isAbleToCheckin()) {
            $res['msg'] = "您似乎已经签到过了...";
            $res['ret'] = 1;
            return $response->getBody()->write(json_encode($res));
        }
        $traffic = rand(Config::get('checkinMin'), Config::get('checkinMax'));
        $this->user->transfer_enable = $this->user->transfer_enable + Tools::toMB($traffic);
        $this->user->last_check_in_time = time();
        $this->user->save();
        $res['msg'] = sprintf("获得了 %u MB流量.", $traffic);
        $res['ret'] = 1;
        return $this->echoJson($response, $res);
    }

    public function doDraw($request, $response, $args)
    {
        if (!$this->user->isAbleToDraw()) {
            $res['msg'] = "您似乎已经沒有抽流量機會了...";
            $res['ret'] = 1;
            return $response->getBody()->write(json_encode($res));
        }

        
        // $traffic = rand(Config::get('checkinMin'), Config::get('checkinMax'));
        $betInput = intval(Config::get('eachBet'));
        $betSafeCap = intval(Config::get('bet_safe_cap'));

        if($this->user->unusedTraffic() > ($betSafeCap +  $betInput))
        {
            $res['msg'] = "您似乎已经只有很少流量了...";
            $res['ret'] = 1;
            return $response->getBody()->write(json_encode($res));
        }

        $traffic = 0;
        $exFactorStart = floatval(Config::get('ex_factor_start'));
        $exFactorStep = floatval(Config::get('ex_factor_steping'));
        $exFactor = ($exFactorStart - ($exFactorStep * (intval(Config::get('limitedBet')) - $this->user->drawChance())))*1000;
        $betWinRate = intval(Config::get('win_rate'));
        $betKeepRate = intval(Config::get('keep_rate'));
        $betLoseRate = intval(Config::get('lose_rate'));
        
// Draw Logic Start
        $traffic = 500; // Dummy Draw
        $draw_1_chance = floatval(Config::get('gamble_result_chance_1')) * $exFactor * $betWinRate;
        $draw_1_chance_endAt = 1+$draw_1_chance;
        $draw_2_chance = floatval(Config::get('gamble_result_chance_2')) * $exFactor * $betWinRate;
        $draw_2_chance_endAt = $draw_1_chance+$draw_2_chance;
        $draw_3_chance = floatval(Config::get('gamble_result_chance_3')) * $exFactor * $betWinRate;
        $draw_3_chance_endAt = $draw_2_chance_endAt+$draw_3_chance;
        $draw_4_chance = floatval(Config::get('gamble_result_chance_4')) * $exFactor * $betKeepRate;
        $draw_4_chance_endAt = $draw_3_chance_endAt+$draw_4_chance;
        $draw_5_chance = floatval(Config::get('gamble_result_chance_5')) * $exFactor * $betLoseRate;
        $draw_5_chance_endAt = $draw_4_chance_endAt+$draw_5_chance;
        $draw_6_chance = floatval(Config::get('gamble_result_chance_6')) * $exFactor * $betLoseRate;
        $draw_6_chance_endAt = $draw_5_chance_endAt+$draw_6_chance;

        $drawSum = $draw_1_chance+$draw_2_chance+$draw_3_chance+$draw_4_chance+$draw_5_chance+$draw_6_chance;
        $randomKey = rand(1, $drawSum);

        // $res['debug_range'] = array();
        // array_push($res['debug_range'],$draw_1_chance_endAt);
        // array_push($res['debug_range'],$draw_2_chance_endAt);
        // array_push($res['debug_range'],$draw_3_chance_endAt);
        // array_push($res['debug_range'],$draw_4_chance_endAt);
        // array_push($res['debug_range'],$draw_5_chance_endAt);
        // array_push($res['debug_range'],$draw_6_chance_endAt);
        // $res['debug_range_SUM'] = $drawSum;
        // $res['randomKey'] = $randomKey;

        if($randomKey>=1&&$randomKey<=$draw_1_chance_endAt)
        {
            $traffic = Config::get('gamble_result_amount_1');
        }else if($randomKey>$draw_1_chance_endAt&&$randomKey<=$draw_2_chance_endAt)
        {
            $traffic = Config::get('gamble_result_amount_2');
        }else if($randomKey>$draw_2_chance_endAt&&$randomKey<=$draw_3_chance_endAt)
        {
            $traffic = Config::get('gamble_result_amount_3');
        }else if($randomKey>$draw_3_chance_endAt&&$randomKey<=$draw_4_chance_endAt)
        {
            $traffic = Config::get('gamble_result_amount_4');
        }else if($randomKey>$draw_4_chance_endAt&&$randomKey<=$draw_5_chance_endAt)
        {
            $traffic = Config::get('gamble_result_amount_5');
        }else if($randomKey>$draw_5_chance_endAt&&$randomKey<=$draw_6_chance_endAt)
        {
            $traffic = Config::get('gamble_result_amount_6');
        }

        // Write result
        $this->user->transfer_enable = $this->user->transfer_enable + Tools::toMB($traffic - $betInput);
        
        if(date('Ymd') <= date('Ymd', $this->user->last_gamble_time)){
            $this->user->game_daytime_count = $this->user->game_daytime_count+1;
        }else{
            $this->user->game_daytime_count = 1;
        }
        $this->user->last_gamble_time = time();
        $this->user->gamed_time = $this->user->gamed_time+1;
        $this->user->save();
        $res['remain'] = Config::get('limitedBet') - $this->user->game_daytime_count;
        $res['msg'] = sprintf("获得了 %u MB流量.", $traffic);
        $res['ret'] = 1;
        return $this->echoJson($response, $res);
    }

    public function kill($request, $response, $args)
    {
        return $this->view()->display('user/kill.tpl');
    }

    public function handleKill($request, $response, $args)
    {
        $user = Auth::getUser();
        $passwd = $request->getParam('passwd');
        // check passwd
        $res = array();
        if (!Hash::checkPassword($user->pass, $passwd)) {
            $res['ret'] = 0;
            $res['msg'] = " 密码错误";
            return $this->echoJson($response, $res);
        }
        Auth::logout();
        $user->delete();
        $res['ret'] = 1;
        $res['msg'] = "GG!您的帐号已经从我们的系统中删除.";
        return $this->echoJson($response, $res);
    }

    public function trafficLog($request, $response, $args){
        $pageNum = 1;
        if(isset($request->getQueryParams()["page"])){
            $pageNum = $request->getQueryParams()["page"];
        }
        $traffic = TrafficLog::where('user_id',$this->user->port)->orderBy('id', 'desc')->paginate(15,['*'],'page',$pageNum);
        $traffic->setPath('/user/trafficlog');
        return $this->view()->assign('logs', $traffic)->display('user/trafficlog.tpl');
    }
	
	public function buyTraffic($request, $response, $args){
		if(isset($args['id'])){
			$id = $args['id'];
			
			$package = Packages::find($id);
			return $this->view()->assign('data', array('package'=>$package, 'credit'=>$this->user->credit, 'id'=>$id))->display('user/buytraffic.tpl');
		}else{
        $pageNum = 1;
        if(isset($request->getQueryParams()["page"])){
            $pageNum = $request->getQueryParams()["page"];
        }
        $package = Packages::where('package_enable','1')->orderBy('price', 'asc')->paginate(15,['*'],'page',$pageNum);
        $package->setPath('/user/buytraffic');
        return $this->view()->assign('packages', $package)->display('user/buytraffic.tpl');
		}
    }
	public function addCredit($request, $response, $args){
		
        return $this->view()->display('user/addcredit.tpl');
		
    }
	public function resetFlow($request, $response, $args){
		
		$coupons = CouponCode::where('userid',$this->user->id)->where('status','1')->where('expiredate','>', date('Y-m-d'))->orderBy('expiredate', 'asc')->get();
        return $this->view()->assign('coupons', $coupons)->display('user/resetflow.tpl');
		
    }
	public function doResetFlow($request, $response, $args){
		
		if(isset($args['code']))
		{
			$user = Auth::getUser();
			$coupon = CouponCode::where('userid',$user->id)->where('code',$args['code'])->where('status','1')->where('expiredate','>', date('Y-m-d'))->orderBy('expiredate', 'asc')->first();
			if($coupon == null)
			{
				$res['msg'] ='錯誤重置碼';
        		$res['ret'] = 0;
			}else{
				$coupon->status = '-1';
				$coupon->save();
				$user->u = 0;
				$user->d = 0;
				$user->save();
				$res['msg'] = '重置成功';
				$res['ret'] = 1;
			}
		}else{
			$res['msg'] ='沒有重置碼';
        	$res['ret'] = 0;
		}
		
		return $this->echoJson($response, $res);
		
    }
}
