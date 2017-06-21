<?php

namespace App\Controllers\Admin;

use App\Models\DocumentContent;
use App\Controllers\BaseController;

class DocController extends BaseController
{
    public function index(){
        $docs = DocumentContent::all();
        return $this->view()->assign('documents',$docs)->display('admin/doc/index.tpl');
    }

    public function create($request, $response, $args){
        return $this->view()->display('admin/doc/create.tpl');
    }

    public function add($request, $response, $args){
        $doc = new DocumentContent();
        $doc->type =  $request->getParam('type');
        $doc->content =  $request->getParam('content');
        $doc->sort_order =  $request->getParam('sort_order');
        if(!$doc->save()){
            $rs['ret'] = 0;
            $rs['msg'] = "添加失败";
            return $response->getBody()->write(json_encode($rs));
        }
        $rs['ret'] = 1;
        $rs['msg'] = "文件添加成功";
        return $response->getBody()->write(json_encode($rs));
    }


    public function deleteGet($request, $response, $args){
        $id = $args['id'];
        $doc = DocumentContent::find($id);
        $doc->delete();
        $newResponse = $response->withStatus(302)->withHeader('Location', '/admin/doc');
        return $newResponse;
    }
}