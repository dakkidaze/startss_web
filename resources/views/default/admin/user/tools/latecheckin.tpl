{include file='admin/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            用户列表
            <small>User List</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        {$users->render()}
                        <table class="table table-hover">
                            <tr>
                                <th><a href="javascript:setOrder('id');">ID</a></th>
                                <th><a href="javascript:setOrder('email');">邮箱</a></th>
                                <th><a href="javascript:setOrder('port');">端口</a></th>
                                <!-- <th>加密方式</th> -->
                                <th><a href="javascript:setOrder('reg_date');">註冊日期</a></th>
                                <th><a href="javascript:setOrder('transfer_enable');">已用流量/总流量</a></th>
                                <th><a href="javascript:setOrder('t');">最后連線时间</a></th>
                                <th><a href="javascript:setOrder('last_check_in_time');">最后簽到时间</a></th>
                                <th>操作</th>
                            </tr>
                            {foreach $users as $user}
                            <tr>
                                <td>#{$user->id}</td>
                                <td>{$user->email}</td>
                                <td>{$user->port}</td>
                                <!-- <td>{$user->method}</td> -->
                                <td>{$user->regDate()}</td>
                                <td>{$user->usedTraffic()}/{$user->enableTraffic()}</td>
                                <td>{$user->lastSsTime()}</td>
                                <td>{$user->lastCheckInTime()}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="/admin/user/{$user->id}/edit">编辑</a>
                                    <a class="btn btn-danger btn-sm" id="delete" value="{$user->id}" href="/admin/user/{$user->id}/delete">删除</a>
                                </td>
                            </tr>
                            {/foreach}
                        </table>
                        {$users->render()}
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script>
    $(document).ready(function(){
        function delete(){
            $.ajax({
                type:"DELETE",
                url:"/admin/user/",
                dataType:"json",
                data:{
                    name: $("#name").val()
                },
                success:function(data){
                    if(data.ret){
                        $("#msg-error").hide(100);
                        $("#msg-success").show(100);
                        $("#msg-success-p").html(data.msg);
                        window.setTimeout("location.href='/admin/user'", 2000);
                    }else{
                        $("#msg-error").hide(10);
                        $("#msg-error").show(100);
                        $("#msg-error-p").html(data.msg);
                    }
                },
                error:function(jqXHR){
                    $("#msg-error").hide(10);
                    $("#msg-error").show(100);
                    $("#msg-error-p").html("发生错误："+jqXHR.status);
                }
            });
        }
        $("html").keydown(function(event){
            if(event.keyCode==13){
                login();
            }
        });
        $("#delete").click(function(){
            delete();
        });
        $("#ok-close").click(function(){
            $("#msg-success").hide(100);
        });
        $("#error-close").click(function(){
            $("#msg-error").hide(100);
        });
    })
</script>
<script language="JavaScript">
function searchClick(){
	$kw_text = $("#search_kw").val();
	if($kw_text != ""){
	$linkText = "location.href='/admin/user/search/"+$kw_text+"'";
	console.log($linkText);
   window.setTimeout($linkText, 100);
	}else{
		alert("No Keyword");
	}
};

function setOrder($order){
	$urlpath = location.pathname;
	var vars = [], hash, ex_order='id', ex_sort='a', new_order, new_sort;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

	
    for(var i = 0; i < hashes.length; i++)
    {
        if(hashes[i].length >1){
    	hash = hashes[i].split('=');
    	if(hash.length>1){
	    	if(hash[0]=="order"){
	    		ex_order = hash[1];
	        	}else if(hash[0]=="sort"){
	    		ex_sort = hash[1];
	        	}else{
	        		vars.push(hashes[i]);
	            	}
	        }
        }
    }
    if(ex_order == $order){
    	if(ex_sort == 'a'){
    		new_order=ex_order; new_sort='d';
// 			alert(ex_order+"->d");
        	}else{
        		new_order=ex_order; new_sort='a';
//         		alert(ex_order+"->a");
            	}
        }else{
        	new_order=$order; new_sort=ex_sort;
//         	alert($order+"->"+ex_sort);
            }
    $parmas = '?';
    for(var i = 0; i < vars.length; i++){
    	$parmas= $parmas+"&"+vars[i];
        }
    $parmas = $parmas+"&order="+new_order+"&sort="+new_sort;
    $linkText = "location.href='"+$urlpath+$parmas+"'";
    window.setTimeout($linkText, 100);
}


</script>
{include file='admin/footer.tpl'}