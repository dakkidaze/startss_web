<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Controllers\BaseController;
use App\Utils\Hash;
use App\Services\Config;

class UserController extends BaseController
{
    public function index($request, $response, $args){
    	
        $pageNum = 1;
        if(isset($request->getQueryParams()["page"])){
            $pageNum = $request->getQueryParams()["page"];
        }
        $sqlorder = 'id';
        $sqlsort = 'asc';
        if(isset($request->getQueryParams()["order"])){
        	
        	switch($request->getQueryParams()["order"]){
        			case 'id': $sqlorder = 'id';
        			break;
        			case 'email': $sqlorder = 'email';
        			break;
        			case 'reg_date': $sqlorder = 'reg_date';
        			break;
        			case 'transfer_enable': $sqlorder = 'transfer_enable';
        			break;
        			case 'port': $sqlorder = 'port';
        			break;
        			case 't': $sqlorder = 't';
        			break;
        			case 'last_check_in_time': $sqlorder = 'last_check_in_time';
        			break;
        			default: $sqlorder = 'id';
        			break;
        	}
        	
        	if(isset($request->getQueryParams()["sort"])){
        		$sqlsort = ($request->getQueryParams()["sort"]=='a')?'asc':'desc';
        	}
        }
        
		$users = User::orderBy($sqlorder, $sqlsort)->paginate(15,['*'],'page',$pageNum);
		
		
        
        if(isset($request->getQueryParams()["order"]))
		{
			$users->setPath('/admin/user?&order='.$request->getQueryParams()["order"].'&sort='.$request->getQueryParams()["sort"]);
		}else{
			$users->setPath('/admin/user');
		}
        
        return $this->view()->assign('users',$users)->display('admin/user/index.tpl');
    }

	public function search($request, $response, $args){
        $pageNum = 1;
		$kw = $args['kw'];
        if(isset($request->getQueryParams()["page"])){
            $pageNum = $request->getQueryParams()["page"];
        }
		if($kw != null || $kw != ""){
			$rawWhere = "id = '".$kw."' OR user_name = '".$kw."' OR email = '".$kw."' OR port = '".$kw."'";
		}
        $users = User::whereRaw($rawWhere)->paginate(15,['*'],'page',$pageNum);
        $users->setPath('/admin/user/search/'.$kw);
        return $this->view()->assign('users',$users)->display('admin/user/index.tpl');
    }

	public function searchRatio($request, $response, $args){
        $pageNum = 1;
		$kw = $args['kw'];
        if(isset($request->getQueryParams()["page"])){
            $pageNum = $request->getQueryParams()["page"];
        }
		if($kw != null || $kw != ""){
			$rawWhere = " (`u`+`d`) / `transfer_enable` ".$kw;
		}
        $users = User::whereRaw($rawWhere)->paginate(15,['*'],'page',$pageNum);
        $users->setPath('/admin/user/searchRatio/'.$kw);
        return $this->view()->assign('users',$users)->display('admin/user/index.tpl');
    }
	
    public function edit($request, $response, $args){
        $id = $args['id'];
        $user = User::find($id);
        if ($user == null){

        }
        return $this->view()->assign('user',$user)->assign('refer',$_SERVER['HTTP_REFERER'])->display('admin/user/edit.tpl');
    }

    public function update($request, $response, $args){
        $id = $args['id'];
        $user = User::find($id);

        $user->email =  $request->getParam('email');
        if ($request->getParam('pass') != '') {
            $user->pass = Hash::passwordHash($request->getParam('pass'));
        }
        $user->port =  $request->getParam('port');
        $user->passwd = $request->getParam('passwd');
        $user->transfer_enable = $request->getParam('transfer_enable');
        $user->invite_num = $request->getParam('invite_num');
        $user->method = $request->getParam('method');
        $user->enable = $request->getParam('enable');
        $user->is_admin = $request->getParam('is_admin');
        $user->ref_by = $request->getParam('ref_by');
		$user->credit = $request->getParam('credit');
        if(!$user->save()){
            $rs['ret'] = 0;
            $rs['msg'] = "修改失败";
            return $response->getBody()->write(json_encode($rs));
        }
        $rs['ret'] = 1;
        $rs['msg'] = "修改成功";
        return $response->getBody()->write(json_encode($rs));
    }

    public function delete($request, $response, $args){
        $id = $args['id'];
        $user = User::find($id);
        if(!$user->delete()){
            $rs['ret'] = 0;
            $rs['msg'] = "删除失败";
            return $response->getBody()->write(json_encode($rs));
        }
        $rs['ret'] = 1;
        $rs['msg'] = "删除成功";
        return $response->getBody()->write(json_encode($rs));
    }

    public function deleteGet($request, $response, $args){
        $id = $args['id'];
        $user = User::find($id);
        $user->delete();       
//         $newResponse = $response->withStatus(302)->withHeader('Location', '/admin/user');
        $newResponse = $response->withStatus(302)->withHeader('Location', $_SERVER['HTTP_REFERER']);
        return $newResponse;
    }
	
	public function tools($request, $response, $args){
        $action = $args['action'];
		$pageNum = 1;
        if(isset($request->getQueryParams()["page"])){
            $pageNum = $request->getQueryParams()["page"];
        }
        $sqlorder = 'id';
        $sqlsort = 'asc';
        if(isset($request->getQueryParams()["order"])){
        	 
        	switch($request->getQueryParams()["order"]){
        		case 'id': $sqlorder = 'id';
        		break;
        		case 'email': $sqlorder = 'email';
        		break;
        		case 'reg_date': $sqlorder = 'reg_date';
        		break;
        		case 'transfer_enable': $sqlorder = 'transfer_enable';
        		break;
        		case 'port': $sqlorder = 'port';
        		break;
        		case 't': $sqlorder = 't';
        		break;
        		case 'last_check_in_time': $sqlorder = 'last_check_in_time';
        		break;
        		default: $sqlorder = 'id';
        		break;
        	}
        	 
        	if(isset($request->getQueryParams()["sort"])){
        		$sqlsort = ($request->getQueryParams()["sort"]=='a')?'asc':'desc';
        	}
        }
		$actionsql = '';
		switch ($action){
			case 'latecheckin':{$users = User::where('last_check_in_time', '<', time()-Config::get('latecheckin')*86400)->where('reg_date', '<', time()-Config::get('latecheckin')*86400)->orderBy($sqlorder, $sqlsort)->paginate(15,['*'],'page',$pageNum);}break;
			case 'lasthr':{$users = User::where('t', '>',(time()-3600))->orderBy($sqlorder, $sqlsort)->paginate(15,['*'],'page',$pageNum);}break;
			
			default:break;
		}
		
        // $users = User::where('last_check_in_time', '<', Config::get('latecheckin')*24*60*60)->paginate(15,['*'],'page',$pageNum);
        $users->setPath('/admin/user/tools/latecheckin');
        return $this->view()->assign('users',$users)->display('admin/user/tools/latecheckin.tpl');
        // $user = User::find($id);
        // $user->delete();
        // $newResponse = $response->withStatus(302)->withHeader('Location', '/admin/user');
        // return $newResponse;
    }
}