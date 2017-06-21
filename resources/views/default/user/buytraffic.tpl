{include file='user/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            購買流量
            <small>Traffic Store</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="col-xs-12">
                <div id="msg-error" class="alert alert-warning alert-dismissable" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> 出錯了!</h4>

                    <p id="msg-error-p"></p>
                </div>
                <div id="msg-success" class="alert alert-success alert-dismissable" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> 購買成功!</h4>

                    <p id="msg-success-p"></p>
                </div>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                	{if (isset($packages))}
                    <div class="box-body table-responsive no-padding">
                        {$packages->render()}
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>流量套餐</th>
                                <th>可用流量</th>
                                <th>價格</th>
                                <th>套餐類型</th>
                                <th>購買</th>
                            </tr>
                            {foreach $packages as $package}
                                <tr>
                                    <td>#{$package->id}</td>
                                    <td>{$package->packagename}</td>
                                    <td>{$package->package_traffic_m()}</td>
                                    <td>{$package->price()}</td>
                                    <td>{$package->package_type()}</td>
                                    <td><a class="btn btn-info btn-sm" href="/user/buy/{$package->id}">購買</a></td>
                                </tr>
                            {/foreach}
                        </table>
                        {$packages->render()}
                    </div><!-- /.box-body -->
                    {/if}
                    {if (isset($data))}
                    <div class="box-body table-responsive no-padding">
                       <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-shopping-cart"></i>

                        <h3 class="box-title">購買詳情</h3>
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                        	
                            <dt>現時流量</dt>
                            <dd>{$user->unusedTraffic()}</dd>
                            <dt>購買增加流量</dt>
                            <dd>{$data['package']->packagename}({$data['package']->package_traffic_m()})</dd>
                            <dt>購買後流量</dt>
                            <dd>{$user->guessUnusedTraffic($data['package']->package_traffic_m)}</dd>
                            <dt>現時結餘</dt>
                            <dd>{$data['credit']}</dd>
                            <dt>流量總值</dt>
                            <dd>-{$data['package']->price}</dd>
                            <dt>購買後結餘</dt>
                            <dd>{$data['credit']-$data['package']->price}</dd>
                            
                        </dl>

                    </div>
                    	<div class="box-footer">
                    		{if ($data['credit']-$data['package']->price >= 0)}
                        	<button type="submit" id="confirm-buy" class="btn btn-primary">購買</button>
                        	{else}
                        	<button type="" id="" class="btn btn-danger" disabled>結餘不足</button>
                        	{/if}
                    	</div>
                    </div><!-- /.box-body -->
                    {/if}
                </div><!-- /.box -->
            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $("#msg-success").hide();
    $("#msg-error").hide();
    $("#ss-msg-success").hide();
</script>
{if (isset($data))}
<script>
    $(document).ready(function () {
        $("#confirm-buy").click(function () {
        	$("#confirm-buy").hide();
            $.ajax({
                type: "POST",
                url: "buypackage",
                dataType: "json",
                data: {
                    traffpackage: {($data['id'])}
                },
                success: function (data) {
                    if (data.ret) {
                    	console.log(data.ret);
                        $("#msg-success").show();
                        $("#msg-success-p").html(data.msg);
                       	$(".box-body").html("");
                    } else {
                    	console.log(data.ret);
                        $("#msg-error").show();
                        $("#msg-error-p").html(data.msg);
                    }
                },
                error: function (jqXHR) {
                    alert("發生錯誤：" + jqXHR.status);
                }
            })
        })
    })
</script>
{/if}
{include file='user/footer.tpl'}